<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("database.php");
include("functions.php");

$instructor = get_instructor($myconnection);
$instructor_id = $instructor['instructor_id'];

// current semester and year $current_year=2025;
$current_semester = get_current_semester();
$current_year = date('Y');

// get currently taught courses
$current_courses_q = "select s.course_id, c.course_name, s.section_id from course c, section s where c.course_id = s.course_id and 
                            s.instructor_id = '$instructor_id' and s.semester = '$current_semester' and s.year = '$current_year'";
$current_courses_qres = mysqli_query($myconnection, $current_courses_q);
$current_courses = mysqli_fetch_all($current_courses_qres, MYSQLI_ASSOC);

// get past courses taught
$past_courses_q = "select s.semester, s.year, s.course_id, c.course_name, s.section_id from course c, section s where c.course_id = s.course_id and
                        s.instructor_id = '$instructor_id' and (s.year  < '$current_year' or (s.year = '$current_year' and s.semester != '$current_semester')) 
                        order by s.year desc,
                            case s.semester
                                when 'Fall' then 1
                                when 'Summer' then 2
                                when 'Spring' then 3
                                when 'Winter' then 4
                            end;"
;
$past_courses_qres = mysqli_query($myconnection, $past_courses_q);
$past_courses = mysqli_fetch_all($past_courses_qres, MYSQLI_ASSOC); ?>

<!DOCTYPE html>
<style>
    .indented-table {
        margin-left: 20px;
    }
</style>
<html>

<head>
    <title>Course Information</title>
</head>

<body>
    <h2>Course List:</h2>

    <h3>Current Courses (<?php echo $current_semester; ?> <?php echo $current_year; ?>)</h3>
    <?php if (empty($current_courses)): ?>
        <p>No current courses.</p>
    <?php else: ?>
        <table border="1" cellpadding="10">
            <?php foreach ($current_courses as $course): ?>
                <?php
                $course_id = $course['course_id'];
                $course_name = $course['course_name'];
                $section_id = $course['section_id'];
                ?>
                <tr>
                    <td>
                        <details>
                            <summary><?php echo $course_id; ?> - <?php echo $course_name; ?> | <?php echo $section_id; ?>
                            </summary>
                            <?php
                            $names_query = "select s.name from take t, student s where t.course_id = '$course_id' and t.section_id = '$section_id' and
                                    t.semester = '$current_semester' and t.year = '$current_year' and t.student_id = s.student_id";
                            $names_result = mysqli_query($myconnection, $names_query);
                            $names = mysqli_fetch_all($names_result, MYSQLI_ASSOC);
                            ?>
                            <?php if (empty($names)): ?>
                                <p>No students in this section.</p>
                            <?php else: ?>
                                <table class="indented-table" border="1" cellpadding="10">
                                    <tr>
                                        <th>Student Name</th>
                                    </tr>

                                    <?php foreach ($names as $name): ?>
                                        <tr>
                                            <td><?php echo $name['name']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            <?php endif; ?>
                        </details>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <h3>Past Courses</h3>
    <?php if (empty($past_courses)): ?>
        <p>No past courses.</p>
    <?php else: ?>
        <table border="1" cellpadding="10">
            <?php
            $semester_in_list = "";
            $year_in_list = 0;
            ?>
            <?php foreach ($past_courses as $course): ?>
                <?php
                $course_id = $course['course_id'];
                $course_name = $course['course_name'];
                $section_id = $course['section_id'];
                $semester = $course['semester'];
                $year = $course['year'];
                ?>
                <?php if ($semester != $semester_in_list || $year != $year_in_list): ?>
                    <?php
                    $semester_in_list = $semester;
                    $year_in_list = $year;
                    ?>
                    <tr>
                        <th>
                            <?php echo $semester; ?>             <?php echo $year; ?>
                        </th>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td>
                        <details>
                            <summary><?php echo $course_id; ?> - <?php echo $course_name; ?> | <?php echo $section_id; ?>
                            </summary>
                            <?php
                            $student_query = "select s.name, t.grade from take t, student s where t.course_id = '$course_id' and t.section_id = '$section_id' and
                                                    t.semester = '$semester' and t.year = '$year' and t.student_id = s.student_id";
                            $student_result = mysqli_query($myconnection, $student_query);
                            $students = mysqli_fetch_all($student_result, MYSQLI_ASSOC);
                            ?>
                            <?php if (empty($students)): ?>
                                <p>No students in this section.</p>
                            <?php else: ?>
                                <table class="indented-table" border="1" cellpadding="10">
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Grade</th>
                                    </tr>
                                    <?php foreach ($students as $student): ?>
                                        <tr>
                                            <td><?php echo $student['name']; ?></td>
                                            <td align="center"><?php echo $student['grade']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            <?php endif; ?>
                        </details>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?><br>

    <a href="instructor_page.php">Back to Home Page</a>
</body>

</html>