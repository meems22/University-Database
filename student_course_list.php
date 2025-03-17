<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("database.php");
include("functions.php");

// get student id
$student_id = get_student_info($myconnection, 'student_id');

// current semester and year
$current_year = 2025;
$current_semester = 'Spring';

// get current classes
$current_courses_q = "select t.course_id, c.course_name, c.credits, t.grade, t.year, t.semester from take t, course c where c.course_id = t.course_id and 
                    t.student_id = '$student_id' and t.semester = '$current_semester' and year = '$current_year'";
$current_courses_qres = mysqli_query($myconnection, $current_courses_q);
$current_courses = mysqli_fetch_all($current_courses_qres, MYSQLI_ASSOC);

// get past courses taken
$past_courses_q = "select t.course_id, c.course_name, c.credits, t.grade, t.year, t.semester from take t, course c where c.course_id = t.course_id and
                    t.student_id = '$student_id' and (t.year  < '$current_year' or (t.year = '$current_year' and t.semester != '$current_semester'))";
$past_courses_qres = mysqli_query($myconnection, $past_courses_q);
$past_courses = mysqli_fetch_all($past_courses_qres, MYSQLI_ASSOC);


$total_credits = 0;
$total_grade_points = 0;
$gpa = 0;

$grade_points = [
    'A' => 4.0, 'A-' => 3.7, 'B+' => 3.3, 'B' => 3.0, 'B-' => 2.7, 
    'C+' => 2.3, 'C' => 2.0, 'C-' => 1.7, 'D+' => 1.3, 'D' => 1.0, 'F' => 0.0
];

foreach ($past_courses as $course) { 
    $total_credits += $course['credits'];
    if (isset($grade_points[$course['grade']])) {
        $total_grade_points += $grade_points[$course['grade']] * $course['credits'];
    }
}

// calculate GPA only for past courses
if ($total_credits > 0) {
    $gpa = round($total_grade_points / $total_credits, 2);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Information</title>
</head>
<body>
    <h2>Your Course List:</h2>

    <h3>Currently Enrolled (Spring 2025)</h3>
    <?php if (empty($current_courses)): ?>
        <p>No current courses.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Credits</th>
            </tr>
            <?php foreach ($current_courses as $course): ?>
            <tr>
                <td><?php echo $course['course_id']; ?></td>
                <td><?php echo $course['course_name']; ?></td>
                <td><?php echo $course['credits']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <h3>Past Courses</h3>
    <?php if (empty($past_courses)): ?>
        <p>No past courses.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Credits</th>
                <th>Grade</th>
                <th>Semester</th>
                <th>Year</th>
            </tr>
            <?php foreach ($past_courses as $course): ?>
            <tr>
                <td><?php echo $course['course_id']; ?></td>
                <td><?php echo $course['course_name']; ?></td>
                <td><?php echo $course['credits']; ?></td>
                <td><?php echo $course['grade']; ?></td>
                <td><?php echo $course['semester']; ?></td>
                <td><?php echo $course['year']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <p><strong>Total Credits Earned:</strong> <?php echo $total_credits; ?></p>
    <p><strong>Cumulative GPA:</strong> <?php echo $gpa; ?></p>

    <form action="undergraduate_page.php" method="get">
        <input type="submit" value="Back to Home Page">
    </form>
</body>
</html>