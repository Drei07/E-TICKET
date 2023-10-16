<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';

class Event
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    //add event
    public function addEvent($event_name, $event_date, $event_time, $event_venue, $event_max_guest, $event_rules, $event_poster, $event_price)
    {
        // Check if the event name already exists
        if ($this->isEventNameExists($event_name)) {
            $_SESSION['status_title'] = 'Error';
            $_SESSION['status'] = 'Event with the same name already exists';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
            header("Location: ../events");
            return; // Exit the function
        }
    
        $folder = "../../../src/img/" . basename($event_poster);
        chmod($folder, 0755);
        $stmt = $this->runQuery('INSERT INTO events (event_name, event_date, event_time, event_venue, event_max_guest, event_rules, event_poster, event_price) VALUES (:event_name, :event_date, :event_time, :event_venue, :event_max_guest, :event_rules, :event_poster, :event_price)');
        $exec = $stmt->execute(array(
            ":event_name" => $event_name,
            ":event_date" => $event_date,
            ":event_time" => $event_time,
            ":event_venue" => $event_venue,
            ":event_max_guest" => $event_max_guest,
            ":event_rules" => $event_rules,
            ":event_poster" => $event_poster,
            ":event_price" => $event_price,
        ));
    
        if ($exec && move_uploaded_file($_FILES['event_poster']['tmp_name'], $folder)) {
            $lastInsertedId = $this->conn->lastInsertId();
    
            // Generate access key
            function generateAccessKey()
            {
                // Generate a unique access key using any desired method or algorithm
                // Example: Generate a random string
                $length = 10;
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $accessKey = '';
                for ($i = 0; $i < $length; $i++) {
                    $accessKey .= $characters[rand(0, strlen($characters) - 1)];
                }
                return $accessKey;
            }
    
            // Check if access key already exists
            function isAccessKeyExists($accessKey)
            {
                $database = new Database();
                $db = $database->dbConnection();
                $stmt = $db->prepare('SELECT COUNT(*) FROM event_access_key WHERE access_key = :access_key');
                $stmt->execute(array(":access_key" => $accessKey));
                $count = $stmt->fetchColumn();
                return ($count > 0);
            }
    
            // Generate unique access key
            $accessKey = generateAccessKey();
    
            // Check if access key already exists
            while (isAccessKeyExists($accessKey)) {
                $accessKey = generateAccessKey();
            }
    
            // Insert into event_access_key table
            $accessKeyStmt = $this->runQuery('INSERT INTO event_access_key (event_id, access_key) VALUES (:event_id, :access_key)');
            $accessKeyStmt->execute(array(
                ":event_id" => $lastInsertedId,
                ":access_key" => $accessKey,
            ));
    
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Event added successfully';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }
    
        header("Location: ../events");
    }    

    //Check if event name is exists
    public function isEventNameExists($event_name)
        {
    $stmt = $this->runQuery('SELECT COUNT(*) FROM events WHERE event_name = :event_name');
    $stmt->execute(array(":event_name" => $event_name));
    $count = $stmt->fetchColumn();

    return ($count > 0);
    }

    // EDIT
    public function editEvent($event_id, $event_name, $event_date, $event_time, $event_venue, $event_max_guest, $event_rules, $event_poster, $event_price)
    {
        $stmt = $this->runQuery('SELECT event_name, event_date, event_time, event_venue, event_max_guest, event_rules, event_price FROM events WHERE id=:id');
        $stmt->execute(array(":id" => $event_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_event_name = $row['event_name'];
        $current_event_date = $row['event_date'];
        $current_event_time = $row['event_time'];
        $current_event_venue = $row['event_venue'];
        $current_event_max_guest = $row['event_max_guest'];
        $current_event_rules = $row['event_rules'];
        $current_event_price = $row['event_price'];


        if (
            $current_event_name != $event_name ||
            $current_event_date != $event_date ||
            $current_event_time != $event_time ||
            $current_event_venue != $event_venue ||
            $current_event_max_guest != $event_max_guest ||
            $current_event_rules != $event_rules ||
            $current_event_price != $event_price
        ) {
            $stmt = $this->runQuery('UPDATE events SET event_name=:event_name, event_date=:event_date, event_time=:event_time, event_venue=:event_venue, event_max_guest=:event_max_guest, event_rules=:event_rules, event_price=:event_price WHERE id=:id');
            $exec = $stmt->execute(array(
                ":id" => $event_id,
                ":event_name" => $event_name,
                ":event_date" => $event_date,
                ":event_time" => $event_time,
                ":event_venue" => $event_venue,
                ":event_max_guest" => $event_max_guest,
                ":event_rules" => $event_rules,
                ":event_price" => $event_price,

            ));

            if ($exec) {
                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = 'Event details successfully updated!';
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;
            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Something went wrong, please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
            }
        } else {
            $_SESSION['status_title'] = 'No Changes';
            $_SESSION['status'] = 'No changes were made.';
            $_SESSION['status_code'] = 'info';
            $_SESSION['status_timer'] = 40000;
        }


        if (!empty($_FILES['event_poster']['tmp_name'])) {
            $folder = "../../../src/img/" . basename($event_poster);
            chmod($folder, 0755);

            $stmt = $this->runQuery('UPDATE events SET event_poster=:event_poster WHERE id=:id');
            $exec = $stmt->execute(array(
                ":id" => $event_id,
                ":event_poster" => $event_poster,
            ));

            if ($exec && move_uploaded_file($_FILES['event_poster']['tmp_name'], $folder)) {
                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = 'Department logo successfully updated!';
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;
            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Something went wrong, please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
            }
        }

        header('Location: ../events-details');
        exit();
    }

    public function deleteEvent($event_id)
    {
        $disabled = "disabled";
        $stmt = $this->runQuery('UPDATE events SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $event_id,
            ":status"   => $disabled,
        ));

        if ($exec) {
            // Disable related event_per_course entries
            $stmt = $this->runQuery('UPDATE event_per_course SET event_status=:event_status WHERE event_id=:event_id');
            $exec = $stmt->execute(array(
                ":event_id"        => $event_id,
                ":event_status"   => $disabled,
            ));

            if ($exec) {
                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = 'Events successfully deleted!';
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;
            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Something went wrong, please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
            }
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../events');
        exit();
    }


    //activate event
    public function activateEvent($event_id)
    {
        $active = "active";
        $stmt = $this->runQuery('UPDATE events SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $event_id,
            ":status"   => $active,
        ));

        if ($exec) {
            // Disable related event_per_course entries
            $stmt = $this->runQuery('UPDATE event_per_course SET event_status=:event_status WHERE event_id=:event_id');
            $exec = $stmt->execute(array(
                ":event_id"        => $event_id,
                ":event_status"   => $active,
            ));

            if ($exec) {
                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = 'Events successfully activated!';
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;
            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Something went wrong, please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
            }
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../events');
        exit();
    }

    //deactivate event access key
    public function deactivateAccessKey($access_key_id)
    {
        $disabled = "disabled";
        $stmt = $this->runQuery('UPDATE event_access_key SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $access_key_id,
            ":status"   => $disabled,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Access key successfully disabled!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }
        header('Location: ../events-details');
        exit();
    }

    //activate event access key
    public function activateAccessKey($access_key_id)
    {
        $active = "active";
        $stmt = $this->runQuery('UPDATE event_access_key SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $access_key_id,
            ":status"   => $active,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Access key successfully activate!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }
        header('Location: ../events-details');
        exit();
    }


    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

//add
if (isset($_POST['btn-add-event'])) {
    $event_name         = trim($_POST['event_name']);
    $event_date         = trim($_POST['event_date']);
    $event_time         = trim($_POST['event_time']);
    $event_venue        = trim($_POST['event_venue']);
    $event_max_guest    = trim($_POST['event_max_guest']);
    $event_rules        = trim($_POST['event_rules']);
    $event_poster       = $_FILES['event_poster']['name'];
    $event_price        = trim($_POST['event_price']);



    $add_event = new Event();
    $add_event->addEvent($event_name, $event_date, $event_time, $event_venue, $event_max_guest, $event_rules, $event_poster, $event_price);
}

//edit
if (isset($_POST['btn-edit-event'])) {
    $event_id           = $_GET["id"];
    $event_name         = trim($_POST['event_name']);
    $event_date         = trim($_POST['event_date']);
    $event_time         = trim($_POST['event_time']);
    $event_venue        = trim($_POST['event_venue']);
    $event_max_guest    = trim($_POST['event_max_guest']);
    $event_rules        = trim($_POST['event_rules']);
    $event_poster       = $_FILES['event_poster']['name'];
    $event_price        = trim($_POST['event_price']);

    $edit_event = new Event();
    $edit_event->editEvent($event_id, $event_name, $event_date, $event_time, $event_venue, $event_max_guest, $event_rules, $event_poster, $event_price);
}

//delete
if (isset($_GET['delete_event'])) {
    $event_id = $_GET["id"];

    $delete_event = new Event();
    $delete_event->deleteEvent($event_id);
}

//activate
if (isset($_GET['activate_event'])) {
    $event_id = $_GET["id"];

    $activate_event = new Event();
    $activate_event->activateEvent($event_id);
}

//deactivate event access key
if (isset($_POST['btn_deactivate_access_key'])) {
    $access_key_id = $_GET["access_key_id"];

    $deactivate_access_key = new Event();
    $deactivate_access_key->deactivateAccessKey($access_key_id);
}

//acticate event access key
if (isset($_POST['btn_activate_access_key'])) {
    $access_key_id = $_GET["access_key_id"];

    $activate_access_key = new Event();
    $activate_access_key->activateAccessKey($access_key_id);
}



class CourseEvent
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }


    //add event per course
    public function addCourseEvent($event_id, $event_type, $course_id, $year_level_id)
    {
        $stmt = $this->runQuery('SELECT COUNT(*) FROM event_per_course WHERE event_id = :event_id AND  course_id = :course_id AND year_level_id = :year_level_id');
        $stmt->execute(array(
            ":event_id" => $event_id,
            ":course_id" => $course_id,
            ":year_level_id" => $year_level_id,
        ));
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $_SESSION['status_title'] = 'Warning!';
            $_SESSION['status'] = 'The event already exists for the selected event type.';
            $_SESSION['status_code'] = 'warning';
            $_SESSION['status_timer'] = 10000;
        } else {
            $stmt = $this->runQuery('INSERT INTO event_per_course (event_id, event_type, course_id, year_level_id) VALUES (:event_id, :event_type, :course_id, :year_level_id)');
            $exec = $stmt->execute(array(
                ":event_id" => $event_id,
                ":event_type" => $event_type,
                ":course_id" => $course_id,
                ":year_level_id" => $year_level_id,
            ));

            if ($exec) {
                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = 'Event added successfully';
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;
            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Something went wrong, please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
            }
        }

        header('Location: ../course-events-list');
    }

    //delete delete Event Per Course
    public function deleteEventPerCourse($event_per_course)
    {
        $disabled = "disabled";
        $stmt = $this->runQuery('UPDATE event_per_course SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $event_per_course,
            ":status"   => $disabled,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Events successfully remove!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../course-events-list');
        exit();
    }

    //Activate Event Per Course
    public function activateEventPerCourse($event_per_course)
    {
        $active = "active";
        $stmt = $this->runQuery('UPDATE event_per_course SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $event_per_course,
            ":status"   => $active,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Events successfully activated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../course-events-list');
        exit();
    }




    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

if (isset($_POST['btn-add-course-events'])) {
    $event_id      = trim($_POST['event_name']);
    $event_type     = trim($_POST['event_type']);
    $course_id      = $_GET["course_id"];
    $year_level_id = $_GET["year_level_id"];

    $add_course_events = new CourseEvent();
    $add_course_events->addCourseEvent($event_id, $event_type, $course_id, $year_level_id);
}

//delete
if (isset($_GET['delete_event_per_course'])) {
    $event_per_course = $_GET["id"];

    $delete_event_per_course = new CourseEvent();
    $delete_event_per_course->deleteEventPerCourse($event_per_course);
}

//activate
if (isset($_GET['activate_event_per_course'])) {
    $event_per_course = $_GET["id"];

    $activate_event_per_course = new CourseEvent();
    $activate_event_per_course->activateEventPerCourse($event_per_course);
}
