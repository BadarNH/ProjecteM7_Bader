var modal = document.getElementById("myModal");
var link = document.getElementById("forgotPasswordLink");
var span = document.getElementsByClassName("close")[0];

link.onclick = function() {
    modal.style.display = "flex"; // Cambiar a flex para centrar
}

span.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

document.getElementById("submitRecovery").onclick = function() {
    var email = document.getElementById("emailRecovery").value;
    alert("Email de recuperació enviat a: " + email);
    modal.style.display = "none";
}