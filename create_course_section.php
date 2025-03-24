<?php
session_start();
include("database.php");
include("functions.php");

if (isset($_SESSION['error'])) {
    echo $_SESSION['error'];
    unset($_SESSION['error']); 
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $course_id = $_POST['course_id'];
    $section_id = $_POST['section_id'];
    $semester = $_POST['semester'];
    $year = intval($_POST['year']);
    $instructor_id = $_POST['instructor_id'];
    $classroom_id = $_POST['classroom_id'];
    $time_slot_id = $_POST['time_slot_id'];

    if(!empty($course_id) && !empty($section_id) && !empty($semester) && !empty($year) && ($semester == 'Fall' || $semester == 'Spring')) {
        
        // limit of 2 sections per timeslot
        $sid_count = "select count(section_id) as count from section where time_slot_id = '$time_slot_id' and semester = '$semester' and year = '$year' and course_id = '$course_id'";
        $sid_count_res = mysqli_query($myconnection, $sid_count);
        if($sid_count_res) {
            $row = mysqli_fetch_assoc($sid_count_res);
            $num_sections = $row['count'];

            if ($num_sections >= 2) {
                $_SESSION['error'] = "Error: This timeslot is full, choose a different timeslot.";
                header("Location: create_course_section.php");
                exit;
            }
        }

        // an instructor can only teach 1 or two sections per semester
        $teach_count = "select count(section_id) as count from section where instructor_id = '$instructor_id' and semester = '$semester' and year = '$year'";
        $teach_count_res = mysqli_query($myconnection, $teach_count);
        if($teach_count_res) {
            $row = mysqli_fetch_assoc($teach_count_res);
            $num_sections = $row['count'];

            if ($num_sections >= 2) {
                $_SESSION['error'] = "Error: This instructor is unavailible, choose a different instructor.";
                header("Location: create_course_section.php");
                exit;
            }
        }

        // if an instructor is assigned two sections, the two must be scheduled in consecutive timeslots
        if($teach_count_res) {
            $row = mysqli_fetch_assoc($teach_count_res);
            $num_sections = $row['count'];

            if ($num_sections == 1) {
                $existing_time = "select start_time, end_time from time_slot t, section s where t.time_slot_id = s.time_slot_id and s.instructor_id = '$instructor_id' and s.semester = '$semester' and year = '$year'";
                
                $current_time = "select start_time, end_time from time_slot where time_slot_id = $time_slot_id";                
            }

        }
        
        $query = "insert into section (course_id, section_id, semester, year, instructor_id, classroom_id, time_slot_id) values ('$course_id', '$section_id', '$semester', '$year', '$instructor_id', '$classroom_id', '$time_slot_id')";
        mysqli_query($myconnection, $query);
        header("Location: create_course_section.php");
        exit;

    } else {
        echo "Enter Information!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create New Course Section</title>
</head>
    <body>
        <h1>Create New Course Section</h1>
        <form action="create_course_section.php" method="post">
        <input type="text" name="course_id" placeholder="Enter Course ID" required><br><br>
        <input type="text" name="section_id" placeholder="Enter Section ID"><br><br>

        <select name="semester" id="semester">
            <option value="">-- Select Semester --</option>
            <option value="Fall">Fall</option>
            <option value="Spring">Spring</option>
        </select><br><br>

        <input type="number" name="year" placeholder="Enter Year"><br><br>

        <select name="instructor_id" id="instructor_id">
            <option value="">-- Select Instructor ID --</option>
            <option value="1">David Adams (ID: 1)</option>
            <option value="2">Sirong Lin (ID: 2)</option>
            <option value="3">Yelena Rykalova (ID: 3)</option>
            <option value="4">Johannes Weis (ID: 4)</option>
            <option value="5">Tom Wilkes (ID: 5)</option>
        </select><br><br>

        <input type="text" name="classroom_id" placeholder="Enter Classroom ID"><br><br>

        <select name="time_slot_id" id="time_slot_id">
            <option value="">-- Select Time Slot ID --</option>
            <option value="TS1">MWF 11 - 11:50 (ID: TS1)</option>
            <option value="TS2">MWF 12 - 12:50 (ID: TS2)</option>
            <option value="TS3">MWF 13:00 - 13:50 elena Rykalova (ID: TS3)</option>
            <option value="TS4">TT 11 - 12:15 (ID: TS4)</option>
            <option value="TS5">TT 12:30 - 13:45 (ID: TS5)</option>
        </select><br><br>


        <input type="submit" value="Create Course">
    </form>

    <br>

    <form action="admin_page.php" method="get">
        <input type="submit" value="Back to Home Page">
    </form>

    </body>
</html>