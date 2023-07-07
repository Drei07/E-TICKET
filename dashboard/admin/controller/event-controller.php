<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__.'/../../../database/dbconfig.php';

class Event {
    private $conn;

    public function __construct() 
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    //add event
    public function addEvent($event_name, $event_date, $event_time, $event_venue, $event_max_guest, $event_rules, $event_type, $event_poster, $event_price, $course_id, $year_level_id){
        $folder = "../../../src/img/" . basename($event_poster);
        chmod($folder, 0755);
        $stmt = $this->runQuery('INSERT INTO events (event_name, event_date, event_time, event_venue, event_max_guest, event_rules, event_type, event_poster, event_price, course_id, year_level_id) VALUES (:event_name, :event_date, :event_time, :event_venue, :event_max_guest, :event_rules, :event_type, :event_poster, :event_price, :course_id, :year_level_id)');
        $exec = $stmt->execute(array(
            ":event_name" => $event_name,
            ":event_date" => $event_date,
            ":event_time" => $event_time,
            ":event_venue" => $event_venue,
            ":event_max_guest" => $event_max_guest,
            ":event_rules" => $event_rules,
            ":event_type" => $event_type,
            ":event_poster" => $event_poster,
            ":event_price" => $event_price,
            ":course_id" => $course_id,
            ":year_level_id" => $year_level_id
        ));

        if ($$exec && move_uploaded_file($_FILES['event_poster']['tmp_name'], $folder)) {
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

        header("Location: ../course-events");
    }

 // EDIT
public function editEvent($event_id, $event_name, $event_date, $event_time, $event_venue, $event_max_guest, $event_rules, $event_type, $event_poster, $event_price){
    $stmt = $this->runQuery('SELECT event_name, event_date, event_time, event_venue, event_max_guest, event_rules, event_type, event_price FROM events WHERE id=:id');
    $stmt->execute(array(":id" => $event_id));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_event_name = $row['event_name'];
    $current_event_date = $row['event_date'];
    $current_event_time = $row['event_time'];
    $current_event_venue = $row['event_venue'];
    $current_event_max_guest = $row['event_max_guest'];
    $current_event_rules = $row['event_rules'];
    $current_event_type = $row['event_type'];
    $current_event_price = $row['event_price'];


    if ($current_event_name != $event_name ||
        $current_event_date != $event_date ||
        $current_event_time != $event_time ||
        $current_event_venue != $event_venue ||
        $current_event_max_guest != $event_max_guest ||
        $current_event_rules != $event_rules ||
        $current_event_type != $event_type ||
        $current_event_price != $event_price)
    {
        $stmt = $this->runQuery('UPDATE events SET event_name=:event_name, event_date=:event_date, event_time=:event_time, event_venue=:event_venue, event_max_guest=:event_max_guest, event_rules=:event_rules, event_type=:event_type, event_price=:event_price WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id" => $event_id,
            ":event_name" => $event_name,
            ":event_date" => $event_date,
            ":event_time" => $event_time,
            ":event_venue" => $event_venue,
            ":event_max_guest" => $event_max_guest,
            ":event_rules" => $event_rules,
            ":event_type" => $event_type,
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

        //delete course
        public function deleteEvent($event_id){
            $disabled = "disabled";
            $stmt = $this->runQuery('UPDATE events SET status=:status WHERE id=:id');
            $exec = $stmt->execute(array(
                ":id"        => $event_id,
                ":status"   => $disabled,
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
    
            header('Location: ../course-events');
            exit();
    
        }


    //activate event
    public function activateEvent($event_id){
        $active = "active";
        $stmt = $this->runQuery('UPDATE events SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $event_id,
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

        header('Location: ../course-events');
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
    $event_type         = trim($_POST['event_type']);
    $event_poster       = $_FILES['event_poster']['name'];
    $event_price        = trim($_POST['event_price']);
    $course_id          = $_GET['course_id'];
    $year_level_id      = $_GET['year_level_id'];



    $add_event = new Event();
    $add_event->addEvent($event_name, $event_date, $event_time, $event_venue, $event_max_guest, $event_rules, $event_type, $event_poster, $event_price, $course_id, $year_level_id);
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
    $event_type         = trim($_POST['event_type']);
    $event_poster       = $_FILES['event_poster']['name'];
    $event_price        = trim($_POST['event_price']);

    $edit_event = new Event();
    $edit_event->editEvent($event_id, $event_name, $event_date, $event_time, $event_venue, $event_max_guest, $event_rules, $event_type, $event_poster, $event_price);
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
