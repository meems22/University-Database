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

        $query = "select name from student where email = '$email'";
        $result = mysqli_query($myconnection, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
    }
    return null; // Return null if no student found
}

function get_student_info($myconnection, $info) {
    if (isset($_SESSION['email'])) {
        $email = mysqli_real_escape_string($myconnection, $_SESSION['email']);
        $query = "select $info from student where email = '$email'";
        $result = mysqli_query($myconnection, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row[$info];
        }
    }
    return "N/A"; 
}

function get_undergrad_info($myconnection, $id, $info) {
        $query = "select $info from undergraduate where student_id = '$id'";
        $result = mysqli_query($myconnection, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row[$info];
        }
        return "N/A";
    }

function get_masters_info($myconnection, $id, $info) {
    $query = "select $info from master where student_id = '$id'";
    $result = mysqli_query($myconnection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row[$info];
    }
    return "N/A";
}

function get_phd_info($myconnection, $id, $info) {
    $query = "select $info from PhD where student_id = '$id'";
    $result = mysqli_query($myconnection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row[$info];
    }
    return "N/A";
}
?>