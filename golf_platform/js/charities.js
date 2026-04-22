let allCharities = [];

/* ---------------- LOAD CHARITIES ---------------- */
async function loadCharities(){
    try {
        let res = await fetch("/golf_platform/api/get_charities.php");
        let data = await res.json();

        allCharities = data.data || [];

        displayCharities(allCharities);

    } catch(err){
        console.error("Error loading charities:", err);
    }
}

/* ---------------- DISPLAY CHARITIES ---------------- */
function displayCharities(list){

    let container = document.getElementById("charityList");

    if(!container) return;

    let html = "";

    if(list.length === 0){
        container.innerHTML = "<p>No charities available</p>";
        return;
    }

    list.forEach(c => {

        // ✅ SAFE DESCRIPTION FIX
        let description = (c.description && c.description.trim() !== "")
            ? c.description
            : "No description available";

        html += `
            <div class="card charity-card">
                <h3>${c.name}</h3>
                <p>${description}</p>

                <button onclick="selectCharity(${c.id}, '${c.name}')">
                    Select
                </button>
            </div>
        `;
    });

    container.innerHTML = html;
}

/* ---------------- SEARCH FILTER ---------------- */
function filterCharities(){

    let q = document.getElementById("search").value.toLowerCase();

    let filtered = allCharities.filter(c =>
        c.name.toLowerCase().includes(q)
    );

    displayCharities(filtered);
}

/* ---------------- SELECT CHARITY ---------------- */
async function selectCharity(id, name){

    let percentage = prompt("Enter donation % (min 10):", "10");

    if(!percentage) return;

    let res = await fetch("/golf_platform/api/save_charity.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `charity_id=${id}&percentage=${percentage}`
    });

    let data = await res.json();

    alert(data.message || "Saved!");

    window.location.href = "userdashboard.php";
}

/* ---------------- INIT ---------------- */
document.addEventListener("DOMContentLoaded", loadCharities);