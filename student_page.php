<?php
session_start();

include("database.php");
include("functions.php");

$user_data = check_login($myconnection);
$student_data = get_student($myconnection);
$student_id = get_student_info($myconnection, 'student_id');
$student_email = get_student_info($myconnection, 'email');
$student_dept_name = get_student_info($myconnection, 'dept_name');
$student_type = get_student_type($myconnection);

if ($student_type == 'undergraduate') {
    $student_class_standing = get_undergrad_info($myconnection, $student_id, 'class_standing');
    $student_credits = get_undergrad_info($myconnection, $student_id, 'total_credits');
} else if ($student_type == 'master') {
    $student_credits = get_masters_info($myconnection, $student_id, 'total_credits');
} else {
    $student_qualifier = get_phd_info($myconnection, $student_id, 'qualifier');
    $student_proposal_defense_date = get_phd_info($myconnection, $student_id, 'proposal_defense_date');
    $student_dissertation_defense_date = get_phd_info($myconnection, $student_id, 'dissertation_defense_date');
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Undergraduate Home Page</title>

<body>

    <h1>Hello, <?php echo $student_data['name']; ?></h1><br>
    <h2>Your Information:</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>Name</th>
            <th>Student ID</th>
            <th>Email</th>
            <th>Department</th>
            <th>Student Type</th>
            <?php if ($student_type != "PhD"): ?>
                <?php if ($student_type == "undergraduate"): ?>
                    <th>Class Standing</th>
                <?php endif; ?>
                <th>Total Credits</th>
            <?php else: ?>
                <th>Qualifier</th>
                <th>Proposal Defense Date</th>
                <th>Dissertation Defense Date</th>
            <?php endif; ?>
            <th>Edit Info</th>
        </tr>
        <tr align="center">
            <td><?php echo $student_data['name']; ?></td>
            <td><?php echo htmlspecialchars($student_id); ?></td>
            <td><?php echo htmlspecialchars($student_email); ?></td>
            <td><?php echo htmlspecialchars($student_dept_name); ?></td>
            <td><?php echo htmlspecialchars($student_type); ?></td>
            <?php if ($student_type != 'PhD'): ?>
                <?php if ($student_type == 'undergraduate'): ?>
                    <td><?php echo htmlspecialchars($student_class_standing); ?></td>
                <?php endif; ?>
                <td><?php echo htmlspecialchars($student_credits); ?></td>
            <?php else: ?>
                <td><?php echo htmlspecialchars($student_qualifier); ?></td>
                <td><?php echo htmlspecialchars($student_proposal_defense_date); ?></td>
                <td><?php echo htmlspecialchars($student_dissertation_defense_date); ?></td>
            <?php endif; ?>
            <td>
                <form action="edit_info.php" method="GET">
                    <input type="submit" value="Edit">
                </form>
            </td>
        </tr>
    </table><br>

    <form action="student_course_list.php" method="get">
        <input type="submit" value="View My Courses">
    </form><br>

    <form action="browse_courses.php" method="get">
        <input type="submit" value="Browse Course Offerings">
    </form><br>

    <a href="logout.php">Logout</a>
</body>

</html>