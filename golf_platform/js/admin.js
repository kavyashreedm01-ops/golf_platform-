const BASE_URL = "/golf_platform/api";

/* ---------------- RUN DRAW ---------------- */
async function runDraw(){
    let res = await fetch(BASE_URL + "/run_draw.php");
    let data = await res.json();

    if(data.status === "success"){
        let el = document.getElementById("drawMsg");
        if(el){
            el.innerText = "Draw Numbers: " + data.numbers.join(", ");
        }
        loadWinners();
    } else {
        alert(data.message);
    }
}

/* ---------------- LOAD WINNERS ---------------- */
async function loadWinners(){

    let res = await fetch(BASE_URL + "/get_winners.php");
    let data = await res.json();

    let table = document.getElementById("winnerTable");
    if(!table) return;

    table.innerHTML = "";

    if(!data || data.length === 0){
        table.innerHTML = "<tr><td colspan='8'>No winners yet</td></tr>";
        return;
    }

    data.forEach(w => {

        let proof = w.proof
            ? `<a href="uploads/${w.proof}" target="_blank">View</a>`
            : "No proof";

        let verify = "";

        if(w.verification_status === "submitted"){
            verify = `
                <button onclick="verify(${w.id}, 'approved')">Approve</button>
                <button onclick="verify(${w.id}, 'rejected')">Reject</button>
            `;
        } else {
            verify = w.verification_status || "Pending";
        }

        let action = w.status === "pending"
            ? `<button onclick="markPaid(${w.id})">Mark Paid</button>`
            : "Paid ✔";

        table.innerHTML += `
            <tr>
                <td>${w.user_id}</td>
                <td>${w.draw_id}</td>
                <td>${w.match_count}</td>
                <td>₹${w.prize}</td>
                <td>${w.status}</td>
                <td>${proof}</td>
                <td>${verify}</td>
                <td>${action}</td>
            </tr>
        `;
    });
}

/* ---------------- LOAD USERS ---------------- */
async function loadUsers(){

    let res = await fetch(BASE_URL + "/get_users.php");
    let data = await res.json();

    let table = document.getElementById("userTable");
    if(!table) return;

    table.innerHTML = "";

    if(!data || data.length === 0){
        table.innerHTML = "<tr><td colspan='4'>No users found</td></tr>";
        return;
    }

    let count = 1;

    data.forEach(u => {
        table.innerHTML += `
            <tr>
                <td>${count++}</td>
                <td>${u.name || "N/A"}</td>
                <td>${u.email}</td>
                <td>${u.plan ? u.plan.toUpperCase() : "No Plan"}</td>
            </tr>
        `;
    });
}

/* ---------------- STATS ---------------- */
async function loadStats(){

    try{
        let res = await fetch(BASE_URL + "/admin_stats.php");
        let data = await res.json();

        console.log("STATS:", data);

        let usersEl = document.getElementById("statUsers");
        let winnersEl = document.getElementById("statWinners");
        let prizeEl = document.getElementById("statPrize");

        if(usersEl) usersEl.innerText = data.users || 0;
        if(winnersEl) winnersEl.innerText = data.winners || 0;
        if(prizeEl) prizeEl.innerText = "₹" + (data.prize || 0);

    } catch(err){
        console.error("Stats error:", err);
    }
}

/* ---------------- MARK AS PAID ---------------- */
async function markPaid(id){

    let res = await fetch(BASE_URL + "/mark_paid.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "id=" + id
    });

    let data = await res.json();
    alert(data.message);

    loadWinners();
}

/* ---------------- VERIFY PROOF ---------------- */
async function verify(id, status){

    let res = await fetch(BASE_URL + "/verify_proof.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `id=${id}&status=${status}`
    });

    let data = await res.json();
    alert(data.message);

    loadWinners();
}

/* ---------------- CHARITY ADMIN ---------------- */
async function loadCharitiesAdmin(){

    let res = await fetch(BASE_URL + "/get_charities.php");
    let d = await res.json();

    let container = document.getElementById("charityAdmin");
    if(!container) return;

    let html = "";

    if(!d.data || d.data.length === 0){
        container.innerHTML = "<p>No charities found</p>";
        return;
    }

    d.data.forEach(c=>{
        html += `
            <div>
                ${c.name}
                <button onclick="deleteCharity(${c.id})">Delete</button>
            </div>
        `;
    });

    container.innerHTML = html;
}

async function addCharity(){

    let name = document.getElementById("cname").value;
    let desc = document.getElementById("cdesc").value;

    if(!name){
        alert("Enter charity name");
        return;
    }

    await fetch(BASE_URL + "/add_charity.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`name=${name}&description=${desc}`
    });

    document.getElementById("cname").value = "";
    document.getElementById("cdesc").value = "";

    loadCharitiesAdmin();
}

async function deleteCharity(id){
    await fetch(BASE_URL + "/delete_charity.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:"id=" + id
    });

    loadCharitiesAdmin();
}

/* ---------------- DRAW PREVIEW ---------------- */
async function simulateDraw(){
    let res = await fetch(BASE_URL + "/simulate_draw.php");
    let data = await res.json();

    let el = document.getElementById("preview");
    if(el){
        el.innerText = "Preview: " + data.numbers.join(", ");
    }
}

/* ---------------- INIT ---------------- */
document.addEventListener("DOMContentLoaded", () => {
    loadWinners();
    loadUsers();
    loadStats();
    loadCharitiesAdmin(); // 🔥 IMPORTANT (was missing)
});