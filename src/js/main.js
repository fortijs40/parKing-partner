//For switching between light and dark theme
const lightThemeBtn = document.getElementById('light-theme-toggle');
const darkThemeBtn = document.getElementById('dark-theme-toggle');
const logoutButton = document.getElementById('logout-button');
logoutButton.addEventListener('click', function() {
  // Redirect to the logout page or perform your logout action here
  window.location.href = '../php/logout.php'; // Replace 'logout.php' with the actual URL to your logout script
});

function setInitialTheme() {
    const theme = getThemeFromCookie();

    if (theme === 'dark') {
      darkThemeBtn.classList.remove('hide');
      lightThemeBtn.classList.add('hide');
      document.getElementById('theme-style').href = 'src/css/colors-dark.css';
    }
  }
function setThemeCookie(theme) {
    document.cookie = `theme=${theme}; path=/; expires=Fri, 31 Dec 9999 23:59:59 GMT`;
  }
  lightThemeBtn.addEventListener('click', () => {
    setThemeCookie('dark'); // Set the theme preference in a cookie
    lightThemeBtn.classList.add('hide');
    darkThemeBtn.classList.remove('hide');
    document.getElementById('theme-style').href = 'src/css/colors-dark.css';
  });
  darkThemeBtn.addEventListener('click', () => {
    setThemeCookie('light'); // Set the theme preference in a cookie
    darkThemeBtn.classList.add('hide');
    lightThemeBtn.classList.remove('hide');
    document.getElementById('theme-style').href = 'src/css/colors-light.css';
  });
  // Function to retrieve the theme preference from a cookie
  function getThemeFromCookie() {
    const cookieValue = document.cookie
      .split('; ')
      .find(row => row.startsWith('theme='))
      .split('=')[1];
    return cookieValue || 'light'; // Default to 'light' if the cookie is not set.
  }

  // Call the function to set the initial theme
  setInitialTheme();

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