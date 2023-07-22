<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';

class CourseEvent
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    //add course
    public function addCourseEvent($course, $year_level)
    {
        // Check if the data already exists in the database
        $stmt = $this->runQuery('SELECT * FROM course_event WHERE course_id = :course_id AND year_level_id = :year_level_id AND status = :status');
        $stmt->execute(array(
            ":course_id" => $course,
            ":year_level_id" => $year_level,
            ":status" => "active",
        ));
        $existingData = $stmt->fetch();

        if ($existingData) {
            // Data already exists, do not execute the insertion
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Input data already exists!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        } else {
            // Data does not exist, proceed with the insertion
            $stmt = $this->runQuery('INSERT INTO course_event (course_id, year_level_id) VALUES (:course_id, :year_level_id)');
            $exec = $stmt->execute(array(
                ":course_id" => $course,
                ":year_level_id" => $year_level,
            ));

            if ($exec) {
                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = 'Course event added successfully';
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;
            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Something went wrong, please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
            }
        }

        header('Location: ../course-events');
    }


    // Edit course
    public function editCourseEvent($course_event_id, $year_level, $course)
    {
        // Check if the course name and department have actually changed
        $old_data_stmt = $this->runQuery('SELECT course_id, year_level_id FROM course_event WHERE id=:id');
        $old_data_stmt->execute(array(
            ":id" => $course_event_id,
        ));
        $old_data = $old_data_stmt->fetch(PDO::FETCH_ASSOC);
        $old_course = $old_data['course_id'];
        $old_year_level = $old_data['year_level_id'];

        if ($old_course == $course && $old_year_level == $year_level) {
            // Course name and department have not changed, don't need to update
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'No changes were made.';
            $_SESSION['status_code'] = 'info';
            $_SESSION['status_timer'] = 40000;

            header("Location: ../course-events");
            exit();
        }

        // Check if the edited data matches an existing record in the database
        $matching_data_stmt = $this->runQuery('SELECT id FROM course_event WHERE course_id=:course_id AND year_level_id=:year_level_id');
        $matching_data_stmt->execute(array(
            ":course_id" => $course,
            ":year_level_id" => $year_level,
        ));
        $matching_data = $matching_data_stmt->fetch(PDO::FETCH_ASSOC);

        if ($matching_data) {
            // Edited data matches an existing record in the database, do not execute the update query
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'The edited data already exists in the database.';
            $_SESSION['status_code'] = 'info';
            $_SESSION['status_timer'] = 40000;

            header("Location: ../course-events");
            exit();
        }

        // Course name or department has changed, or edited data does not exist in the database, execute UPDATE query
        $stmt = $this->runQuery('UPDATE course_event SET course_id = :course_id, year_level_id = :year_level_id WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id" => $course_event_id,
            ":course_id" => $course,
            ":year_level_id" => $year_level,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Course event successfully updated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header("Location: ../course-events");
        exit();
    }


    //delete course
    public function deleteCourseEvent($course_event_id)
    {
        $disabled = "disabled";
        $stmt = $this->runQuery('UPDATE course_event SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $course_event_id,
            ":status"   => $disabled,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Course event successfully deleted!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../course-events');
        exit();
    }

    //activate course
    public function activateCourseEvent($course_event_id)
    {
        $active = "active";
        $stmt = $this->runQuery('UPDATE course_event SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $course_event_id,
            ":status"   => $active,
        ));


        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Course event successfully activated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../course-events');
    }


    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

//add
if (isset($_POST['btn-add-course-event'])) {
    $year_level      = trim($_POST['year_level']);
    $course          = trim($_POST['course']);

    $add_course_event = new CourseEvent();
    $add_course_event->addCourseEvent($course, $year_level);
}

// //edit
if (isset($_POST['btn-edit-course-event'])) {
    $course_event_id        = $_GET["id"];
    $year_level             = trim($_POST['year_level']);
    $course                 = trim($_POST['course']);;

    $edit_course_event = new CourseEvent();
    $edit_course_event->editCourseEvent($course_event_id, $year_level, $course);
}

//delete
if (isset($_GET['delete_course_event'])) {
    $course_event_id = $_GET["id"];

    $delete_course_event = new CourseEvent();
    $delete_course_event->deleteCourseEvent($course_event_id);
}

//activate
if (isset($_GET['activate_course_event'])) {
    $course_event_id = $_GET["id"];

    $activate_course_event = new CourseEvent();
    $activate_course_event->activateCourseEvent($course_event_id);
}
