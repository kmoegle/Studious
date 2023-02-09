//! Function to make the Add Card Form visible/invisible based on the current display state
function displayAdd(event) {
    const addCardForm = document.getElementById('add-card-form')
    if (addCardForm.style.display === 'block'){
        addCardForm.reset();
        addCardForm.style.display = 'none'
    } else {

        addCardForm.style.display = 'block';
    }
}

//! Function to make the Edit Card Form visible/invisible based on the current display state
function displayEdit(event) {
    const editCardForm = document.getElementById('edit-card-form')
    if (editCardForm.style.display === 'block'){
        editCardForm.reset();
        editCardForm.style.display = 'none'
    } else {
        editCardForm.style.display = 'block';
    }
}

//! Function to prefill the Edit Card form with the front and back data based on ID entered by user
//! USES AJAX
function prefillEdit(event){
    const editCardForm = document.getElementById('edit-card-form');
    const frontField = document.getElementById('front-input-edit-card');
    const backField = document.getElementById('back-input-edit-card');
    const submitButton = document.getElementById('submit-input-edit-card')
    const value = event.target.value;
    //! set the fields back to the default state when the ID field is empty
    if (value.length === 0){
        frontField.value = '';
        frontField.disabled = true;
        backField.value = '';
        backField.disabled = true;
        submitButton.disabled = true;

    }
    //! if the user entered valid and positive integer initiate AJAX
    const num = Number(value);
    if (Number.isInteger(num) && num > 0) {
        //! if the id is valid, prefill front field
        let request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200){
                let result = this.responseText;
                if (result !== 'not foundHOJ'){
                    frontField.value = result;
                    frontField.disabled = false;
                    submitButton.disabled = false;
                }
            }
        }
        request.open('GET', 'card-for-edit.php?front=True&id='+num, true);
        request.send();
        //! likewise prefill back field
        let request2 = new XMLHttpRequest();
        request2.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200){
                let result = this.responseText;
                if (result !== 'not foundHOJ'){
                    backField.value = result;
                    backField.disabled = false;
                }
            }
        }
        request2.open('GET', 'card-for-edit.php?back=True&id='+num, true);
        request2.send();
    }
}

function showSomething(){
    setTimeout(function (){
        alert('hey');
    }, 2000);
}

//! Function to validate the Edit Card Form on submission
function validateEditCardForm(event){
    const idField = document.getElementById('id-input-edit-card');
    const frontField = document.getElementById('front-input-edit-card');
    const backField = document.getElementById('back-input-edit-card');
    let errors = 0;
    //! checking that id field value is a non-negative integer (mysql row auto increment id)
    if (Number.isInteger(Number(idField.value)) && Number(idField.value) > 0) {
    } else {
        errors += 1
    }
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
        alert('You need to fill in all fields (the first one being a valid card ID integer, max length 320 characters per field for the other 2)).');
    } else {
        event.target.submit();
    }
}

//! Function to validate the Add Card Form on the cards page
function validateAddCardForm(event){
    const frontField = document.getElementById('front-input-add-card');
    const backField = document.getElementById('back-input-add-card');
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
//! 2. hook up all the relevant event listeners
window.onload = function (){
    mode();
    const plusButton = document.getElementById('plus-button');
    plusButton.addEventListener('click', displayAdd, false);
    const closeAddButton = document.getElementById('close-input-add-card');
    closeAddButton.addEventListener('click', displayAdd, false);
    const editButton = document.getElementById('edit-button');
    editButton.addEventListener('click', displayEdit, false)
    const idEditInput = document.getElementById('id-input-edit-card');
    idEditInput.addEventListener('keyup', prefillEdit);
    const closeEditButton = document.getElementById('close-input-edit-card');
    closeEditButton.addEventListener('click', displayEdit, false);
    const editCardForm = document.getElementById('edit-card-form');
    editCardForm.addEventListener('submit', validateEditCardForm);
    const userButton = document.getElementById('user-button');
    userButton.addEventListener('click', displayUser, false)
    const addCardForm = document.getElementById('add-card-form');
    addCardForm.addEventListener('submit', validateAddCardForm, false);
}