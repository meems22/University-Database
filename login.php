<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("database.php");
include("functions.php");

// check if user already has an account
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password) && !is_numeric($email)) {
        // read from database: 
        $query = "select * from account where email = '$email'";
        $result = mysqli_query($myconnection, $query);

        // if that email exists then, check the password
        if ($result) {
            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);
                if ($user_data['password'] === $password) {
                    $_SESSION['email'] = $user_data['email'];
                    $email = $user_data['email'];
                    $type = $user_data['type'];

                    if ($type == 'student') {
                        header("Location: student_page.php");
                        exit();
                    } else if ($type == 'admin') {
                        header("Location: admin_page.php");
                        exit;
                    } else if ($type = 'instructor') {
                        header("Location: instructor_page.php");
                        exit;
                    }

                }
            }
        }
        echo "Incorrect Email or Password, Try Again!";
    } else {
        echo "Enter Information!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sign In</title>
</head>

<body>
    <form method="post">
        <br>
        <div><strong>Login</div>
        <input type="text" name="email" placeholder="Enter Email"><br><br>
        <input type="password" name="password" placeholder="Enter Password"><br><br>

        <input type="submit" value="Login"><br><br>
        <a href="create_account.php">Create Student Account</a><br><br>
    </form>
</body>

</html>