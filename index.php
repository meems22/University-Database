<?php
session_start();

include("database.php");
include("functions.php");

$user_data = check_login($myconnection);
$student_data = get_student($myconnection);

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $dept_name = $_POST['dept_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $type = $_POST['user_type'];

    if(!empty($email) && !empty($password) && !is_numeric($email)) {
        // save information to database (account first): 
        $query = "insert into account (email, password, type) values ('$email', '$password', '$type')";
        mysqli_query($myconnection, $query);

        // save info into student
        $query2 = "insert into student (student_id, name, email, dept_name) values ('$id', '$name','$email', '$dept_name')";
        mysqli_query($myconnection, $query2);

        // after the user signs up they are redirected to the login page
        header("Location: login.php");
        die();
    } else { 
        echo "Enter Information!";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>My Website</title>
</head>
<body>
    <a href="logout.php">Logout</a>
    <h1>Hello, <?php echo $student_data['name']; ?></h1><br>
    <div>Edit Your Information</div>
        <input type="text" name="id" placeholder="Enter ID" required><br><br>
        <input type ="text" name ="name" placeholder="Name"><br><br>
        <input type ="text" name ="dept_name" placeholder="Department Name"><br><br>
        <input type ="text" name ="email" placeholder="Email"><br><br>
        <input type ="password" name ="password" placeholder="Password"><br><br>
        <input type="submit" value="Submit Changes"><br><br>
</body>
</html>