//! Function to display the sign-in pop-up window based on the current display state
function displaySignIn() {
    const signInForm = document.getElementById('sign-in-form')
    if (signInForm.style.display === 'block'){
        signInForm.reset();
        signInForm.style.display = 'none'
    } else {
        signInForm.style.display = 'block';
    }
}

//! Function to validate the sign-up form
function validateSignUpForm(event) {
    const firstNameField = document.getElementById('first-name-input-sign-up');
    const lastNameField = document.getElementById('last-name-input-sign-up');
    const usernameField = document.getElementById('username-input-sign-up')
    const emailField = document.getElementById('email-input-sign-up');
    const passwordField = document.getElementById('password-input-sign-up');
    const consentCheckbox = document.getElementById('gdpr-input-sign-up');
    let errors = 0;
    let errorMessages = [];
    //! checking that fields have the required length
    if (firstNameField.value.length < 1 || firstNameField.value.length > 32){
        errors += 1;
        errorMessages.push('first name length is not between 1 and 32');
    }
    if (lastNameField.value.length < 1 || lastNameField.value.length > 32){
        errors += 1;
        errorMessages.push('last name length is not between 1 and 32');
    }
    if (usernameField.value.length < 1 || usernameField.value.length > 32){
        errors += 1;
        errorMessages.push('username length is not between 1 and 32');
    }
    if (emailField.value.length < 1 || emailField.value.length > 320){
        errors += 1;
        errorMessages.push('email length is not between 1 and 320');
    }
    if (passwordField.value.length < 1 || passwordField.value.length > 32){
        errors += 1;
        errorMessages.push('password length is not between 1 and 32');
    }
    //! checking that fields do not have any leading or trailing spaces
    if(firstNameField.value !== firstNameField.value.trim()){
        errors += 1;
        errorMessages.push('first name has leading or trailing spaces');
    }
    if(lastNameField.value !== lastNameField.value.trim()){
        errors += 1;
        errorMessages.push('last name has leading or trailing spaces');
    }
    if(usernameField.value !== usernameField.value.trim()){
        errors += 1;
        errorMessages.push('username has leading or trailing spaces');
    }
    if(emailField.value !== emailField.value.trim()){
        errors += 1;
        errorMessages.push('email has leading or trailing spaces');
    }
    if(passwordField.value !== passwordField.value.trim()){
        errors += 1;
        errorMessages.push('password has leading or trailing spaces');
    }
    //! checking that first and last name satisfy regex (one word)
    if(/^[a-zA-Zá-žÁ-Ž]+$/.test(firstNameField.value)){
    } else {
        errors += 1;
        errorMessages.push('first name must be a valid one word name');
    }
    if(/^[a-zA-Zá-žÁ-Ž]+$/.test(lastNameField.value)){
    } else {
        errors += 1;
        errorMessages.push('last name must be a valid one word name');
    }
    //! checking that the username uses only allowed characters
    if(/^[a-zA-Z0-9]+$/.test(usernameField.value)){
    } else {
        errors += 1;
        errorMessages.push('username can only use alphanumerical characters');
    }
    //! checking that the email is valid
    if(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-z]{2,4}$/.test(emailField.value)){
    } else {
        errors += 1;
        errorMessages.push('email must be in a valid format');
    }
    //! checking that the mandatory checkbox is checked
    if(consentCheckbox.checked){
    } else {
        errors += 1
        errorMessages.push('you must consent with our policy by checking the checkbox')
    }
    //! prevent/submit based on errors
    if (errors>0){
        event.target.preventDefault();
        //! create and report an informed alert message
        alert('Total of '+errors+' found: '+errorMessages.toString());
    } else {
        event.target.submit();
    }
}

