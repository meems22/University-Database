<?php
// Start session and include the database connection
session_start();
include 'database.php';

// Check if the student is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// If the form is submitted, update the student's information
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the student_id, new name and new password from the form
    $email = $_SESSION['email'];
    $new_name = $_POST['name'];
    $new_password = $_POST['password'];  // (In practice, consider hashing the password)

    // Update the student's name in the student table
    $sql1 = "UPDATE student SET name = '$new_name' WHERE email = '$email'";
    // Update the password in the account table
    $sql2 = "UPDATE account SET password = '$new_password' WHERE email = '$email'";

    // Execute the update queries
    $update1 = mysqli_query($myconnection, $sql1);
    $update2 = mysqli_query($myconnection, $sql2);

    if ($update1 && $update2) {
        echo "Account updated successfully.";
        // Redirect back to the landing page after successful update
        header("Location: student_account.php");
        exit();
    } else {
        echo "Error updating account: " . mysqli_error($conn);
    }
} else {
    // If the form has not been submitted, show the edit form
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        // Retrieve the student's current information (joining student and account to get password)
        $sql = "SELECT s.*, a.password FROM student s JOIN account a ON s.email = a.email 
                WHERE s.email = '$email'";
        $result = mysqli_query($myconnection, $sql);
        if (mysqli_num_rows($result) > 0) {
            $student = mysqli_fetch_assoc($result);
        } else {
            echo "No student record found.";
            exit();
        }
    } else {
        echo "Session Error";
        exit();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Student Account</title>
</head>

<body>
    <h2>Edit Your Account Information</h2>
    <!-- Form for updating the student's name and password -->
    <form action="student_update.php" method="POST">
        <!-- Hidden field to pass the student_id -->
        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>"><br>
        <label for="password">Password:</label>
        <input type="password" name="password" value="<?php echo htmlspecialchars($student['password']); ?>"><br>
        <input type="submit" value="Update">
    </form>
</body>

</html>