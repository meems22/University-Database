<?php
    session_start();
    
    include("database.php");
    include("functions.php");

    // check if user already has an account
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $type = $_POST['user_type'];

        if(!empty($email) && !empty($password) && !is_numeric($email)) {
            // read from database: 
            $query = "select * from account where email = '$email' limit 1";
            $result = mysqli_query($myconnection, $query);
            
            // if that email exists then, check the password
            if($result) {
                if($result && mysqli_num_rows($result) > 0) {
                    $user_data = mysqli_fetch_assoc($result);
                    if($user_data['password'] === $password) {
                        $_SESSION['email'] = $user_data['email'];
                        
                        // after the user signs up they are redirected to the index page
                        header("Location: index.php");
                        die;
                        
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
        <a href="create_account.php">Create Account</a><br><br>
    </form>
</body>
</html>
