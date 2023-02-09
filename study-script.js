//! Function that reveals the answer as well as feedback buttons whenever user clicks the REVEAL button
function revealAnswer(event){
    //! reveal the answer
    const answerText = document.getElementById('test-answer');
    answerText.style.color = 'rgba(255, 255, 255, 1)';
    //! reveal the prompt so that user knows they have to rate their memory response
    const question = document.getElementById('how-was-it');
    question.style.color = 'rgba(255, 255, 255, 1)';
    //! hide the button that was clicked
    event.target.style.display = 'none';
    //! display feedback buttons
    const positiveButton = document.getElementById('positive-feedback');
    const negativeButton = document.getElementById('negative-feedback');
    positiveButton.style.visibility = 'visible';
    negativeButton.style.visibility = 'visible';
}

//! Main function:
//! 1. set the current dark/light mode from LOCAL STORAGE
//! 2. hook up all the relevant event listeners
window.onload = function (){
    const userButton = document.getElementById('user-button');
    userButton.addEventListener('click', displayUser, false);
    const revealButton = document.getElementById('reveal-button')
    revealButton.addEventListener('click', revealAnswer, false)
}