// function setTheme(themeName) {
//     localStorage.setItem('theme', themeName);
//     document.documentElement.className = themeName;
// }


// function toggleTheme() {
//     if (localStorage.getItem('theme') === 'theme-dark') {
//         setTheme('theme-light');
//     } else {
//         setTheme('theme-dark');
//     }
// }


// (function () {
//     if (localStorage.getItem('theme') === 'theme-dark') {
//         setTheme('theme-dark');
//     } else {
//         setTheme('theme-light');
//     }
// })();
const themeSwitcher = document.getElementById("style-toggle.js");
themeSwitcher.addEventListener("change", toggleTheme);
function toggleTheme() {
    const body = document.body;
    if (themeSwitcher.checked) {
        body.classList.remove("theme-light");
        body.classList.add("theme-dark");
    } else {      
        body.classList.remove("theme-dark");
        body.classList.add("theme-light");
    }
}


