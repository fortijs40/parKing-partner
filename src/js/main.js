//listen out for when id light-theme-toggle and dark-theme-toggle is clicked, hide one another and load in either css colors-light.css or colors-dark.css

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