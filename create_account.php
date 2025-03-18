<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    include("database.php");
    include("functions.php");

    // check if user already has an account
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $id = intval($_POST['id']);
        $name = $_POST['name'];
        $dept_name = $_POST['dept_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $student_type = $_POST['student_type'];
        $class_standing = $_POST['class_standing'];

        if(!empty($email) && !empty($password) && !is_numeric($email)) {
            // save information to account table (account first): 
            $query = "insert into account (email, password, type) values ('$email', '$password', 'student')";
            mysqli_query($myconnection, $query);

            // save info to student table
            $query2 = "insert into student (student_id, name, email, dept_name) values ('$id', '$name','$email', '$dept_name')";
            mysqli_query($myconnection, $query2);
            
            // save info into type of student table (undergraduate, masters, PhD)
            if ($student_type == 'Undergraduate') {
                $query3 = "insert into undergraduate (student_id, total_credits, class_standing) values ('$id', 0, '$class_standing')";
                mysqli_query($myconnection, $query3);
            } else if ($student_type == 'Master') {
                $query3 = "insert into master (student_id, total_credits) values ('$id', 0)";
                mysqli_query($myconnection, $query3);
            } else if ($student_type == 'PhD') {
                $query3 = "insert into PhD (student_id, qualifier, proposal_defence_date, dissertation_defence_date) values ('$id', NULL, NULL, NULL)";
                mysqli_query($myconnection, $query3);
            }
            

            // after the user signs up they are redirected to the login page
            header("Location: login.php");
            die();
        } else { 
            echo "Enter Information!";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form method="post">
        
        <div>Create Account</div>
        <input type="text" name="id" placeholder="Enter ID" required><br><br>
        <input type ="text" name ="name" placeholder="Name"><br><br>
        <input type ="text" name ="dept_name" placeholder="Department Name"><br><br>
        <input type ="text" name ="email" placeholder="Email"><br><br>
        <input type ="password" name ="password" placeholder="Password"><br><br>


        <label for="student_type">Select Student Type:</label>
        <select name="student_type" id="student_type">
            <option value="Undergraduate">Undergraduate</option>
            <option value="Master">Master's</option>
            <option value="PhD">PhD</option>
        </select><br><br>

        <label for="class_standing">If Undergraduate, select year:</label>
        <select name="class_standing" id="class_standing">
            <option value="">-- Select Year --</option>
            <option value="Freshman">Freshman</option>
            <option value="Sophomore">Sophomore</option>
            <option value="Junior">Junior</option>
            <option value="Senior">Senior</option>
        </select><br><br>

        <input type="submit" value="Create Account"><br><br>
        <a href="login.php">Click to Login</a><br><br>
    </form>
</body>
</html>
