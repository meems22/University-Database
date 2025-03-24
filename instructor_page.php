<?php
session_start();

include("database.php");
include("functions.php");

$user_data = check_login($myconnection);
$instructor = get_instructor($myconnection);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Instructor Home Page</title>

<body>

    <h1>Hello, <?php echo $instructor['instructor_name']; ?></h1><br>
    <h2>Your Information:</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>Name</th>
            <th>Instructor ID</th>
            <th>Email</th>
            <th>Department</th>
            <th>Title</th>
            <th>Edit Info</th>
        </tr>
        <tr align="center">
            <td><?php echo $instructor['instructor_name']; ?></td>
            <td><?php echo $instructor['instructor_id']; ?></td>
            <td><?php echo $instructor['email']; ?></td>
            <td><?php echo $instructor['dept_name']; ?></td>
            <td><?php echo $instructor['title']; ?></td>
            <td>
                <form action="edit_info.php" method="GET">
                    <input type="submit" value="Edit">
                </form>
            </td>
        </tr>
    </table><br>

    <form action="instructor_course_list.php" method="get">
        <input type="submit" value="View Course History">
    </form><br>

    <form action="advisor_appointment.php" method="get">
        <input type="submit" value="Appoint Advisee">
    </form><br>

    <a href="logout.php">Logout</a>
</body>

</html>