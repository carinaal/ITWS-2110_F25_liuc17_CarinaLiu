# ITWS 2110 - Lab 6: PHP Calculator
**Name:** Carina Liu  
**Date:** November 3, 2025

## Project Overview
A PHP-based calculator that implements object-oriented programming principles to perform basic arithmetic operations. The calculator handles addition, subtraction, multiplication, and division with both client and server-side validation.

## Implementation 
1. **Initial Setup**
    - I started by creating a new folder named lab6 inside C:\xampp\htdocs and copied lab6start.php file so that Apache can serve it properly. I had to download XAMPP, start Apache, and confirm the page was loading through http://localhost/lab6/lab6.php

2. **Part 1: Operation Classes**
   - Using the provided abstract Operation class and the Addition class, I was able to implement my Subtraction, Multiplication, and Division classes similarly. I wrote each one by overriding the two required methods operate() and getEquation() to perform its specific calculation and return a formatted 
   equation.
   - In the division class I added a small check to make sure the second number wasn't zero. If it was, the code throws an exception so the user gets an error message.

3. **Part 2: Form Processing**
   - For the POST form handling at the bottom of the file, I used if statements with isset($_POST['add']), isset($_POST['subtract']), and so on to figure out which button was clicked. Based on the button, I instantiated the correct class (new Addition($op1, $op2) etc.), then called $op->getEquation() to display the result.I made sure to echo this output inside <pre id='result'> so it matched the lab instructions.

4. **Testing & Enhancements**
    - I tested each operation individually to make sure the right equations and answers appeared
    - I also tested invalid inputs, empty values, and division by zero to confirm my error handling worked correctly
   - Added JavaScript input validation
   - Implemented real-time numeric checking
   - Added user feedback in result area
   - Fixed input field IDs for better accessibility

