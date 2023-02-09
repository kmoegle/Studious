//! Helper function to add zero in front of numbers less than ten for clock purposes
function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

//! Function to create the visual live clock for the user
function startTime() {
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const dayNames = ['', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    //! extracting the time data from the Date object
    let today = new Date();
    let y = today.getFullYear();
    let mo = today.getMonth();
    let da = today.getDate();
    let d = today.getDay();
    let h = today.getHours();
    let m = today.getMinutes();
    let s = today.getSeconds();
    //! add a zero in front of numbers<10
    m = checkTime(m);
    s = checkTime(s);
    //! fill in the elements with the time data
    document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
    document.getElementById('day').innerHTML = dayNames[d];
    document.getElementById('date').innerHTML = monthNames[mo] + " " + da + ", " + y;
    //! repeat continuously
    let t = setTimeout(function () {
        startTime()
    }, 500);
}

//! Function to validate the Add Card Form on the home page
function validateHomeAddCardForm(event){
    const frontField = document.getElementById('home-front-input-add-card');
    const backField = document.getElementById('home-back-input-add-card');
    let errors = 0;
    //! checking that the front field is non-empty and has good length
    if (frontField.value.length > 0 && frontField.value.length < 321){
    } else {
        errors += 1
    }
    //! checking that the back field is non-empty and has good length
    if (backField.value.length > 0 && backField.value.length < 321){
    } else {
        errors += 1
    }
    //! prevent/submit based on errors
    if (errors>0){
        event.target.preventDefault();
        alert('You need to fill in both fields (max length 320 characters per field for both).');
    } else {
        event.target.submit();
    }

}

//! Main function:
//! 1. set the current dark/light mode from LOCAL STORAGE
//! 2. start the visual clock
//! 3. hook up all the relevant event listeners
window.onload = function (){
    mode();
    startTime();
    const userButton = document.getElementById('user-button');
    userButton.addEventListener('click', displayUser, false)
    const homeAddCardForm = document.getElementById('box3');
    homeAddCardForm.addEventListener('submit', validateHomeAddCardForm)
}