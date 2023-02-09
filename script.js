//! function to set the color palette css variables to dark mode
function changeToDarkMode(){
    document.documentElement.style.setProperty('--primary', '#fd558f');
    document.documentElement.style.setProperty('--primary-light', '#c51162');
    document.documentElement.style.setProperty('--primary-light-hover', '#380016');
    document.documentElement.style.setProperty('--primary-dark', '#d7396c');
    document.documentElement.style.setProperty('--primary-darker', '#a5224b');
    document.documentElement.style.setProperty('--accent', '#01cb86');
    document.documentElement.style.setProperty('--accent-light', '#00e898');
    document.documentElement.style.setProperty('--accent-dark', '#02a76e');
    document.documentElement.style.setProperty('--shadow-color', '#1f1f1f');
    document.documentElement.style.setProperty('--text-color', '#ffffff');
    document.documentElement.style.setProperty('--text-color-on-primary', '#000000');
    document.documentElement.style.setProperty('--body-background', '#323232');
    document.documentElement.style.setProperty('--isDarkMode', 'true');
    document.getElementById('mode-icon').innerHTML = 'light_mode';
}

//! function to set the color palette css variables to light mode
function changeToLightMode(){
    document.documentElement.style.setProperty('--primary', '#2962ff');
    document.documentElement.style.setProperty('--primary-light', '#768fff');
    document.documentElement.style.setProperty('--primary-light-hover', '#b6e3ff');
    document.documentElement.style.setProperty('--primary-dark', '#0039cb');
    document.documentElement.style.setProperty('--primary-darker', '#002171');
    document.documentElement.style.setProperty('--accent', '#c51162');
    document.documentElement.style.setProperty('--accent-light', '#fd558f');
    document.documentElement.style.setProperty('--accent-dark', '#8e0038');
    document.documentElement.style.setProperty('--shadow-color', '#a0a0a0');
    document.documentElement.style.setProperty('--text-color', '#444444');
    document.documentElement.style.setProperty('--text-color-on-primary', '#ffffff');
    document.documentElement.style.setProperty('--body-background', '#f3f3f3');
    document.documentElement.style.setProperty('--isDarkMode', 'false');
    document.getElementById('mode-icon').innerHTML = 'dark_mode';
}

//! function to display the hidden pop-up with the username
function displayUser(event) {
    const popUp = document.getElementById('user-pop-up');
    popUp.style.display = 'block';
    setTimeout(function(){
        popUp.style.display = 'none'
    }, 2000);
}

//! function to properly switch between light/dark mode
function mode(){
    //! we want to ensure that the local storage holds information about the current mode
    //! and whenever we open our website in the same browser, the design reflects user settings
    //! if local storage does not hold such value, set it to NOT DARK
    if (localStorage.getItem("isDarkMode") === null) {
        localStorage.setItem('isDarkMode', 'false');
    //! if it does AND it is dark, reflect that visually
    } else if (localStorage.getItem('isDarkMode') === 'true') {
        changeToDarkMode();
    }
    //! add a listener to the button to change the mode and update local storage whenever user clicks it
    const darkModeButton = document.getElementById("dark-mode-button");
    darkModeButton.addEventListener('click', function (){
        if(getComputedStyle(document.documentElement).getPropertyValue('--isDarkMode')==='false'){
            localStorage.setItem('isDarkMode', 'true');
            changeToDarkMode()
        } else {
            localStorage.setItem('isDarkMode', 'false');
            changeToLightMode()
        }
    }, false);
}

