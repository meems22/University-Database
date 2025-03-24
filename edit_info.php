<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("database.php");
include("functions.php");

$user_data = check_login($myconnection);
$header_string = "";

if ($user_data['type'] == "admin") {
    $header_string = "admin_page.php";
} else if ($user_data['type'] == "instructor") {
    $header_string = "instructor_page.php";
} else {
    $header_string = "student_page.php";
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $new_name = $_POST['name'];
    $new_password = $_POST['password'];
    $email = $_SESSION['email'];

    if (!empty($new_name)) {
        if ($user_data['type'] == "student") {
            $query = "update student set name = '$new_name' where email = '$email'";
            mysqli_query($myconnection, $query);
        } else if ($user_data["type"] == "instructor") {
            $query = "update instructor set instructor_name = '$new_name' where email = '$email'";
            mysqli_query($myconnection, $query);
        }
    }

    if (!empty($new_password)) {
        $query = "update account set password = '$new_password' where email = '$email'";
        mysqli_query($myconnection, $query);
    }

    header("Location: $header_string");
    exit();
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
        <?php if ($user_data['type'] != "admin"): ?>
            <input type="text" name="name" placeholder="New Name"><br><br>
        <?php endif; ?>
        <input type="password" name="password" placeholder="New Password"><br><br>
        <input type="submit" value="Update Info"><br><br>
    </form>

    <?php echo "<a href='$header_string'>Back to Home Page</a>"; ?>