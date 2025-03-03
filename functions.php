<?php
// this funtion checks if person is already logged in
function check_login($myconnection) {
   if(isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = "select * from account where email = '$email' limit 1";

    $result = mysqli_query($myconnection, $query);
    if($result && mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);
        return $user_data;
    }
   }

   // go back to login page
   header("Location: login.php");
   die;
}

function get_student($myconnection) {
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $email = mysqli_real_escape_string($myconnection, $email); // Prevent SQL injection

        $query = "SELECT name FROM student WHERE email = '$email'";
        $result = mysqli_query($myconnection, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
    }
    return null; // Return null if no student found
}