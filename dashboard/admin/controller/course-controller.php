<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__.'/../../../database/dbconfig.php';

class Course {
    private $conn;

    public function __construct() 
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    //add course
    public function addCourse($course_name, $department){
        $stmt = $this->runQuery('INSERT INTO course (department_id, course) VALUES (:department_id, :course)');
        $exec = $stmt->execute(array(
            ":department_id"  => $department,
            ":course"         => $course_name,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Course added successfully';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../course');
    }
    
    //edit course
    public function editCourse($course_id, $course_name, $department) {
        // Check if the course name and department have actually changed
        $old_data_stmt = $this->runQuery('SELECT course, department_id FROM course WHERE id=:id');
        $old_data_stmt->execute(array(
            ":id" => $course_id,
        ));
        $old_data = $old_data_stmt->fetch(PDO::FETCH_ASSOC);
        $old_name = $old_data['course'];
        $old_department = $old_data['department_id'];
        
        if ($old_name == $course_name && $old_department == $department) {
            // Course name and department have not changed, don't need to update
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'No changes were made.';
            $_SESSION['status_code'] = 'info';
            $_SESSION['status_timer'] = 40000;
            
            header('Location: ../course');
            exit();
        }
        
        // Course name or department has changed, execute UPDATE query
        $stmt = $this->runQuery('UPDATE course SET department_id=:department_id, course=:course WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id" => $course_id,
            ":department_id" => $department,
            ":course" => $course_name,
        ));
        
        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Course successfully updated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }
        
        header('Location: ../course');
        exit();
    }    

    //delete course
    public function deleteCourse($course_id){
        $disabled = "disabled";
        $stmt = $this->runQuery('UPDATE course SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $course_id,
            ":status"   => $disabled,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Course successfully deleted!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../course');
        exit();

    }

    //activate course
    public function activateCourse($course_id){
        $active = "active";
        $stmt = $this->runQuery('UPDATE course SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $course_id,
            ":status"   => $active,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Course successfully activated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../course');
    }


    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

}

//add
if (isset($_POST['btn-add-course'])) {
    $department      = trim($_POST['department']);
    $course_name     = trim($_POST['course']);

    $add_Course = new Course();
    $add_Course->addCourse($course_name, $department);
}

//edit
if (isset($_POST['btn-edit-course'])) {
    $course_id       = $_GET["id"];
    $department      = trim($_POST['department']);
    $course_name     = trim($_POST['course']);

    $edit_course = new Course();
    $edit_course->editCourse($course_id, $course_name, $department );
}

//delete
if (isset($_GET['delete_course'])) {
    $course_id = $_GET["id"];

    $delete_course = new Course();
    $delete_course->deleteCourse($course_id);
}

//activate
if (isset($_GET['activate_course'])) {
    $course_id = $_GET["id"];

    $activate_course = new Course();
    $activate_course->activateCourse($course_id);
}



?>