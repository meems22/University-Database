<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    include("database.php");
    include("functions.php");

    // check if user already has an account
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
    <title>Login</title>
</head>
<body>
    <form method="post">
        
        <div>Create Account</div>
        <input type="text" name="id" placeholder="Enter ID" required><br><br>
        <input type ="text" name ="name" placeholder="Name"><br><br>
        <input type ="text" name ="dept_name" placeholder="Department Name"><br><br>
        <input type ="text" name ="email" placeholder="Email"><br><br>
        <input type ="password" name ="password" placeholder="Password"><br><br>


        <label for="user_type">Select User Type:</label>
        <select name="user_type" id="user_type">
            <option value="student">Student</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <input type="submit" value="Create Account"><br><br>
        <a href="login.php">Click to Login</a><br><br>
    </form>
</body>
</html>
