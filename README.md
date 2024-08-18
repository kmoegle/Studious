# Flashcard Web Application | Studious

This is my semester project in Foundations of Web Applications  [B6B39ZWA](https://bilakniha.cvut.cz/en/predmet3129506.html#gsc.tab=0)  course as part of my Software Engineering undergraduate curriculum at Czech Technical University in Prague, CZ, EU.

Designed, developed and programmed by  [Matyas Urban](https://www.linkedin.com/in/matyasurban/)  in Fall 2022.


# Brief Description

Educational dynamic web application to facilitate memory retention using flashcards and spaced repetition. Key features: accessibility, responsive web design, data persistence, input validation, ajax, threat resilience (xss, csrf).
# Quick Links

- Project demo: [link](http://wa.toad.cz/~urbanm48/studious/)
- UI Samples, manual, implementation: [link](studious.pdf)
# Technical Implementation
The web application is written purely using:
- HTML5
- CSS3
- JavaScript (ES7+)
- PHP7
- MySQL

No frameworks or special libraries are used in accordance with course requirements. This choice of stack is given by server-supported tech and course requirements.
# Files Overview
Key components of the website that hold HTML code are:

1. [index.php](index.php)
2. [home.php](home.php)
3. [cards.php](cards.php)
4. [study.php](study.php)

And their corresponding JS scripts:

1. [index-script.js](index-script.js)
2. [home-script.js](home-script.js)
3. [cards-script.js](cards-script.js)
4. [study-script.js](study-script.js)

Additionally, all HTML-holding files use [script.js](script.js).  
Website structure is styled using [style.css](style.css) plus by appropriate JS scripts. Server-side logic is handled by the following files:

1. [config.dist.php](config.dist.php) - database access credentials --> copy to `config.php` to apply the settings
2. [tasks.php](tasks.php) - DB connection functions, majority of functions handling POST
3. [availability-check.php](availability-check.php) - script to handle AJAX for username and email availability
4. [card-for-edit.php](card-for-edit.php) - script to handle AJAX XMLHttpRequest for prefilling ediCardForm
5. [delete.php](delete.php) - script to delete a specific card from a database

Additional files include:
1.  intro-background.jpg - [0,689 MB] used for index background
2.  logo-icon.png - [0.002 MB] used for logo and favicon
3.  database-logs.txt - if database connection fails, logs are to be found here

Images are compressed and used resourcefully to optimize website performance.
## User Input
- The app entails several complex forms, and all user input is thoroughly validated through HTML, JavaScript, and PHP.
- HTML validation: input attributes (required, type, maxlength, pattern, ...)
- JavaScript validation: form.addEventListener(‘submit’,validate), preventDefault, heavy input analysis through regular expressions and other measures
- PHP validation: csrf token, isset, another round of thorough analysis
- Forms are accessible with the use of label for all input elements
## Website Structure with HTML
- All HTML code has been validated to conform to modern standards and avoid deprecated expressions
- The HTML effectively uses semantic elements like header, nav, and main.
- The HTML describes purely structure, and no inline CSS is present to improve code readability. The same goes for JavaScript.
- Google Font Poppins is used throughout the document.

## Document Presentation with CSS
- All CSS rules are placed in a dedicated file fully separated from HTML code and linked appropriately using <link>.
- Flexbox is being heavily used to achieve the desired presentation of elements.
- Some rules use advanced selectors as well as CSS combinators and pseudo-classes.
- Media queries are present to achieve a compact layout on mobile devices and select only relevant information for print.
- Advanced CSS, including animations, keyframes, and variables is present in order to enhance user experience.

## Client-Side Scripting with JavaScript
- JavaScript works with CSS variables as well as LocalStorage to provide users with the option to choose dark/light mode of the website.
- JavaScript is also used to display/hide some of the forms by modifying applicable CSS
- Script for each page uses window.onload function to hookup event listeners (click, submit, keyup) to relevant objects on the page.
- The application makes use of asynchronous JavaScript XMLHttpRequest to contact the server and retrieve relevant information on the go as a user types some input.
- Website uses JavaScript mainly to facilitate visual styles, input validation and provide helpful feedback.

## Server-Side Scripting with PHP/MySQL, Security
- Application offers its functionality only to registered users and all others are redirected to an introductory page with sign-up upon an attempt to access secure part of the app.
- Another security measures include:
    - **XSS prevention** with the use of htmlspecialchars function
    - **CSRF prevention** with the use of generating a complex token for secure actions
    - **Data breach prevention by storing** hashed/salted passwords using `password_hash()`
    - **Double submission prevention** by effectively rerouting the user
    - **SQL Injection prevention** with the use of bind_param
- Most errors are effectively displayed to the user in applicable situations while sensitive database-related errors are passed on to a secure file on the server.
- User-input is not lost in case of an error, so that user can correct themself without refilling all the data.
- All database communication is facilitated by MySQL with the use of a secure and effective pipeline `$MySQLi->prepare->bind_parram->execute->get_result`
- MySQL also powers pagination, sorting, and filtering thanks to LIMIT, ORDER BY, and WHERE keywords
