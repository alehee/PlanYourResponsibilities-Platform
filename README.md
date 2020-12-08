# PlanYourResponsibilities-Platform (PlanDeca)
![It's a front pic!](https://github.com/alehee/PlanYourResponsibilities-Platform/blob/master/github_resources/front_github.png?raw=true)

## Description
PlanYourResponsibilities-Platform is a web platform for task management, team responsibilities control and work plan capabilities. If you're manager you can schedule your employees work day. If you're an employee you have your own todo list with color information, daily e-mail messages with your 'today' list and chat to communicate with other task collaborators.

Including:
* Normal / HR tasks split
* Private fast-tasks list
* Daily tasks e-mail and 'new task' e-mail
* Tasks advisory
* Tasks collaborators chat
* ...and many more!

**Polish version only!**

![It's a pic!](https://github.com/alehee/PlanYourResponsibilities-Platform/blob/master/github_resources/addtask.png "Add task")
![It's a pic!](https://github.com/alehee/PlanYourResponsibilities-Platform/blob/master/github_resources/task.png "Task")
![It's a pic!](https://github.com/alehee/PlanYourResponsibilities-Platform/blob/master/github_resources/list.png "Task list")

## Used technology
Technology I used in this project:
* HTML/CSS
* PHP
* JS + JQuery
* Bootstrap
* MySQL

## How to use
Short description of using the platform for your own purpose. First of all you need **MySQL and PHP servers**, like some sort of web hosting or, for LAN purpose, XAMPP.

When you have your environment here's what you need to do next:

  1. Download latest master branch files and unzip it
  2. Upload/copy the unziped files to your hosting environment (or XAMPP)
  3. Import the *db_example.sql* to your MySQL system, check how tables are build and clear it
  4. Rename *connection_example.php* to ***connection.php***
  5. Change the *connection.php* data to:
  ```php
  <?php
  $host = "localhost"; // your MySQL host url or ip
  $user_db = "user_db"; // MySQL username, in XAMPP default is 'root'
  $password_db = "password"; // MySQL password, in XAMPP default is blank
  $db_name = "user_db"; // database name you choose while importing the db_example.php
  ?>
  ```
  6. Upload changes to host or run the XAMPP server, and you're good to go!
  
To run daily mail, database autoclear, and other cool stuff you need to run *\TFRS2P6VB6\save_server.php*, I used Windows Task Scheduler and the .bat file inside the folder.
  
## Thank you!
Thank you for peeking at my project!

If you're interested check out my other stuff [here](https://github.com/alehee)
