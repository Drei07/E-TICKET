<?php
require_once __DIR__ . '/../../database/dbconfig2.php';
// include_once '../../configuration/settings-configuration.php';

// // instances of the classes
// $config = new SystemConfig();

// Access token
$access_token = $_SESSION['token'];
$access_token_data = fetchAccessTokenData($pdoConnect, $access_token);

function fetchAccessTokenData($pdoConnect, $access_token)
{
    $pdoQuery = "SELECT * FROM access_token WHERE token = :token";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->execute(array(":token" => $access_token));
    return $pdoResult->fetch(PDO::FETCH_ASSOC);
}

// Course event data
$courseId = $access_token_data['course_id'];
$yearLevelId = $access_token_data['year_level_id'];
$eventsId = $access_token_data['event_id'];

if ($courseId == NULL || $yearLevelId == NULL) {
    // Event data
    $events_data = fetchEventData($pdoConnect, $eventsId);
} else {
    $course_event_data = fetchCourseEventData($pdoConnect, $courseId, $yearLevelId);

    // Course data
    $course_id = $course_event_data["course_id"];
    $course_data = fetchCourseData($pdoConnect, $course_id);

    // Department data
    $department_id = $course_data['department_id'];
    $department_data = fetchDepartmentData($pdoConnect, $department_id);

    // Year level data
    $year_level_id = $course_event_data['year_level_id'];
    $year_level_data = fetchYearLevelData($pdoConnect, $year_level_id);
}

// Fetch event data
function fetchEventData($pdoConnect, $eventsId)
{
    $pdoQuery = "SELECT * FROM events WHERE id = :id";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->execute(array(":id" => $eventsId));
    return $pdoResult->fetch(PDO::FETCH_ASSOC);
}

// Fetch course event data
function fetchCourseEventData($pdoConnect, $course_id, $year_level_id)
{
    $pdoQuery = "SELECT * FROM course_event WHERE course_id = :course_id AND year_level_id = :year_level_id";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->execute(array(":course_id" => $course_id, ":year_level_id" => $year_level_id));
    return $pdoResult->fetch(PDO::FETCH_ASSOC);
}

// Fetch course data
function fetchCourseData($pdoConnect, $course_id)
{
    $pdoQuery = "SELECT * FROM course WHERE id = :id";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->execute(array(":id" => $course_id));
    return $pdoResult->fetch(PDO::FETCH_ASSOC);
}

// Fetch department data
function fetchDepartmentData($pdoConnect, $department_id)
{
    $pdoQuery = "SELECT * FROM department WHERE id = :id";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->execute(array(":id" => $department_id));
    return $pdoResult->fetch(PDO::FETCH_ASSOC);
}

// Fetch year level data
function fetchYearLevelData($pdoConnect, $year_level_id)
{
    $pdoQuery = "SELECT * FROM year_level WHERE id = :id";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->execute(array(":id" => $year_level_id));
    return $pdoResult->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='UTF-8'>
    <title>Event Ticket</title>
    <style>
        .ticket {
            width: 400px;
            height: 500px;
            padding: 20rem;
            background-color: #F8F8F8;
            border: 1px solid #F8F8F8;
            border-radius: 10px;
            text-align: center;
        }

        .ticket h1 {
            font-size: 30px;
            font-weight: 700;
        }

        .ticket h2 {
            font-size: 20px;
            font-weight: 500;
        }

        .ticket h3 {
            font-size: 9px;
            font-weight: 400;
        }

        .ticket p {
            font-size: 7px;
            font-weight: 300;
        }

        .ticket .details{
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 5rem;
        }

        .ticket .details p{
            font-size: 9px;
        }

        .ticket .footer{
            display: flex;
            padding: 5rem;
            justify-content: center;
            margin-top: 10rem;
        }
        .ticket .footer p{
            font-size: 9px;
        }
    </style>
</head>

<body>
    <div class="ticket">
        <h1>E-CKET</h1>
        <?php
        if ($courseId == NULL || $yearLevelId == NULL) {
        ?>
            <h2><?php echo $events_data['event_name'] ?></h2>
            <h3><?php echo $events_data['event_venue']; ?></h3>
            <p><?php echo date('m/d/y', strtotime($events_data['event_date'])); ?> (Optional Events)</p>


            <div class="details">
                <p><strong>Full Name: </strong> <?php echo $_SESSION['last_name'] ?>, <?php echo $_SESSION['first_name'] ?> <?php echo $_SESSION['middle_name'] ?></p>
                <p><strong>Phone Number: </strong> +63<?php echo $_SESSION['phone_number'] ?></p>
                <p><strong>Email: </strong> <?php echo $_SESSION['email'] ?></p>
            </div>

            <div class="footer">
                <p>Kindly ensure that you have this ticket with you when attending the event, as it will serve as your entry pass. Should you have any further questions or concerns, please do not hesitate to contact us.</p>
            </div>
        <?php
        } else {
        ?>
            <h2><?php echo $department_data['department'] ?></h2>
            <h3><?php echo $course_data['course']; ?></h3>
            <p><?php echo $year_level_data['year_level']; ?> (Mandatory Events)</p>

            <div class="details">
                <p><strong>Full Name: </strong> <?php echo $_SESSION['last_name'] ?>, <?php echo $_SESSION['first_name'] ?> <?php echo $_SESSION['middle_name'] ?></p>
                <p><strong>Phone Number: </strong> +63<?php echo $_SESSION['phone_number'] ?></p>
                <p><strong>Email: </strong> <?php echo $_SESSION['email'] ?></p>
            </div>

            <div class="footer">
                <p>Kindly ensure that you have this ticket with you when attending the event, as it will serve as your entry pass. Should you have any further questions or concerns, please do not hesitate to contact us.</p>
            </div>
        <?php
        }
        ?>
    </div>
</body>

</html>