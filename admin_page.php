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
    <h1>Hello, Admin</h1>
    <form action="edit_info.php" method="get">
        <input type="submit" value="Change Password">
    </form><br>
    <form action="create_course.php" method="get">
        <input type="submit" value="Create New Course">
    </form><br>

    <form action="create_course_section.php" method="get">
        <input type="submit" value="Create New Course Section">
    </form><br>

    <form action="assign_ta.php" method="get">
        <input type="submit" value="Assign TA to Course">
    </form><br>

    <form action="advisor_appointment.php" method="get">
        <input type="submit" value="Appoint PhD Advisor/Advisee">
    </form><br><br>

    <a href="logout.php">Logout</a>
</body>

</html>