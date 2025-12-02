# ITWS 2110 - Lab 8: SQL & PHP
**Name:** Carina Liu  
**Date:** December 1, 2025

## Overview
In this lab, I created a new MySQL database in phpMyAdmin, defined tables, modified table structures, inserted sample data, and ran SQL queries.

## Observations
    - Initially, I was having trouble connecting my folder to Apache and MySQL could not start. Eventually I found out that there was another MySQL server already running on port 3306. 
    - Foreign key constraints require related rows to exist first. For example, grades referencing a CRN or RIN cannot be inserted until the courses and students tables contain matching values
    - The SQL tab is significantly faster than manually entering the data using the Insert GUI.
    - The ALTER TABLE command must be written one field per line when adding multiple fields to avoid syntax errors to improve readability

## Assumptions
The assignment did not specify data types for street, city, state, or zip, so I selected:
    - street -> VARCHAR(255)
    - city -> VARCHAR(100)
    - state -> VARCHAR(50)
    - zip -> VARCHAR(10)
For section and year, I used 
    - section -> VARCHAR(10)
    - year -> INT(4)

## Comments
Overall, this lab helped me understand how SQL, table design, and phpMyAdmin work together in a real workflow. Creating the tables manually made me more aware of how important it is to choose appropriate data types and define clear primary and foreign key relationships. Writing the SQL myself also helped me get more comfortable with syntax when creating tables, adding new columns, and joining tables in queries. 
