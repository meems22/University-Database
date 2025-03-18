<?php
session_start();
include("database.php");
include("functions.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Home Page</title>
    <body>
        <h1>Admin page</h1>
            <form action="create_course.php" method="get">
            <input type="submit" value="Create New Course"> </form><br>

            <form action="create_course_section.php" method="get">
            <input type="submit" value="Create New Course Section"> </form><br><br>

            <a href="logout.php">Logout</a>
    </body>
</html>