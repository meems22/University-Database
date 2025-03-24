<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("database.php");
include("functions.php");

$user_data = check_login($myconnection);
$header_string = "";

if ($user_data['type'] == "admin") {
    $header_string = "admin_page.php";

    $instructors_query = "select * from instructor";
    $instructors_result = mysqli_query($myconnection, $instructors_query);
    $instructors = mysqli_fetch_all($instructors_result, MYSQLI_ASSOC);

    $available_phd_query = "select * from student join PhD on student.student_id = PhD.student_id where PhD.student_id not in 
                            (select student_id from advise group by student_id having count(*) = 2)";

    $unavailable_phd_query = "select * from student join PhD on student.student_id = PhD.student_id where PhD.student_id in 
                                (select student_id from advise group by student_id having count(*) = 2)";
} else if ($user_data['type'] == "instructor") {
    $header_string = "instructor_page.php";
    $instructor = get_instructor($myconnection);
    $instructor_id = $instructor['instructor_id'];

    $available_phd_query = "select * from student join PhD on student.student_id = PhD.student_id where PhD.student_id not in 
                            (select student_id from advise group by student_id having count(*) = 2) and PhD.student_id not in
                            (select student_id from advise where instructor_id = '$instructor_id')";

    $unavailable_phd_query = "select * from student join PhD on student.student_id = PhD.student_id where PhD.student_id in 
                                (select student_id from advise group by student_id having count(*) = 2) or PhD.student_id in
                                (select student_id from advise where instructor_id = '$instructor_id')";
}

$available_phd_result = mysqli_query($myconnection, $available_phd_query);
$available_phd = mysqli_fetch_all($available_phd_result, MYSQLI_ASSOC);

$unavailable_phd_result = mysqli_query($myconnection, $unavailable_phd_query);
$unavailable_phd = mysqli_fetch_all($unavailable_phd_result, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $instructor_id = $_POST['instructor_id'];
    $student_id = $_POST['student_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if (!empty($instructor_id) && !empty($student_id) && !empty($start_date)) {
        $query = "insert into advise values ('$instructor_id', '$student_id', '$start_date', '$end_date')";
        mysqli_query($myconnection, $query);
        header("Location: $header_string");
        exit();
    } else {
        echo "Please fill out all fields.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Advisor Appointment</title>
</head>

<body>
    <form method="post">
        <?php if ($user_data['type'] == "admin"): ?>
            <h2>Appoint Advisor/Advisee</h2>
            <h3>Instructor Advisors:</h3>
            <?php if (mysqli_num_rows($instructors_result) != 0): ?>
                <table border="1" cellpadding="10">
                    <tr>
                        <th>Select</th>
                        <th>Name</th>
                        <th>Instructor ID</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Title</th>
                    </tr>
                    <?php foreach ($instructors as $instructor): ?>
                        <tr align="center">
                            <td>
                                <input type="radio" name="instructor_id" value="<?php echo $instructor['instructor_id']; ?>">
                            </td>
                            <td><?php echo $instructor['instructor_name']; ?></td>
                            <td><?php echo $instructor['instructor_id']; ?></td>
                            <td><?php echo $instructor['email']; ?></td>
                            <td><?php echo $instructor['dept_name']; ?></td>
                            <td><?php echo $instructor['title']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table><br>
            <?php else: ?>
                <p>No instructors available.</p><br>
            <?php endif; ?>
        <?php else: ?>
            <input type="hidden" name="instructor_id" value="<?php echo get_instructor($myconnection)['instructor_id']; ?>">
            <h2>Appoint Advisee</h2>
        <?php endif; ?>

        <h3>Available PhD Students:</h3>
        <?php if (mysqli_num_rows($available_phd_result) != 0): ?>
            <table border="1" cellpadding="10">
                <tr>
                    <th>Select</th>
                    <th>Name</th>
                    <th>Student ID</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Qualifier</th>
                    <th>Proposal Defense Date</th>
                    <th>Dissertation Defense Date</th>
                </tr>
                <?php foreach ($available_phd as $phd): ?>
                    <tr align="center">
                        <td>
                            <input type="radio" name="student_id" value="<?php echo $phd['student_id']; ?>">
                        </td>
                        <td><?php echo $phd['name']; ?></td>
                        <td><?php echo $phd['student_id']; ?></td>
                        <td><?php echo $phd['email']; ?></td>
                        <td><?php echo $phd['dept_name']; ?></td>
                        <td><?php echo $phd['qualifier']; ?></td>
                        <td><?php echo $phd['proposal_defence_date']; ?></td>
                        <td><?php echo $phd['dissertation_defence_date']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table><br>
        <?php else: ?>
            <p>No available PhD students.</p><br>
        <?php endif; ?>
        <h3>Unavailable PhD Students:</h3>
        <?php if (mysqli_num_rows($unavailable_phd_result) != 0): ?>
            <table border="1" cellpadding="10">
                <tr>
                    <th>Name</th>
                    <th>Student ID</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Qualifier</th>
                    <th>Proposal Defense Date</th>
                    <th>Dissertation Defense Date</th>
                </tr>
                <?php foreach ($unavailable_phd as $phd): ?>
                    <tr align="center">
                        <td><?php echo $phd['name']; ?></td>
                        <td><?php echo $phd['student_id']; ?></td>
                        <td><?php echo $phd['email']; ?></td>
                        <td><?php echo $phd['dept_name']; ?></td>
                        <td><?php echo $phd['qualifier']; ?></td>
                        <td><?php echo $phd['proposal_defence_date']; ?></td>
                        <td><?php echo $phd['dissertation_defence_date']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table><br>
        <?php else: ?>
            <p>No unavailable PhD students.</p><br>
        <?php endif; ?>
        <?php if (
            mysqli_num_rows($available_phd_result) != 0 && ($user_data['type'] == "instructor" ||
                ($user_data['type'] == "admin" && mysqli_num_rows($instructors_result) != 0))
        ): ?>
            <label for="start">Select Start Date:</label>
            <input type="date" id="start" name="start_date" value="<?php echo date('Y-m-d'); ?>"
                min="<?php echo date('Y-m-d'); ?>" required><br><br>
            <label for="end">Select End Date:</label>
            <input type="date" id="end" name="end_date" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"><br><br><br>
            <input type="submit" value="Appoint">
        <?php endif; ?>
    </form><br>
    <a href="<?php echo $header_string; ?>">Back to Home Page</a>