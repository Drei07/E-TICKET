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
    public function addEvent($event_name, $event_date, $event_time, $event_venue, $event_max_guest, $event_rules, $event_type, $event_poster, $course_id, $year_level_id){
        $stmt = $this->runQuery('INSERT INTO events (event_name, event_date, event_time, event_venue, event_max_guest, event_rules, event_type, event_poster, course_id, year_level_id) VALUES (:event_name, :event_date, :event_time, :event_venue, :event_max_guest, :event_rules, :event_type, :event_poster, :course_id, :year_level_id)');
        $exec = $stmt->execute(array(
            ":event_name" => $event_name,
            ":event_date" => $event_date,
            ":event_time" => $event_time,
            ":event_venue" => $event_venue,
            ":event_max_guest" => $event_max_guest,
            ":event_rules" => $event_rules,
            ":event_type" => $event_type,
            ":event_poster" => $event_poster,
            ":course_id" => $course_id,
            ":year_level_id" => $year_level_id
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

        header("Location: ../course-events");
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
    $course_id          = $_GET['course_id'];
    $year_level_id      = $_GET['year_level_id'];



    $add_event = new Event();
    $add_event->addEvent($event_name, $event_date, $event_time, $event_venue, $event_max_guest, $event_rules, $event_type, $event_poster, $course_id, $year_level_id);
}




?>