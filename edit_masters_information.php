<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("database.php");
include("functions.php");


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];
    $old_email = $_SESSION['email'];
    $student_id = get_student_info($myconnection, 'student_id');
    
    // if student requests to change email, then upate tables
    if (!empty($new_email) && !is_numeric($new_email)) {
        $query = "update account set email = '$new_email' where email = '$old_email'";
        mysqli_query($myconnection, $query);

        $query2 = "update student set email = '$new_email' where student_id = '$student_id'";
        mysqli_query($myconnection, $query2);

        $_SESSION['email'] = $new_email;
    }

    // if student requests to change password, then update the tables
    if (!empty($new_password)) {
        $email = $_SESSION['email'];
        $query = "update account set password = '$new_password' where email = '$email'";
        mysqli_query($myconnection, $query);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Information</title>
</head>
<body>
    <form method="post">
        <h2>Update Your Information</h2>
        <input type="text" name="email" placeholder="New Email"><br><br>
        <input type="password" name="password" placeholder="New Password"><br><br>
        <input type="submit" value="Update Info"><br><br>
    </form>

    <form action="masters_page.php" method="get">
    <input type="submit" value="Back to Home Page"> </form>  
    