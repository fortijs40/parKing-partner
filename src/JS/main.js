//For switching between light and dark theme
const lightThemeBtn = document.getElementById('light-theme-toggle');
const darkThemeBtn = document.getElementById('dark-theme-toggle');

lightThemeBtn.addEventListener('click', () => {
    lightThemeBtn.classList.add('hide');
    darkThemeBtn.classList.remove('hide');
    document.getElementById('theme-style').href = 'src/css/colors-dark.css';
});

darkThemeBtn.addEventListener('click', () => {
    darkThemeBtn.classList.add('hide');
    lightThemeBtn.classList.remove('hide');
    document.getElementById('theme-style').href = 'src/css/colors-light.css';
});

const popupBackgroundObj = document.getElementById("popup-background");
const mainBodyObj = document.getElementById("main-body");
let popupObj = null;

//For closing and opening popups
function closePopup() {
    document.getElementById(popupObj).classList.add('hide');
    popupBackgroundObj.classList.add('hide');
    mainBodyObj.classList.remove('popup-blur');
}

function openPopup(popup) {
    popupObj = popup;
    document.getElementById(popupObj).classList.remove('hide');
    popupBackgroundObj.classList.remove('hide');
    mainBodyObj.classList.add('popup-blur');
}

const password = document.getElementById("password")
    , confirm_password = document.getElementById("conf-password");

function validatePassword(){
    if(password.value !== confirm_password.value) {
        confirm_password.setCustomValidity("Passwords Don't Match");
    } else {
        confirm_password.setCustomValidity('');
    }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;

// Get the links
const companyLink = document.getElementById("company-link");
const personLink = document.getElementById("person-link");

// Function to set the active link
function setActiveLink(link) {
    // Remove the "active" class from all links
    companyLink.classList.remove("active");
    personLink.classList.remove("active");

    // Add the "active" class to the specified link
    link.classList.add("active");
}

// Event listeners to set the active link
companyLink.addEventListener("click", function() {
    setActiveLink(companyLink);
});

personLink.addEventListener("click", function() {
    setActiveLink(personLink);
});