//! Function to validate the sign-in form
function validateSignInForm(event) {
    const usernameField = document.getElementById('username-input-sign-in');
    const passwordField = document.getElementById('password-input-sign-in');
    let errors = 0;
    //! because we are verifying password and username input, we do not want to disclose any hints to a potential malicious user
    //! thus we check only required length for both fields
    if (usernameField.value.length > 0 && usernameField.value.length < 33){
    } else {
        errors += 1
    }
    //! checking that the back field is non-empty and has good length
    if (usernameField.value.length > 0 && passwordField.value.length < 33){
    } else {
        errors += 1
    }
    //! prevent/submit based on errors
    if (errors>0){
        event.target.preventDefault();
        alert('You need to fill in both fields (max length 32 characters per field for both).');
    } else {
        event.target.submit();
    }
}

//! Function to display to user, whether they satisfied password requirements
function passwordTips(event) {
    const defaultColor = getComputedStyle(document.documentElement).getPropertyValue('--text-color');
    //! get the individual list items
    const requirementObjects = document.getElementById('password-requirements').children;
    const lowercase = requirementObjects[0];
    const uppercase = requirementObjects[1];
    const digits = requirementObjects[2];
    const length = requirementObjects[3];
    const spaces = requirementObjects[4];
    const value = event.target.value;
    //! set the default gray state when there is no input
    if (value.length === 0) {
        lowercase.style.color = defaultColor;
        uppercase.style.color = defaultColor;
        digits.style.color = defaultColor;
        length.style.color = defaultColor;
        spaces.style.color = defaultColor;
        lowercase.innerHTML = 'Lowercase letter (a-z)';
        uppercase.innerHTML = 'Uppercase letter (A-Z)';
        digits.innerHTML = 'Digit (0-9)';
        length.innerHTML = 'Length from 8 to 32';
        spaces.innerHTML = 'No leading or trailing spaces';
        return;
    //! LENGTH REQUIREMENT
    } else if (value.length < 8) {
        length.style.color = 'red';
        length.innerHTML = "Must be longer";
    } else if (value.length > 32){
        length.style.color = 'red';
        length.innerHTML = "Must be shorter";
    } else {
        length.style.color = 'green';
        length.innerHTML = "Has correct length"
    }
    //! LOWERCASE REQUIREMENT
    if (/[a-z]/.test(value)){
        lowercase.style.color = 'green';
        lowercase.innerHTML = 'Contains lowercase (a-z)';
    } else {
        lowercase.style.color = 'red';
        lowercase.innerHTML = 'Must contain lowercase (a-z)';
    }
    //! UPPERCASE REQUIREMENT
    if (/[A-Z]/.test(value)){
        uppercase.style.color = 'green';
        uppercase.innerHTML = 'Contains uppercase (A-Z)';
    } else {
        uppercase.style.color = 'red';
        uppercase.innerHTML = 'Must contain uppercase (A-Z)';
    }
    //! DIGIT REQUIREMENT
    if (/\d/.test(value)){
        digits.style.color = 'green';
        digits.innerHTML = 'Contains a digit (0-9)';
    } else {
        digits.style.color = 'red';
        digits.innerHTML = 'Must contain a digit (0-9)';
    }
    //! NO LEADING TRAILING SPACES REQUIREMENT
    if (value.trim() === value) {
        spaces.style.color = 'green';
        spaces.innerHTML = 'Does not contain leading/trailing spaces';
    } else {
        spaces.style.color = 'red';
        spaces.innerHTML = 'Must not contain leading/trailing spaces';
    }
}

//! Function to display to user, whether they satisfied first name requirements
function firstNameTips(event) {
    const defaultColor = getComputedStyle(document.documentElement).getPropertyValue('--text-color');
    //! get the individual list items
    const requirementObjects = document.getElementById('first-name-requirements').children;
    const length = requirementObjects[0];
    const spaces = requirementObjects[1];
    const value = event.target.value;
    //! set the default gray state when there is no input
    if (value.length === 0) {
        length.style.color = defaultColor;
        length.innerHTML = 'Length from 1 to 32'
        spaces.style.color = defaultColor;
        spaces.innerHTML = 'No spaces allowed'
        return;
    //! LENGTH REQUIREMENT
    } else if (value.length > 32) {
        length.style.color = 'red';
        length.innerHTML = 'Must be shorter';
    } else {
        length.style.color = 'green';
        length.innerHTML = `Has the correct length`
    }
    //! NO SPACES REQUIREMENT
    if (/\s/.test(value)) {
        spaces.style.color = 'red';
        spaces.innerHTML = 'Must not contain any spaces'
    } else {
        spaces.style.color = 'green';
        spaces.innerHTML = 'Does not contain any spaces'
    }
}

