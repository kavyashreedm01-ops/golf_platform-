const API = "http://localhost/golf-platform/api";

/* REGISTER */
function register() {
    fetch(API + "/register.php", {
        method: "POST",
        body: new URLSearchParams({
            name: document.getElementById("name").value,
            email: document.getElementById("email").value,
            password: document.getElementById("password").value
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === "success"){
            alert("Registered!");
            window.location = "login.php";
        }
    });
}

/* LOGIN */
function login() {
    fetch(API + "/login.php", {
        method: "POST",
        body: new URLSearchParams({
            email: document.getElementById("email").value,
            password: document.getElementById("password").value
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === "success"){
            window.location = "user/dashboard.php";
        } else {
            alert("Invalid login");
        }
    });
}

/* ADD SCORE */
function addScore() {
    fetch(API + "/add_score.php", {
        method: "POST",
        body: new URLSearchParams({
            score: document.getElementById("score").value,
            date: document.getElementById("date").value
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.status);
        loadScores();
    });
}

/* LOAD SCORES */
function loadScores() {
    fetch(API + "/get_scores.php")
    .then(res => res.json())
    .then(data => {
        let list = document.getElementById("scoreList");
        list.innerHTML = "";

        data.forEach(s => {
            list.innerHTML += `<li>${s.score} - ${s.score_date}</li>`;
        });
    });
}

/* LOGOUT */
function logout() {
    fetch(API + "/logout.php", {
        method: "POST"
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === "success"){
            window.location = "../login.php"; // adjust if needed
        }
    });
}