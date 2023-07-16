<?php
require_once __DIR__ . '/database/dbconfig2.php';
include_once 'dashboard/user/authentication/user-signin.php';
include_once 'configuration/settings-configuration.php';

$config = new SystemConfig();


if ($_SESSION['token'] === NULL) {
    header('Location: ./');
    exit;
}

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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="src/img/<?php echo $config->getSystemLogo() ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="src/css/landing-page.css?v=<?php echo time(); ?>">
    <title>Get Ticket</title>

</head>

<body>
    <!-- Live queue Modal -->
    <section class="event" id="cov">

        <div class="heading">
            <h1><?php echo $department_data['department'] ?></h1>
            <h2><?php echo $course_data['course']; ?></h2>
            <p><?php echo $year_level_data['year_level']; ?> (Mandatory Events)</p>
        </div>

        <div class="column" id="column1">
            <?php
            $pdoQuery = "SELECT * FROM event_per_course WHERE course_id = :course_id AND year_level_id = :year_level_id AND event_type = :event_type AND status = :status ORDER BY id DESC";
            $pdoResult5 = $pdoConnect->prepare($pdoQuery);
            $pdoResult5->execute(array(
                ":course_id"         => $courseId,
                ":year_level_id"     => $yearLevelId,
                ":event_type"        => 1,
                ":status"            => "active"
            ));
            if ($pdoResult5->rowCount() >= 1) {
                while ($event_per_course_data = $pdoResult5->fetch(PDO::FETCH_ASSOC)) {
                    extract($event_per_course_data);

                    $event_id = $event_per_course_data['event_id'];
                    $eventIds[] = $event_id; // Add the event ID to the array

                    $pdoQuery = "SELECT * FROM events WHERE id = :id";
                    $pdoResult2 = $pdoConnect->prepare($pdoQuery);
                    $pdoExec = $pdoResult2->execute(array(":id" => $event_id));
                    $event_data = $pdoResult2->fetch(PDO::FETCH_ASSOC);

            ?>

                    <div class="image">
                        <img src="src/img/<?php echo $event_data['event_poster'] ?>" alt="event">
                        <h4><?php echo $event_data['event_name'] ?></h4>
                        <p>Event Date: <?php echo date('m/d/y', strtotime($event_data['event_date'])); ?></p>
                        <button type="button" class="more btn-warning">More Info</button>
                    </div>

            <?php
                }
            }
            ?>
        </div>
        <div class="get-ticket">
            <button type="submit" class="btn-dark" data-bs-toggle="modal" data-bs-target="#pre-registration">Get Ticket</button>
            <button type="submit" class="btn-danger"><a href="./" class="cancel">Cancel</a></button>
        </div>
    </section>

    <div class="pre-registration-modal">
        <div class="modal fade" id="pre-registration" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <a href="" id="logo"></a>
                        <p style="font-size: 15px; font-weight:bold;">Confirm your details</p>
                        <a href="" class="close" data-bs-dismiss="modal" aria-label="Close"><img src="src/img/caret-right-fill.svg" alt="close-btn" width="24" height="24"></a>
                    </div>
                    <div class="modal-body">
                        <div class="form-alert">
                            <span id="message"></span>
                        </div>
                        <form action="dashboard/student/controller/student-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                            <div class="row gx-5 needs-validation">
                                <!-- event id -->
                                <?php
                                foreach ($eventIds as $eventId) {
                                    echo '<input type="hidden" name="event_ids[]" value="' . $eventId . '">';
                                }
                                ?>
                                <!-- course id-->
                                <input type="hidden" name="course_id" value="<?php echo $courseId?>">
                                <!-- year level id-->
                                <input type="hidden" name="year_level_id" value="<?php echo $yearLevelId?>">

                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">First Name <span style="font-size:17px; margin-top: 2rem; color:red; opacity:0.8;">*</span></label>
                                    <input type="text" value="<?php echo $_SESSION['first_name'] ?>" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" maxlength="15" autocomplete="off" name="first_name" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" id="first_name" required>
                                    <div class="invalid-feedback">
                                        Please provide a First Name.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" value="<?php echo $_SESSION['middle_name'] ?>" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" maxlength="15" autocomplete="off" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" name="middle_name" id="middle_name">
                                    <div class="invalid-feedback">
                                        Please provide a Middle Name.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name <span style="font-size:17px; margin-top: 2rem; color:red; opacity:0.8;">*</span></label>
                                    <input type="text" value="<?php echo $_SESSION['last_name'] ?>" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" maxlength="15" autocomplete="off" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" name="last_name" id="last_name" required>
                                    <div class="invalid-feedback">
                                        Please provide a Last Name.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">Phone No. <span style="font-size:17px; margin-top: 2rem; color:red; opacity:0.8;">*</span></span></label>
                                    <div class="input-group flex-nowrap">
                                        <span class="input-group-text" id="addon-wrapping">+63</span>
                                        <input type="text" value="<?php echo $_SESSION['phone_number'] ?>" class="form-control numbers" inputmode="numeric" autocapitalize="off" autocomplete="off" name="phone_number" id="phone_number" minlength="10" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required placeholder="eg. 9776621929">
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="primary" name="btn-get-ticket" id="btn-get-ticket" onclick="return IsEmpty(); sexEmpty();">Get Ticket</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Pre-Registration Modal -->
    <footer>
        <h1 class="credit"> <?php echo $config->getSystemCopyright() ?></h1>
    </footer>

    <script src="src/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="src/js/landing-page.js"></script>
    <script src="src/node_modules/sweetalert/dist/sweetalert.min.js"></script>

    <?php
    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
    ?>
        <script>
            swal({
                title: "<?php echo $_SESSION['status_title']; ?>",
                text: "<?php echo $_SESSION['status']; ?>",
                icon: "<?php echo $_SESSION['status_code']; ?>",
                button: false,
                timer: <?php echo $_SESSION['status_timer']; ?>,
            });
        </script>
    <?php
        unset($_SESSION['status']);
    }
    ?>
</body>

</html>