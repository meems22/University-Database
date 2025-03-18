<?php
session_start();

include("database.php");
include("functions.php");
$user_data = check_login($myconnection);
$student_data = get_student($myconnection);
$student_id = get_student_info($myconnection, 'student_id');
$student_email = get_student_info($myconnection, 'email');
$student_dept_name = get_student_info($myconnection, 'dept_name');
$student_credits = get_masters_info($myconnection, $student_id, 'total_credits');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Master's Home Page</title>
    <body>
        
        <h1>Hello, <?php echo $student_data['name']; ?></h1><br>
        <h2>Your Information:</h2>
            <li><strong>Name:</strong> <?php echo $student_data['name']; ?></li>
            <li><strong>Student ID:</strong> <?php echo htmlspecialchars($student_id); ?></li>
            <li><strong>Student Email:</strong> <?php echo htmlspecialchars($student_email); ?></li>
            <li><strong>Department:</strong> <?php echo htmlspecialchars($student_dept_name); ?></li>
            <li><strong>Total Credits:</strong> <?php echo htmlspecialchars($student_credits); ?></li>
            
            <form action="edit_masters_information.php" method="get">
            <input type="submit" value="Edit Information"> </form>  
            <a href="logout.php">Logout</a>
    </body>
</html>