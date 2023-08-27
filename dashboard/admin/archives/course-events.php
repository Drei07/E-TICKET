<?php
include_once '../../../database/dbconfig2.php';
require_once '../authentication/admin-class.php';
include_once '../../../configuration/settings-configuration.php';


// instances of the classes
$config = new SystemConfig();
$user = new ADMIN();

if (!$user->isUserLoggedIn()) {
	$user->redirect('../../../../private/admin/');
}

// retrieve user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
$stmt->execute(array(":uid" => $_SESSION['adminSession']));
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
			<span class="text">DOMINICAN<br>
				<p>COLLEGE OF TARLAC</p>
			</span>
		</a>
		<ul class="side-menu top">
			<li>
				<a href="../">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="../events">
					<i class='bx bxs-calendar'></i>
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
			<li>
				<a href="../course">
					<i class='bx bxs-book-alt'></i>
					<span class="text">Course</span>
				</a>
			</li>
			<li>
				<a href="../year-level">
					<i class='bx bxs-graduation'></i>
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
					<i class='bx bxs-cog'></i>
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
					<i class='bx bxs-log-out-circle'></i>
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
			<i class='bx bx-menu'></i>
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
					<h1>Course Events Archives</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href=".././">Home</a>
						</li>
						<li>|</li>
						<li>
							<a class="active" href="../course-events">Course Events</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Archives</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-book'></i> List of Archives Course Events</h3>
					</div>
					<!-- BODY -->
					<section class="data-table">
						<div class="info-data">
							<div class="searchBx">
								<input type="text" id="search-input" placeholder="Search Course . . . . . ." class="search">
								<button class="searchBtn" type="button" onclick="searchCourseEvents()"><i class="bx bx-search icon"></i></button>
							</div>
							<?php
							$pdoQuery = "SELECT * FROM course_event WHERE status = :status ORDER BY id DESC";
							$pdoResult = $pdoConnect->prepare($pdoQuery);
							$pdoResult->execute(array(":status" => "disabled"));
							if ($pdoResult->rowCount() >= 1) {
								while ($course_event = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
									extract($course_event);
							?>
									<div class="card">
										<div class="head2">
											<div class="body" onclick="setSessionValues(<?php echo $course_event['course_id'] ?>, <?php echo $course_event['year_level_id'] ?>)">
												<?php
												//course data
												$course_id = $course_event['course_id'];
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
													//year level data
													$year_level_id = $course_event['year_level_id'];
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
											<a href="../controller/course-event-controller.php?id=<?php echo $course_event['id'] ?>&activate_course_event" class="activate"><i class='bx bxs-check-shield icon-3'></i></a>
										</div>
									</div>
								<?php
								}
							} else {
								?>
								<h1 class="no-data">No Course Found</h1>
							<?php
							}
							?>
						</div>
					</section>
				</div>
			</div>
		</main>
		<!-- MAIN -->
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
		function setSessionValues(courseId, yearLevelId) {
			fetch('../course-events-list.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: 'course_id=' + encodeURIComponent(courseId) + '&year_level_id=' + encodeURIComponent(yearLevelId),
				})
				.then(response => {
					window.location.href = '../course-events-list';
				})
				.catch(error => {
					console.error('Error:', error);
				});
		}

		function searchCourseEvents() {
			var searchInput = document.getElementById('search-input').value.trim();
			var cards = document.querySelectorAll('.info-data .card');

			cards.forEach(function(card) {
				var courseName = card.querySelector('h2').innerText;

				if (courseName.toLowerCase().includes(searchInput.toLowerCase())) {
					card.style.display = 'block';
				} else {
					card.style.display = 'none';
				}
			});
		}
	</script>

	<!-- SWEET ALERT -->
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