//! Function to display to user, whether they satisfied last name requirements
function lastNameTips(event) {
    const defaultColor = getComputedStyle(document.documentElement).getPropertyValue('--text-color');
    //! get the individual list items
    const requirementObjects = document.getElementById('last-name-requirements').children;
    const length = requirementObjects[0];
    const spaces = requirementObjects[1];
    const value = event.target.value;
    //! set the default gray state when there is no input
    if (value.length === 0) {
        length.style.color = defaultColor;
        length.innerHTML = 'Length from 1 to 32'
        spaces.style.color = defaultColor;
        spaces.innerHTML = 'No spaces allowed'
        return;
    //! LENGTH REQUIREMENT
    } else if (value.length > 32) {
        length.style.color = 'red';
        length.innerHTML = 'Must be shorter';
    } else {
        length.style.color = 'green';
        length.innerHTML = `Has the correct length`
    }
    //! NO SPACES REQUIREMENT
    if (/\s/.test(value)) {
        spaces.style.color = 'red';
        spaces.innerHTML = 'Must not contain any spaces'
    } else {
        spaces.style.color = 'green';
        spaces.innerHTML = 'Does not contain any spaces'
    }
}

//! Function to display to user, whether they satisfied username requirements
//! USES AJAX
function usernameTips(event) {
    const defaultColor = getComputedStyle(document.documentElement).getPropertyValue('--text-color');
    //! get the individual list items
    const requirementObjects = document.getElementById('username-requirements').children;
    const alphanumerical = requirementObjects[0];
    const length = requirementObjects[1];
    const spaces = requirementObjects[2];
    const availability = requirementObjects[3];
    const value = event.target.value;
    //! set the default gray state when there is no input
    if (value.length === 0) {
        alphanumerical.style.color = defaultColor;
        alphanumerical.innerHTML = 'Only a-z, A-Z, 0-9 can be used'
        length.style.color = defaultColor;
        length.innerHTML = 'Length from 1 to 32'
        spaces.style.color = defaultColor;
        spaces.innerHTML = 'No spaces allowed'
        availability.style.color = defaultColor;
        availability.innerHTML = 'Unique and available';
        return;
    //! LENGTH REQUIREMENT
    } else if (value.length > 32){
        length.style.color = 'red';
        length.innerHTML = 'Must be shorter';
    } else {
        length.style.color = 'green';
        length.innerHTML =  `Has the correct length`
    }
    //! NO SPACES REQUIREMENT
    if (/\s/.test(value)){
        spaces.style.color = 'red';
        spaces.innerHTML = 'Must not contain any spaces'
    } else {
        spaces.style.color = 'green';
        spaces.innerHTML = 'Does not contain any spaces'
    }
    //! ONLY ALLOWED CHARACTERS REQUIREMENT
    if (/^[a-zA-Z0-9]+$/.test(value)){
        alphanumerical.style.color = 'green';
        alphanumerical.innerHTML = 'Uses only a-z, A-Z, 0-9'
    } else {
        alphanumerical.style.color = 'red';
        alphanumerical.innerHTML = 'Must not use other than a-z, A-Z, 0-9'
    }
    //! AJAX: USERNAME AVAILABILITY
    let request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if(this.readyState === 4 && this.status === 200){
            let result = this.responseText;
            if (result === 'found'){
                availability.style.color = 'red';
                availability.innerHTML = 'Username taken'
            } else if (result === 'not found') {
                availability.style.color = 'green';
                availability.innerHTML = 'Username available'
            }
        }
    }
    request.open('GET', 'availability-check.php?usernameInput='+value, true);
    request.send();
}

