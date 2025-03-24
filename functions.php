<?php
// this funtion checks if person is already logged in
function check_login($myconnection)
{
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $query = "select * from account where email = '$email' limit 1";

        $result = mysqli_query($myconnection, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }

    // go back to login page
    header("Location: login.php");
    die;
}

function get_instructor($myconnection)
{
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $email = mysqli_real_escape_string($myconnection, $email); // Prevent SQL injection

        $query = "select * from instructor where email = '$email'";
        $result = mysqli_query($myconnection, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
    }
    return null; // Return null if no instructor found
}

function get_student($myconnection)
{
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

function get_student_info($myconnection, $info)
{
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

function get_undergrad_info($myconnection, $id, $info)
{
    $query = "select $info from undergraduate where student_id = '$id'";
    $result = mysqli_query($myconnection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row[$info];
    }
    return "N/A";
}

function get_masters_info($myconnection, $id, $info)
{
    $query = "select $info from master where student_id = '$id'";
    $result = mysqli_query($myconnection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row[$info];
    }
    return "N/A";
}

function get_phd_info($myconnection, $id, $info)
{
    $query = "select $info from PhD where student_id = '$id'";
    $result = mysqli_query($myconnection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row[$info];
    }
    return "N/A";
}

function get_student_type($myconnection)
{
    $student_id = get_student_info($myconnection, "student_id");
    if ($student_id == "N/A")
        return "N/A";

    if (get_undergrad_info($myconnection, $student_id, "student_id") != "N/A")
        return "undergraduate";
    else if (get_masters_info($myconnection, $student_id, "total_credits") != "N/A")
        return "master";
    else if (get_phd_info($myconnection, $student_id, "student_id") != "N/A")
        return "phd";
    else
        return "N/A";
}

function get_current_semester()
{
    $month = date('n');

    if ($month >= 3 && $month <= 5) {
        return "Spring";
    } elseif ($month >= 6 && $month <= 8) {
        return "Summer";
    } elseif ($month >= 9 && $month <= 11) {
        return "Fall";
    } else {
        return "Winter";
    }
}
?>