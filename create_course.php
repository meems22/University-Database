<?php
session_start();
include("database.php");
include("functions.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $credits = intval($_POST['credits']);

    if(!empty($course_id) && !empty($course_name) && !empty($credits)) {
        $query = "insert into course (course_id, course_name, credits) values ('$course_id', '$course_name', '$credits')";
        mysqli_query($myconnection, $query);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Course</title>
</head>
    <body>
        <h1>Create Course</h1>
        <form action="create_course.php" method="post">
        <input type="text" name="course_id" placeholder="Enter Course ID" required><br><br>

        <input type="text" name="course_name" placeholder="Enter Course Name"><br><br>

        <input type="number" name="credits" placeholder="Enter Credit" min="0" step="1"><br><br>

        <input type="submit" value="Create Course">
    </form>

    <br>

    <form action="admin_page.php" method="get">
        <input type="submit" value="Back to Home Page">
    </form>

    </body>
</html>