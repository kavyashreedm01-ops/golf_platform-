const API = "http://localhost/golf_platform/api";

function logout(){
    fetch(API + "/api_logout.php")
    .then(() => {
        window.location = "index.php";
    });
}