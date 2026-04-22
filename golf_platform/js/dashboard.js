console.log("DASHBOARD JS LOADED");

/* ---------------- LOAD DASHBOARD ---------------- */
async function loadDashboard() {
    try {
        let res = await fetch("/golf_platform/api/getUserData.php");
        let data = await res.json();

        console.log("API DATA:", data);

        /* ---------------- SUBSCRIPTION ---------------- */
        document.getElementById("subscriptionStatus").innerText =
            data.subscription || "Inactive";

        if(document.getElementById("renewalDate")){
            document.getElementById("renewalDate").innerText =
                data.renewal_date || "--";
        }

        /* ---------------- ENTRIES ---------------- */
        if(document.getElementById("entries")){
            document.getElementById("entries").innerText =
                data.entries || 0;
        }

        /* ---------------- TOTAL WON ---------------- */
        document.getElementById("totalWon").innerText =
            data.winnings || 0;

        /* ---------------- SCORES ---------------- */
        let scoreTable = document.getElementById("scoreTable");

        if(scoreTable){
            scoreTable.innerHTML = "";

            if (!data.scores || data.scores.length === 0) {
                scoreTable.innerHTML = "<tr><td colspan='3'>No scores</td></tr>";
            } else {
                data.scores.forEach(s => {
                    scoreTable.innerHTML += `
                        <tr>
                            <td>${s.score_date}</td>
                            <td>${s.score}</td>
                            <td>
                                <button onclick="editScore('${s.score_date}', ${s.score})">Edit</button>
                                <button onclick="deleteScore('${s.score_date}')">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            }
        }

        /* ---------------- WINNINGS ---------------- */
        let winTable = document.getElementById("winningsTable");

        if(winTable){
            winTable.innerHTML = "";

            if (!data.winnings_list || data.winnings_list.length === 0) {
                winTable.innerHTML = "<tr><td colspan='5'>No winnings yet</td></tr>";
            } else {

                data.winnings_list.forEach(w => {

                    let status = (w.verification_status || "").toLowerCase();

                    let proofText = "";
                    let actionUI = "";

                    if (status === "" || status === "pending") {
                        proofText = "Not submitted";
                        actionUI = `
                            <input type="file" id="proof_${w.id}">
                            <button onclick="uploadProof(${w.id})">Upload</button>
                        `;
                    } 
                    else if (status === "submitted") {
                        proofText = "Submitted ⏳";
                        actionUI = "Waiting";
                    } 
                    else if (status === "approved") {
                        proofText = "Approved ✔";
                        actionUI = "<span style='color:#28a745;'>Done</span>"; // ✅ UPDATED
                    } 
                    else if (status === "rejected") {
                        proofText = "Rejected ❌";
                        actionUI = `
                            <input type="file" id="proof_${w.id}">
                            <button onclick="uploadProof(${w.id})">Re-upload</button>
                        `;
                    } 
                    else {
                        proofText = "N/A";
                        actionUI = "";
                    }

                    winTable.innerHTML += `
                        <tr>
                            <td>${w.draw_id}</td>
                            <td>₹${w.prize}</td>
                            <td>${w.status}</td>
                            <td>${proofText}</td>
                            <td>${actionUI}</td>
                        </tr>
                    `;
                });
            }
        }

    } catch (err) {
        console.error("DASHBOARD ERROR:", err);
    }
}

/* ---------------- LOAD CHARITIES ---------------- */
async function loadCharities() {
    let res = await fetch("/golf_platform/api/get_charities.php");
    let data = await res.json();

    let select = document.getElementById("charity");
    if(!select) return;

    select.innerHTML = "";

    data.data.forEach(c => {
        let option = document.createElement("option");
        option.value = c.id;
        option.textContent = c.name;
        select.appendChild(option);
    });
}

/* ---------------- USER CHARITY ---------------- */
async function loadUserCharity() {
    let res = await fetch("/golf_platform/api/get_user_charity.php");
    let data = await res.json();

    if (data.data && document.getElementById("selectedCharity")) {
        document.getElementById("selectedCharity").innerText =
            `${data.data.name} (${data.data.percentage}%)`;
    }
}

/* ---------------- ADD SCORE ---------------- */
async function addScore() {

    let score = document.getElementById("score").value;
    let date = document.getElementById("date").value;

    if(!score || !date){
        alert("Enter score and date");
        return;
    }

    try {
        let res = await fetch("/golf_platform/api/add_score.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                score: score,
                date: date
            })
        });

        let data = await res.json();

        if(data.status === "success"){
            alert("Score added ✅");

            document.getElementById("score").value = "";
            document.getElementById("date").value = "";

            loadDashboard();
        } else {
            alert(data.message);
        }

    } catch(err){
        console.error(err);
        alert("Error adding score");
    }
}

/* ---------------- EDIT SCORE ---------------- */
async function editScore(date, oldScore){

    let newScore = prompt("Enter new score:", oldScore);

    if(!newScore) return;

    await fetch("/golf_platform/api/edit_score.php", {
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`date=${date}&score=${newScore}`
    });

    loadDashboard();
}

/* ---------------- DELETE SCORE ---------------- */
async function deleteScore(date){

    if(!confirm("Are you sure you want to delete this score?")) return;

    await fetch("/golf_platform/api/delete_score.php", {
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:"date="+date
    });

    loadDashboard();
}

/* ---------------- SAVE CHARITY ---------------- */
async function saveCharity() {
    let charity_id = document.getElementById("charity").value;
    let percentage = document.getElementById("percent").value;

    let res = await fetch("/golf_platform/api/save_charity.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `charity_id=${charity_id}&percentage=${percentage}`
    });

    let data = await res.json();
    alert(data.message);

    loadUserCharity();
}

/* ---------------- UPLOAD PROOF ---------------- */
async function uploadProof(winnerId){

    let fileInput = document.getElementById("proof_" + winnerId);

    if(!fileInput || !fileInput.files[0]){
        alert("Select file first");
        return;
    }

    let formData = new FormData();
    formData.append("winner_id", winnerId);
    formData.append("proof", fileInput.files[0]);

    let res = await fetch("/golf_platform/api/upload_proof.php", {
        method: "POST",
        body: formData
    });

    let data = await res.json();
    alert(data.message);

    loadDashboard();
}

/* ---------------- INIT ---------------- */
document.addEventListener("DOMContentLoaded", () => {
    loadDashboard();
    loadCharities();
    loadUserCharity();
});