//! Function to display to user, whether they satisfied email requirements
//! USES AJAX
function emailTips(event){
    const defaultColor = getComputedStyle(document.documentElement).getPropertyValue('--text-color');
    //! get the individual list items
    const requirementObjects = document.getElementById('email-requirements').children;
    const emailFormat = requirementObjects[0];
    const length = requirementObjects[1];
    const spaces = requirementObjects[2];
    const availability = requirementObjects[3];
    const value = event.target.value;
    //! set the default gray state when there is no input
    if (value.length === 0) {
        emailFormat.style.color = defaultColor;
        emailFormat.innerHTML = 'Correct email format'
        length.style.color = defaultColor;
        length.innerHTML = 'Length from 6 to 320'
        spaces.style.color = defaultColor;
        spaces.innerHTML = 'No spaces allowed'
        availability.style.color = defaultColor;
        availability.innerHTML = 'Not already registered';
        return;
    //! LENGTH REQUIREMENT
    } else if (value.length > 320){
        length.style.color = 'red';
        length.innerHTML = 'Must be shorter';
    } else if (value.length < 6) {
        length.style.color = 'red';
        length.innerHTML = 'Must be longer'
    } else {
        length.style.color = 'green';
        length.innerHTML =  `Has the correct length`
    }
    //! NO SPACES REQUIREMENT
    if (/\s/.test(value)){
        spaces.style.color = 'red';
        spaces.innerHTML = 'Must not contain any spaces'
    } else {
        spaces.style.color = 'green';
        spaces.innerHTML = 'Does not contain any spaces'
    }
    //! EMAIL FORMAT REQUIREMENT
    if (/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-z]{2,4}$/.test(value)){
        emailFormat.style.color = 'green';
        emailFormat.innerHTML = 'Is a valid email address'
    } else {
        emailFormat.style.color = 'red';
        emailFormat.innerHTML = 'Must be a valid email address'
    }
    //! AJAX: EMAIL AVAILABILITY
    let request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if(this.readyState === 4 && this.status === 200){
            let result = this.responseText;
            if (result === 'found'){
                availability.style.color = 'red';
                availability.innerHTML = 'Email already registered'
            } else if (result === 'not found') {
                availability.style.color = 'green';
                availability.innerHTML = 'Email not yet registered'
            }
        }
    }
    request.open('GET', 'availability-check.php?emailInput='+value, true);
    request.send();

}

//! Main function:
//! 1. set the current dark/light mode from LOCAL STORAGE
//! 2. hook up all the relevant event listeners
window.onload = function (){
    mode();
    const cancelSignInButton = document.getElementById('cancel-input-sign-in');
    cancelSignInButton.addEventListener('click', displaySignIn, false);
    const signInButton = document.getElementById('sign-in-button');
    signInButton.addEventListener('click', displaySignIn, false);
    const alreadyAMemberButton = document.getElementById('already-a-member');
    alreadyAMemberButton.addEventListener('click', displaySignIn, false);
    const signInForm = document.getElementById('sign-in-form');
    signInForm.addEventListener('submit', validateSignInForm, false);
    const firstNameInputSignUp = document.getElementById('first-name-input-sign-up');
    firstNameInputSignUp.addEventListener('keyup', firstNameTips, false);
    const lastNameInputSignUp = document.getElementById('last-name-input-sign-up');
    lastNameInputSignUp.addEventListener('keyup', lastNameTips, false);
    const usernameInputSignUp = document.getElementById('username-input-sign-up');
    usernameInputSignUp.addEventListener('keyup', usernameTips, false);
    const emailInputSignUp = document.getElementById('email-input-sign-up');
    emailInputSignUp.addEventListener('keyup', emailTips, false);
    const passwordInputSignUp = document.getElementById('password-input-sign-up');
    passwordInputSignUp.addEventListener('keyup', passwordTips, false);
    const userButton = document.getElementById('user-button');
    userButton.addEventListener('click', displayUser, false);
    const signUpForm = document.getElementById('sign-up-form');
    signUpForm.addEventListener('submit', validateSignUpForm, false);
}