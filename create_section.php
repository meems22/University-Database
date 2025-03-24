<?php
// Include the database configuration file (assumes config.php sets up $conn)
include 'database.php';
include 'functions.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form input values
    $course_id = $_POST['course_id'];
    $section_id = $_POST['section_id'];
    $time_slot_id = $_POST['time_slot_id'];
    $classroom_id = $_POST['classroom_id'];
    $instructor_id = $_POST['instructor_id'];
    $semester = $_POST['semester'];
    $year = $_POST['year'];

    // Initialize an array to store error messages
    $errors = array();

    // Constraint 1: Check if the selected time slot already has two sections scheduled
    $query1 = "SELECT COUNT(*) as count FROM section 
               WHERE time_slot_id = '$time_slot_id' 
                 AND semester = '$semester' 
                 AND year = $year";
    $result1 = mysqli_query($myconnection, $query1);
    $row1 = mysqli_fetch_assoc($result1);
    if ($row1['count'] >= 2) {
        $errors[] = "Time slot $time_slot is already full (2 sections scheduled).";
    }

    // Constraint 2: Check how many sections the selected instructor is already assigned to
    $query2 = "SELECT COUNT(*) as count FROM section 
               WHERE instructor_id = '$instructor_id' 
                 AND semester = '$semester' 
                 AND year = $year";
    $result2 = mysqli_query($myconnection, $query2);
    $row2 = mysqli_fetch_assoc($result2);
    if ($row2['count'] >= 2) {
        $errors[] = "Instructor $instructor_id is already assigned to 2 sections.";
    } elseif ($row2['count'] == 1) {
        // Constraint 3: If instructor already teaches one section,
        // ensure the new time slot is consecutive to the existing one.
        $query3 = "SELECT time_slot_id FROM section 
                   WHERE instructor_id = '$instructor_id' 
                     AND semester = '$semester' 
                     AND year = $year";
        $result3 = mysqli_query($myconnection, $query3);
        $row3 = mysqli_fetch_assoc($result3);
        $existing_time_slot = $row3['time_slot_id'];
        // Check if the new time slot is exactly 1 time unit away from the existing slot.
        if (!is_consecutive($myconnection, $time_slot_id, $existing_time_slot)) {
            $errors[] = "Instructor $instructor_id already has a section in time slot $existing_time_slot. When assigned two sections, they must be in consecutive time slots.";
        }
    }

    // If there are any errors, display them.
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    } else {
        // No errors: insert the new course section into the 'section' table.
        // (Assuming the table 'section' exists with columns:
        // course_id, section_id, time_slot, instructor_id, semester, year)
        $insert_query = "INSERT INTO section (course_id, section_id, semester, year, instructor_id, classroom_id, time_slot_id)
                         VALUES ('$course_id', '$section_id', '$semester', '$year', '$instructor_id', '$classroom_id', '$time_slot_id')";
        if (mysqli_query($myconnection, $insert_query)) {
            echo "<p style='color:green;'>New course section created successfully.</p>";
            header("Location: admin_account.html");
            exit();
        } else {
            echo "<p style='color:red;'>Error inserting new section: " . mysqli_error($conn) . "</p>";
        }
    }
}
?>

<!-- HTML form for admin to create a new course section -->
<!DOCTYPE html>
<html>

<head>
    <title>Create New Course Section</title>
</head>

<body>
    <h2>Create New Course Section</h2>
    <form method="POST">
        <input type="text" name="course_id" placeholder="Course ID" required><br><br>
        <input type="text" name="section_id" placeholder="Section ID" required><br><br>
        <input type="text" name="semester" placeholder="Semester" required><br><br>
        <input type="number" name="year" placeholder="Year" required><br><br>
        <input type="text" name="instructor_id" placeholder="Instructor ID" required><br><br>
        <input type="text" name="classroom_id" placeholder="Classroom ID" required><br><br>
        <input type="text" name="time_slot_id" placeholder="Timeslot ID" required><br><br>
        <input type="submit" value="Create Section">
    </form>
</body>

</html>