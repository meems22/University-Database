<?php
session_start();

include("database.php");
include("functions.php");
$user_data = check_login($myconnection);
$student_data = get_student($myconnection);
$student_id = get_student_info($myconnection, 'student_id');
$student_email = get_student_info($myconnection, 'email');
$student_dept_name = get_student_info($myconnection, 'dept_name');
$qualifier = get_phd_info($myconnection, $student_id, 'qualifier');
$proposal_defence_date = get_phd_info($myconnection, $student_id, 'proposal_defence_date');
$dissertation_defence_date = get_phd_info($myconnection, $student_id, 'dissertation_defence_date');
?>

<!DOCTYPE html>
<html>
<head>
    <title>PhD Home Page</title>
    <body>
        
        <h1>Hello, <?php echo $student_data['name']; ?></h1><br>
        <h2>Your Information:</h2>
            <li><strong>Name:</strong> <?php echo $student_data['name']; ?></li>
            <li><strong>Student ID:</strong> <?php echo htmlspecialchars($student_id); ?></li>
            <li><strong>Student Email:</strong> <?php echo htmlspecialchars($student_email); ?></li>
            <li><strong>Department:</strong> <?php echo htmlspecialchars($student_dept_name); ?></li>
            <li><strong>Qualifier:</strong> <?php echo htmlspecialchars($qualifier); ?></li>
            <li><strong>Proposal Defense Date:</strong> <?php echo htmlspecialchars($proposal_defence_date); ?></li>
            <li><strong>Dissertaion Defense Date:</strong> <?php echo htmlspecialchars($dissertation_defence_date); ?></li>

    
            <form action="edit_phd_information.php" method="get">
            <input type="submit" value="Edit Information"> </form>  
            <a href="logout.php">Logout</a>
    </body>
</html>