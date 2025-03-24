<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("database.php");
include("functions.php");

// add a place to unassign TA??

$year = date('Y');
$semester = get_current_semester();

$eligible_courses_q = "select s.section_id, s.course_id, s.semester, s.year from section s, take t 
                        where s.section_id = t.section_id and s.course_id = t.course_id and s.year = '$year' and s.semester = '$semester' 
                        group by s.course_id
                        having count(student_id) >= 10";

$eligible_courses_qres = mysqli_query($myconnection, $eligible_courses_q);
$eligible_courses = mysqli_fetch_all($eligible_courses_qres, MYSQLI_ASSOC);

$eligible_students_q = "select s.name, s.student_id from student s, PhD p
                        where s.student_id = p.student_id
                        and (s.student_id NOT IN (
                            select t.student_id
                            from TA t
                            where t.year = '$year' AND t.semester = '$semester'))";
                
$eligible_students_qres = mysqli_query($myconnection, $eligible_students_q);
$eligible_students = mysqli_fetch_all($eligible_students_qres, MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];

    if (isset($_POST['course_selection'])) {
        list($course_id, $section_id, $semester, $year) = explode('|', $_POST['course_selection']);

        if (!empty($course_id) && !empty($section_id) && !empty($student_id)) {
            $insertion_q = "insert into TA values ('$student_id', '$course_id', '$section_id', '$semester', '$year')";
            mysqli_query($myconnection, $insertion_q);
            header("Location: admin_page.php");
            exit();
        } else {
            echo "Please select both a course and a student.";
        }
    }
}


?>


<!DOCTYPE html>
<html>
<head>
    <title>Appoint TA to a Course</title>
</head>
<body>

<h2>Appoint TA to a Course</h2>
<form method="post">

    <h3>Eligible Courses:</h3>
    <?php if (!empty($eligible_courses)): ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>Select</th>
                <th>Course ID</th>
                <th>Section ID</th>
                <th>Semester</th>
                <th>Year</th>
            </tr>
            <?php foreach ($eligible_courses as $course): ?>
                <tr align="center">
                    <td>
                        <input type="radio" name="course_selection"
                               value="<?php echo $course['course_id'] . '|' . $course['section_id'] . '|' . $course['semester'] . '|' . $course['year']; ?>">
                    </td>
                    <td><?php echo $course['course_id']; ?></td>
                    <td><?php echo $course['section_id']; ?></td>
                    <td><?php echo $course['semester']; ?></td>
                    <td><?php echo $course['year']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table><br>
    <?php else: ?>
        <p>No eligible courses available.</p>
    <?php endif; ?>

    <h3>Available PhD Students:</h3>
    <?php if (!empty($eligible_students)): ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>Select</th>
                <th>Name</th>
                <th>Student ID</th>
            </tr>
            <?php foreach ($eligible_students as $student): ?>
                <tr align="center">
                    <td>
                        <input type="radio" name="student_id" value="<?php echo $student['student_id']; ?>">
                    </td>
                    <td><?php echo $student['name']; ?></td>
                    <td><?php echo $student['student_id']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table><br>
    <?php else: ?>
        <p>No eligible PhD students available.</p>
    <?php endif; ?>

    <?php if (!empty($eligible_courses) && !empty($eligible_students)): ?>
        <input type="submit" value="Assign TA">
    <?php endif; ?>
</form>

<br><a href="admin_page.php">Back to Home Page</a>

</body>
</html>