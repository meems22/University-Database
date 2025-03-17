<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include("database.php");
    include("functions.php");

    // check if user already has an account
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(!empty($email) && !empty($password) && !is_numeric($email)) {
            // read from database: 
            $query = "select * from account where email = '$email'";
            $result = mysqli_query($myconnection, $query);
            
            // if that email exists then, check the password
            if($result) {
                if($result && mysqli_num_rows($result) > 0) {
                    $user_data = mysqli_fetch_assoc($result);
                    if($user_data['password'] === $password) {
                        $_SESSION['email'] = $user_data['email'];
                        $email = $user_data['email'];

                        // get student id from student signing in
                        $find_student_id = "select student_id from student where email = '$email'";
                        $result_student = mysqli_query($myconnection, $find_student_id);
                        $student_data = mysqli_fetch_assoc($result_student);
                        $student_id = $student_data['student_id'];

                        // check if student is undergrad, masters, or phd and redirect to correct page
                        $check_undergrad = "select student_id from undergraduate where student_id = '$student_id'";
                        $result_check_undergrad = mysqli_query($myconnection, $check_undergrad);
                        if(mysqli_num_rows($result_check_undergrad) > 0) {
                            header("Location: undergraduate_page.php");
                            exit;
                        }

                        $check_masters = "select student_id from master where student_id = '$student_id'";
                        $result_check_masters = mysqli_query($myconnection, $check_masters);
                        if(mysqli_num_rows($result_check_masters) > 0) {
                            header("Location: masters_page.php");
                            exit;
                        }
                        
                        $check_phd = "select student_id from PhD where student_id = '$student_id'";
                        $result_check_phd = mysqli_query($myconnection, $check_phd);
                        if(mysqli_num_rows($result_check_phd) > 0) {
                            header("Location: phd_page.php");
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
    <title>Create Account</title>
</head>
<body>
    <form method="post">
        <div>Login</div>
        <input type ="text" name ="email"><br><br>
        <input type ="password" name ="password"><br><br>

        <input type="submit" value="Login"><br><br>
        <a href="create_account.php">Create Student Account</a><br><br>
    </form>
</body>
</html>
