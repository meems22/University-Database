<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("database.php");
include("functions.php");


$year = date('Y');
$semester = get_current_semester();

// get courses for this semester
$current_courses_q = "select c.course_name, c.course_id, c.credits, s.section_id, t.day, t.start_time, t.end_time from
                     course c, section s, time_slot t 
                     where c.course_id = s.course_id and s.semester = '$semester' and s.year = '$year' and s.time_slot_id = t.time_slot_id";
$current_courses_qres = mysqli_query($myconnection, $current_courses_q);
$current_courses = mysqli_fetch_all($current_courses_qres, MYSQLI_ASSOC);

// student
$student_id = get_student_info($myconnection, 'student_id');

// populate the take table
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    $section_id = $_POST['section_id'];

    // check if student meets prereq requirement
    $prereq_q = "select prereq_id from prereq where course_id = '$course_id'";
    $prereq_res = mysqli_query($myconnection, $prereq_q);
    while ($row = mysqli_fetch_assoc($prereq_res)) {
        $prereq_id = $row['prereq_id'];
    }
    $check_prereq = "select course_id from take where course_id = '$prereq_id'";
    $check_prereq_q = mysqli_query($myconnection, $check_prereq);

    //$check_capacity = "select capacity from classroom where classroom_id = $course_id";
    // $cap_res = mysqli_query($myconnection, $check_capacity);
    // $capacity = $row['capacity']; 

    if ($check_prereq_q) { // double check this logic
        $insert_q = "insert into take (student_id, course_id, section_id, semester, year, grade) values
        ('$student_id', '$course_id', '$section_id', '$semester', '$year', NULL)";
        mysqli_query($myconnection, $insert_q);
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Courses Offered</title>
</head>

<body>
    <h2>Courses Offered (Spring 2025)</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Section</th>
            <th>Days</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Credits</th>
            <th>Click to Register</th>
        </tr>
        <?php foreach ($current_courses as $course): ?>
            <tr align="center">
                <td><?php echo $course['course_id']; ?></td>
                <td><?php echo $course['course_name']; ?></td>
                <td><?php echo $course['section_id']; ?></td>
                <td><?php echo $course['day']; ?></td>
                <td><?php echo $course['start_time']; ?></td>
                <td><?php echo $course['end_time']; ?></td>
                <td><?php echo $course['credits']; ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                        <input type="hidden" name="course_name" value="<?php echo $course['course_name']; ?>">
                        <input type="hidden" name="credits" value="<?php echo $course['credits']; ?>">
                        <input type="hidden" name="section_id" value="<?php echo $course['section_id']; ?>">
                        <input type="hidden" name="day" value="<?php echo $course['day']; ?>">
                        <input type="hidden" name="start_time" value="<?php echo $course['start_time']; ?>">
                        <input type="hidden" name="end_time" value="<?php echo $course['end_time']; ?>">
                        <button type="submit">Register</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table><br>
    <a href="student_page.php">Back to Home Page</a>

</body>

</html>