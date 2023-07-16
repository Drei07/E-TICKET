<?php
require_once __DIR__ . '/../../database/dbconfig2.php';
// include_once '../../configuration/settings-configuration.php';

// // instances of the classes
// $config = new SystemConfig();

function fetchAccessTokenData($pdoConnect, $access_token)
{
    $pdoQuery = "SELECT * FROM access_token WHERE token = :token";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->execute(array(":token" => $access_token));
    return $pdoResult->fetch(PDO::FETCH_ASSOC);
}

function fetchCourseEventData($pdoConnect, $course_id, $year_level_id)
{
    $pdoQuery = "SELECT * FROM course_event WHERE course_id = :course_id AND year_level_id = :year_level_id";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->execute(array(":course_id" => $course_id, ":year_level_id" => $year_level_id));
    return $pdoResult->fetch(PDO::FETCH_ASSOC);
}

function fetchCourseData($pdoConnect, $course_id)
{
    $pdoQuery = "SELECT * FROM course WHERE id = :id";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->execute(array(":id" => $course_id));
    return $pdoResult->fetch(PDO::FETCH_ASSOC);
}

function fetchDepartmentData($pdoConnect, $department_id)
{
    $pdoQuery = "SELECT * FROM department WHERE id = :id";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->execute(array(":id" => $department_id));
    return $pdoResult->fetch(PDO::FETCH_ASSOC);
}

function fetchYearLevelData($pdoConnect, $year_level_id)
{
    $pdoQuery = "SELECT * FROM year_level WHERE id = :id";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->execute(array(":id" => $year_level_id));
    return $pdoResult->fetch(PDO::FETCH_ASSOC);
}

$access_token = $_SESSION['token'];
$access_token_data = fetchAccessTokenData($pdoConnect, $access_token);

$courseId = $access_token_data['course_id'];
$yearLevelId = $access_token_data['year_level_id'];

$course_event_data = fetchCourseEventData($pdoConnect, $courseId, $yearLevelId);

$course_id = $course_event_data["course_id"];
$course_data = fetchCourseData($pdoConnect, $course_id);

$department_id = $course_data['department_id'];
$department_data = fetchDepartmentData($pdoConnect, $department_id);

$year_level_id = $course_event_data['year_level_id'];
$year_level_data = fetchYearLevelData($pdoConnect, $year_level_id);


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
            padding: 20px;
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

    </style>
</head>
<body>
    <div class="ticket">
        <h1>E-CKET</h1>
        <h2><?php echo $department_data['department'] ?></h2>
        <h3><?php echo $course_data['course']; ?></h3>
        <p><?php echo $year_level_data['year_level']; ?> (Mandatory Events)</p>
    </div>
</body>
</html>
