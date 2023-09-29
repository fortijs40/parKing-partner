function openPopup() {
    const popup = document.getElementById("login-register-popup");
    popup.classList.add("active");
}
function closePopup() {
    const popup = document.getElementById("login-register-popup");
    popup.classList.remove("active");
}
const loginRegisterButton = document.getElementById("login-register-button");
loginRegisterButton.addEventListener("click", openPopup);