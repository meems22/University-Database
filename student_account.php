<?php
// Start the session and include the database connection file
session_start();
include 'database.php';

// Check if the student is logged in (we assume the student's email is stored in the session)
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get the student's email from the session
$email = $_SESSION['email'];

// Query the database for the student's account details from the student and account tables
$sql = "SELECT s.*, a.password FROM student s JOIN account a ON s.email = a.email WHERE s.email = '$email'";
$result = mysqli_query($myconnection, $sql);
if (mysqli_num_rows($result) > 0) {
    // Fetch the student's information
    $student = mysqli_fetch_assoc($result);
} else {
    echo "No student record found.";
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>My Account</title>
</head>

<body>
    <a href="logout.php">Logout</a>
    <h1>Hello, <?php echo $student['name']; ?></h1>
    <h2>Your Information</h2>
    <!-- Display student information in a table -->
    <table border="1" cellpadding="10">
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Department</th>
            <th>Email</th>
            <th>Password</th>
            <th>Action</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($student['student_id']); ?></td>
            <td><?php echo htmlspecialchars($student['name']); ?></td>
            <td><?php echo htmlspecialchars($student['dept_name']); ?></td>
            <td><?php echo htmlspecialchars($student['email']); ?></td>
            <td><?php echo htmlspecialchars($student['password']); ?></td>
            <td>
                <!-- Edit button sends the student to the edit page -->
                <form action="student_update.php" method="GET">
                    <input type="hidden" name="student_id"
                        value="<?php echo htmlspecialchars($student['student_id']); ?>">
                    <input type="submit" value="Edit">
                </form>
            </td>
        </tr>
    </table>
</body>

</html>