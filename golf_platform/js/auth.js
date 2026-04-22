const wrapper = document.getElementById("wrapper");
const API = "http://localhost/golf_platform/api";

/* =========================
   TOOLTIP CREATION (JS)
========================= */
function createTooltip(input, message){
    let tooltip = document.createElement("span");
    tooltip.className = "tooltip";
    tooltip.innerText = message;

    input.parentNode.style.position = "relative";
    input.parentNode.appendChild(tooltip);

    // Show on focus
    input.addEventListener("focus", () => {
        tooltip.style.visibility = "visible";
    });

    // Hide on blur
    input.addEventListener("blur", () => {
        tooltip.style.visibility = "hidden";
    });

    return tooltip;
}

/* INIT TOOLTIPS */
const regName = document.getElementById("regName");
const regEmail = document.getElementById("regEmail");
const regPassword = document.getElementById("regPassword");

const loginEmail = document.getElementById("loginEmail");
const loginPassword = document.getElementById("loginPassword");

const nameTooltip = createTooltip(regName, "Enter your full name");
const emailTooltip = createTooltip(regEmail, "Enter a valid email (example@mail.com)");
const passTooltip = createTooltip(regPassword, "Minimum 6 characters required");

createTooltip(loginEmail, "Enter your registered email");
createTooltip(loginPassword, "Enter your password");

/* =========================
   UI SWITCH
========================= */
function showRegister(){ wrapper.classList.add("active"); }
function showLogin(){ wrapper.classList.remove("active"); }

/* =========================
   PASSWORD TOGGLE
========================= */
function togglePassword(id){
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}

/* =========================
   VALIDATION
========================= */
function validateRegister(){
    let valid = true;

    regNameError.innerText="";
    regEmailError.innerText="";
    regPassError.innerText="";

    if(regName.value===""){
        regNameError.innerText="Name required";
        nameTooltip.innerText = "Name cannot be empty";
        valid=false;
    }

    if(!regEmail.value.includes("@")){
        regEmailError.innerText="Invalid email";
        emailTooltip.innerText = "Enter a valid email like user@mail.com";
        valid=false;
    }

    if(regPassword.value.length<6){
        regPassError.innerText="Min 6 chars";
        passTooltip.innerText = "Password too short!";
        valid=false;
    }

    return valid;
}

function validateLogin(){
    let valid=true;

    loginEmailError.innerText="";
    loginPassError.innerText="";

    if(loginEmail.value===""){
        loginEmailError.innerText="Email required";
        valid=false;
    }

    if(loginPassword.value===""){
        loginPassError.innerText="Password required";
        valid=false;
    }

    return valid;
}

/* =========================
   REGISTER
========================= */
function register(){
    if(!validateRegister()) return;

    fetch(API + "/register_api.php", {
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:new URLSearchParams({
            name:regName.value,
            email:regEmail.value,
            password:regPassword.value
        })
    })
    .then(r=>r.json())
    .then(d=>{
        alert(d.message||d.status);
        if(d.status==="success") showLogin();
    });
}

/* =========================
   LOGIN
========================= */
async function login(){

    if(!validateLogin()) return;

    let email = loginEmail.value;
    let password = loginPassword.value;

    let res = await fetch("auth.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `action=login&email=${email}&password=${password}`
    });

    let data = await res.json();
    console.log(data);

    if(data.status === "success"){
        alert(data.message);
        window.location.href = data.redirect;
    } else {
        alert(data.message || "Login failed");
    }
}