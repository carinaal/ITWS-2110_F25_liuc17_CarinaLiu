# ITWS 2110 - Quiz 2
**Name:** Carina Liu  
**Date:** December 2, 2025

# 3.1 - Design Decisions
When completing this quiz, I made several specific design choices to align with best practices taught in the course. 
    - Dedicated a config.php file to centralize the database connection details
    - All database interactions use PDO with prepared statements to prevent SQL injection by separating SQL logic from user input
    - For session management, I implemented PHP’s built-in session_start() and stored only the userId in the session to allow persistence across HTTP requests without exposing sensitive user data
    - The passwords are all stored by generating a random salt with random_bytes() and hashing using hash("sha256", $salt . $password) to secure the passwords
    - The databse design uses AUTO_INCREMENT primary keys for userId and projectId
    - I structured the application into modular pages (login.php, register.php, index.php, project.php)
    - I laid out all the projects in a card layout to make the information easily digestible

# 3.2 - Handling No-Database Scenario
If the site were accessed before any database existed, the system would detect this state during initialization. The MySQL/PHP lecture notes describe using SHOW TABLES or attempting a simple query to determine whether required tables exist before performing operations. Following this approach, the application would attempt to query the users table. If the query fails due to missing tables, the system would transition into an installation mode.
During installation, the site would load and execute the SQL schema stored in setupDB.txt, which contains CREATE TABLE statements for all required tables. This aligns with the concept of bootstrapping a system by executing a schema file to create necessary structures.
After the tables are created, the application would redirect the user to the registration page, enabling the first account to be created. This approach provides a smooth, automated first-run experience without requiring manual database setup.

# 3.3 - Preventing Duplicate Project Entries
To prevent duplicate project names, I implemented server-side validation using prepared statements. Before inserting a new project, the application executes:
SELECT COUNT(*) FROM projects WHERE name = ?
If the query returns a count greater than zero, the project is rejected and an error message is displayed. Additionally, the database could also enforce uniqueness by adding a UNIQUE constraint to the name field which reinforces defense in depth.

# 3.4 - Voting Functionality
3.4.1
To support a voting feature, I would add a new table named votes. This table would store each vote submitted by users. It would include: 
- voteId (INT, AUTO_INCREMENT, PRIMARY KEY)
- voterId (INT, FOREIGN KEY referencing users.userId)
- projectId (INT, FOREIGN KEY referencing projects.projectId)
- createdAt (TIMESTAMP, default CURRENT_TIMESTAMP)
This is a many-to-one relationship

3.4.2
To make sure each user can only vote once per project, I would include:
UNIQUE (voterId, projectId)
This prevents duplicate voting and keeps the data consistent. The createdAt timestamp enables tracking when votes occur

3.4.3
To prevent a user from voting for a project they belong to, I would perform a authorization check before inserting a vote. Access should be restricted based on user roles, permissions, or relationships. Before recording a vote, the application would execute:
SELECT COUNT(*)
FROM projectmembership
WHERE projectId = ? AND memberId = ?
If this returns a number greater than zero, the user is a member of that project and is not authorized to vote for it, and the system would block the vote and display an error message.
