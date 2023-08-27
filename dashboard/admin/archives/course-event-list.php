<?php
include_once '../../../database/dbconfig2.php';
require_once '../authentication/admin-class.php';
include_once '../../../configuration/settings-configuration.php';


// instances of the classes
$config = new SystemConfig();
$user = new ADMIN();

if(!$user->isUserLoggedIn())
{
 $user->redirect('../../../../private/admin/');
}

// retrieve user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
$stmt->execute(array(":uid"=>$_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// retrieve profile user and full name
$user_id                = $user_data['id'];
$user_profile           = $user_data['profile'];
$user_fname             = $user_data['first_name'];
$user_mname             = $user_data['middle_name'];
$user_lname             = $user_data['last_name'];
$user_fullname          = $user_data['last_name'] . ", " . $user_data['first_name'];
$user_sex               = $user_data['sex'];
$user_birth_date        = $user_data['date_of_birth'];
$user_age               = $user_data['age'];
$user_civil_status      = $user_data['civil_status'];
$user_phone_number      = $user_data['phone_number'];
$user_email             = $user_data['email'];
$user_last_update       = $user_data['updated_at'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Retrieve the values from the POST request
	$courseId = isset($_POST['course_id']) ? $_POST['course_id'] : '';
	$yearLevelId = isset($_POST['year_level_id']) ? $_POST['year_level_id'] : '';

	// Store the values in session variables
	$_SESSION['course_id'] = $courseId;
	$_SESSION['year_level_id'] = $yearLevelId;
}

// Retrieve the values from session variables
$courseId = isset($_SESSION['course_id']) ? $_SESSION['course_id'] : '';
$yearLevelId = isset($_SESSION['year_level_id']) ? $_SESSION['year_level_id'] : '';


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="../../../src/img/<?php echo $config->getSystemLogo() ?>">
	<link rel="stylesheet" href="../../../src/node_modules/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../../src/node_modules/boxicons/css/boxicons.min.css">
	<link rel="stylesheet" href="../../../src/node_modules/aos/dist/aos.css">
    <link rel="stylesheet" href="../../../src/css/admin.css?v=<?php echo time(); ?>">
	<title>Course Events Archives</title>
</head>
<body>

<!-- Loader -->
<div class="loader"></div>

	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="" class="brand">
			<img src="../../../src/img/<?php echo $config->getSystemLogo() ?>" alt="logo">
			<span class="text">DOMINICAN<br><p>COLLEGE OF TARLAC</p></span>
		</a>
		<ul class="side-menu top">
			<li>
				<a href="../">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="../events">
					<i class='bx bxs-calendar' ></i>
					<span class="text">Events</span>
				</a>
			</li>
            <li class="active">
				<a href="../course-events">
					<i class='bx bxs-calendar'></i>
					<span class="text">Course Events</span>
				</a>
			</li>
			<li>
				<a href="../department">
				<i class='bx bxs-buildings'></i>
				<span class="text">Department</span>
				</a>
			</li>
			<li >
				<a href="../course">
					<i class='bx bxs-book-alt'></i>
					<span class="text">Course</span>
				</a>
			</li>
			<li>
				<a href="../year-level">
					<i class='bx bxs-graduation' ></i>
					<span class="text">Year Level</span>
				</a>
			</li>
			<li>
				<a href="../sub-admin">
					<i class='bx bxs-user-plus'></i>
					<span class="text">User Account</span>
				</a>
			</li>
			<li>
				<a href="../pdf-files">
					<i class='bx bxs-file-pdf'></i>
					<span class="text">PDF Files</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu top">
			<li>
				<a href="../settings">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="../audit-trail">
					<i class='bx bxl-blogger'></i>
					<span class="text">Audit Trail</span>
				</a>
			</li>
			<li>
				<a href="../authentication/admin-signout" class="btn-signout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Signout</span>
				</a>
			</li>
			
		</ul>
	</section>
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<form action="#">
			<div class="form-input">
					<button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
				</div>
			</form>
			<div class="username">
                <span>Hello, <label for=""><?php echo $user_fname ?></label></span>
            </div>
			<a href="profile" class="profile" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Profile">
				<img src="../../../src/img/<?php echo $user_profile ?>">
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
                    <h1>Course Events List Archives</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href=".././">Home</a>
						</li>
						<li>|</li>
						<li>
							<a class="active" href="../course-event-list">Course Events</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Archives</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="info-data">
				<?php

				$pdoQuery = "SELECT * FROM course_event WHERE course_id = :course_id AND year_level_id = :year_level_id";
				$pdoResult = $pdoConnect->prepare($pdoQuery);
				$pdoExec = $pdoResult->execute(array(":course_id" => $courseId, ":year_level_id" => $yearLevelId));
				$course_event_data = $pdoResult->fetch(PDO::FETCH_ASSOC);
				?>
				<div class="card">
					<div class="head2">
						<div class="body">
							<?php
							//course data
							$course_id = $course_event_data["course_id"];
							$pdoQuery = "SELECT * FROM course WHERE id = :id";
							$pdoResult2 = $pdoConnect->prepare($pdoQuery);
							$pdoExec = $pdoResult2->execute(array(":id" => $course_id));
							$course_data = $pdoResult2->fetch(PDO::FETCH_ASSOC);

							//department data
							$department_id = $course_data['department_id'];
							$pdoQuery = "SELECT * FROM department WHERE id = :id";
							$pdoResult3 = $pdoConnect->prepare($pdoQuery);
							$pdoExec = $pdoResult3->execute(array(":id" => $department_id));
							$department_data = $pdoResult3->fetch(PDO::FETCH_ASSOC);
							?>
							<img src="../../../src/img/<?php echo $department_data['department_logo']; ?>" alt="department_logo">
							<h2>
								<?php echo $course_data['course']; ?>
								<br>
								<?php
								//year level
								$year_level_id = $course_event_data['year_level_id'];
								$pdoQuery = "SELECT * FROM year_level WHERE id = :id";
								$pdoResult4 = $pdoConnect->prepare($pdoQuery);
								$pdoExec = $pdoResult4->execute(array(":id" => $year_level_id));
								$year_level_data = $pdoResult4->fetch(PDO::FETCH_ASSOC);
								?>
								<?php echo $year_level_data['year_level']; ?>
								<br>

								<label><?php echo $department_data['department'] ?></label>
							</h2>
						</div>
						<a data-bs-toggle="modal" data-bs-target="#editModal" style="cursor: pointer;"><i class='bx bxs-edit icon'></i></a>
					</div>
				</div>

			</div>
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-calendar'></i> Mandatory Events</h3>
					</div>
					<!-- BODY -->
					<section class="data-table">
						<div class="searchBx">
							<input type="text" id="search-input-mandatory" placeholder="Search Events . . . . . ." class="search">
							<button class="searchBtn" type="button" onclick="searchMandatoryEvents()"><i class="bx bx-search icon"></i></button>
						</div>
						<ul class="box-info" id="mandatory-events">
							<?php
							$pdoQuery = "SELECT * FROM event_per_course WHERE course_id = :course_id AND year_level_id = :year_level_id AND event_type = :event_type AND event_status = :event_status AND status = :status ORDER BY id DESC";
							$pdoResult5 = $pdoConnect->prepare($pdoQuery);
							$pdoResult5->execute(array(
								":course_id" 		=> $courseId,
								":year_level_id" 	=> $yearLevelId,
								":event_type"		=> 1,
								":status"			=> "disabled",
								":event_status"		=> "active"

							));
							if ($pdoResult5->rowCount() >= 1) {

								while ($event_per_course_data = $pdoResult5->fetch(PDO::FETCH_ASSOC)) {
									extract($event_per_course_data);

									$event_id = $event_per_course_data['event_id'];
									$pdoQuery = "SELECT * FROM events WHERE id = :id";
									$pdoResult2 = $pdoConnect->prepare($pdoQuery);
									$pdoExec = $pdoResult2->execute(array(":id" => $event_id));
									$event_data = $pdoResult2->fetch(PDO::FETCH_ASSOC);

							?>
									<li onclick="setSessionValues(<?php echo $event_data['id'] ?>)">

										<img src="../../../src/img/<?php echo $event_data['event_poster'] ?>" alt="">
										<h4><?php echo $event_data['event_name'] ?></h4>
										<p>Event: Price <?php echo $event_data['event_price'] ?></p>
										<p>Event Date: <?php echo date('m/d/y', strtotime($event_data['event_date'])); ?></p>
										<button type="button" class="more btn-success"><a href="../controller/event-controller.php?id=<?php echo $event_per_course_data['id'] ?>&activate_event_per_course=1" class="activate" style="color: #FFFF;">Activate</a></button>

									</li>
							<?php
								}
							}
							?>
						</ul>
					</section>
				</div>
			</div>

			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-calendar'></i> Optional Events</h3>
					</div>
					<!-- BODY -->
					<section class="data-table">
						<div class="searchBx">
							<input type="text" id="search-input-optional" placeholder="Search Events . . . . . ." class="search">
							<button class="searchBtn" type="button" onclick="searchOptionalEvents()"><i class="bx bx-search icon"></i></button>
						</div>
						<ul class="box-info" id="optional-events">
						<?php
							$pdoQuery = "SELECT * FROM event_per_course WHERE course_id = :course_id AND year_level_id = :year_level_id AND event_type = :event_type AND event_status = :event_status AND status = :status ORDER BY id DESC";
							$pdoResult5 = $pdoConnect->prepare($pdoQuery);
							$pdoResult5->execute(array(
								":course_id" 		=> $courseId,
								":year_level_id" 	=> $yearLevelId,
								":event_type"		=> 2,
								":status"			=> "disabled",
								":event_status"		=> "active"

							));
							if ($pdoResult5->rowCount() >= 1) {

								while ($event_per_course_data = $pdoResult5->fetch(PDO::FETCH_ASSOC)) {
									extract($event_per_course_data);

									$event_id = $event_per_course_data['event_id'];
									$pdoQuery = "SELECT * FROM events WHERE id = :id";
									$pdoResult2 = $pdoConnect->prepare($pdoQuery);
									$pdoExec = $pdoResult2->execute(array(":id" => $event_id));
									$event_data = $pdoResult2->fetch(PDO::FETCH_ASSOC);

							?>
									<li onclick="setSessionValues(<?php echo $event_data['id'] ?>)">

										<img src="../../../src/img/<?php echo $event_data['event_poster'] ?>" alt="">
										<h4><?php echo $event_data['event_name'] ?></h4>
										<p>Event: Price <?php echo $event_data['event_price'] ?></p>
										<p>Event Date: <?php echo date('m/d/y', strtotime($event_data['event_date'])); ?></p>
										<button type="button" class="more btn-success"><a href="../controller/event-controller.php?id=<?php echo $event_per_course_data['id'] ?>&activate_event_per_course=1" class="activate" style="color: #FFFF;">Activate</a></button>

									</li>
							<?php
								}
							}
							?>
						</ul>
					</section>
				</div>
			</div>
		</main>
		<!-- MAIN -->
        		<!-- EDIT MODAL -->
		<div class="class-modal">
			<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
						<div class="header"></div>
						<div class="modal-header">
							<h5 class="modal-title" id="editModalLabel"><i class='bx bxs-calendar'></i> Edit Course Event</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<section class="data-form-modals">
								<div class="registration">
									<form action="../controller/course-event-controller.php?id=<?php echo $course_event_data['id'] ?>" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
										<div class="row gx-5 needs-validation">

											<div class="col-md-12">
												<label for="year_level" class="form-label">Year Level<span> *</span></label>
												<select type="text" class="form-select form-control" name="year_level" id="year_level" required>
													<option selected value="<?php echo $year_level_data['id'] ?>"><?php echo $year_level_data['year_level'] ?></option>
													<?php
													$pdoQuery = "SELECT * FROM year_level ";
													$pdoResult6 = $pdoConnect->prepare($pdoQuery);
													$pdoResult6->execute();

													while ($year_level_data2 = $pdoResult6->fetch(PDO::FETCH_ASSOC)) {
													?>
														<option value="<?php echo $year_level_data2['id']; ?> "><?php echo $year_level_data2['year_level'];  ?></option>
													<?php
													}
													?>
												</select>
												<div class="invalid-feedback">
													Please select a Year Level.
												</div>
											</div>

											<div class="col-md-12">
												<label for="course" class="form-label">Course / Program<span> *</span></label>
												<select type="text" class="form-select form-control" name="course" id="course" required>
													<option selected value="<?php echo $course_data['id'] ?>"><?php echo $course_data['course'] ?></option>
													<?php
													$pdoQuery = "SELECT * FROM course ";
													$pdoResult7 = $pdoConnect->prepare($pdoQuery);
													$pdoResult7->execute();

													while ($course_data2 = $pdoResult7->fetch(PDO::FETCH_ASSOC)) {
													?>
														<option value="<?php echo $course_data2['id']; ?> "><?php echo $course_data2['course'];  ?></option>
													<?php
													}
													?>
												</select>
												<div class="invalid-feedback">
													Please select a Course.
												</div>
											</div>

										</div>

										<div class="addBtn">
											<button type="submit" class="btn-dark" name="btn-edit-course-event" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Update</button>
										</div>
									</form>
								</div>
							</section>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- CONTENT -->

    <script src="../../../src/node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <script src="../../../src/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="../../../src/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../../../src/js/loader.js"></script>
    <script src="../../../src/js/form.js"></script>
    <script src="../../../src/js/tooltip.js"></script>
	<script src="../../../src/js/admin.js"></script>

	<script>
		function searchMandatoryEvents() {
			var searchInput = document.getElementById('search-input-mandatory').value.trim();
			var eventItems = document.querySelectorAll('#mandatory-events li');

			eventItems.forEach(function(item) {
				var eventName = item.querySelector('h4').innerText;

				if (eventName.toLowerCase().includes(searchInput.toLowerCase())) {
					item.style.display = 'block';
				} else {
					item.style.display = 'none';
				}
			});

			var noResultsMsg = document.getElementById('no-results-msg-mandatory');
			if (document.querySelectorAll('#mandatory-events li[style="display: block;"]').length === 0) {
				noResultsMsg.style.display = 'block';
			} else {
				noResultsMsg.style.display = 'none';
			}

			if (searchInput === '') {
				eventItems.forEach(function(item) {
					item.style.display = 'block';
				});
				noResultsMsg.style.display = 'none';
			}
		}

		function searchOptionalEvents() {
			var searchInput = document.getElementById('search-input-optional').value.trim();
			var eventItems = document.querySelectorAll('#optional-events li');

			eventItems.forEach(function(item) {
				var eventName = item.querySelector('h4').innerText;

				if (eventName.toLowerCase().includes(searchInput.toLowerCase())) {
					item.style.display = 'block';
				} else {
					item.style.display = 'none';
				}
			});

			var noResultsMsg = document.getElementById('no-results-msg-optional');
			if (document.querySelectorAll('#optional-events li[style="display: block;"]').length === 0) {
				noResultsMsg.style.display = 'block';
			} else {
				noResultsMsg.style.display = 'none';
			}

			if (searchInput === '') {
				eventItems.forEach(function(item) {
					item.style.display = 'block';
				});
				noResultsMsg.style.display = 'none';
			}
		}
	</script>
		<!-- SWEET ALERT -->
		<?php

		if(isset($_SESSION['status']) && $_SESSION['status'] !='')
		{
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