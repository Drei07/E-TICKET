<?php
include_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Retrieve the values from the POST request
	$courseId = isset($_POST['course_id']) ? $_POST['course_id'] : '';
	$yearLevelId = isset($_POST['year_level_id']) ? $_POST['year_level_id'] : '';

	// Store the values in session variables
	$_SESSION['sub_admin_course_id'] = $courseId;
	$_SESSION['sub_admin_year_level_id'] = $yearLevelId;
}

// Retrieve the values from session variables
$courseId = isset($_SESSION['sub_admin_course_id']) ? $_SESSION['sub_admin_course_id'] : '';
$yearLevelId = isset($_SESSION['sub_admin_year_level_id']) ? $_SESSION['sub_admin_year_level_id'] : '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php
	include_once '../../configuration/header.php';
	?>
	<title>Course Events</title>
</head>

<body>

	<!-- Loader -->
	<div class="loader"></div>

	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="" class="brand">
			<img src="../../src/img/<?php echo $config->getSystemLogo() ?>" alt="logo">
			<span class="text">DOMINICAN<br>
				<p>COLLEGE OF TARLAC</p>
			</span>
		</a>
		<ul class="side-menu top">
			<li>
				<a href="./">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li class="active">
				<a href="course-events">
					<i class='bx bxs-calendar'></i>
					<span class="text">Course Events</span>
				</a>
			</li>
			<li>
				<a href="access-tokens">
					<i class='bx bxs-key' ></i>
					<span class="text">Access Tokens</span>
				</a>
			</li>
			<li>
				<a href="pdf-files">
					<i class='bx bxs-file-pdf'></i>
					<span class="text">PDF Files</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu top">
			<li>
				<a href="authentication/user-signout" class="btn-signout">
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
				<img src="../../src/img/<?php echo $user_profile ?>">
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Course Events</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a class="active" href="course-events">Course Events</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Events List</a>
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
							<img src="../../src/img/<?php echo $department_data['department_logo']; ?>" alt="department_logo">
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
					</div>
				</div>

			</div>
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-calendar'></i> Mandatory Events</h3>
					</div>
					<button type="button" data-bs-toggle="modal" data-bs-target="#classModal" class="archives btn-warning"><i class='bx bxs-key' ></i> Generate Access Token</button><br><br>
					<button type="button" class="archives btn-primary"><a href="controller/access-token-controller.php?course_id=<?php echo $courseId ?>&year_level_id=<?php echo $yearLevelId ?>&print_access_tokens-mandatory=1" class="print" style="color: #FFFF;"><i class='bx bxs-printer'></i> Print Access Token</a></button><br><br>
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
								":status"			=> "active",
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
									<li onclick="setSessionValues(<?php echo $event_data['id'] ?>, <?php echo $mandatory ?> )">

										<img src="../../src/img/<?php echo $event_data['event_poster'] ?>" alt="">
										<h4><?php echo $event_data['event_name'] ?></h4>
										<p>Event: Price <?php echo $event_data['event_price'] ?></p>
										<p>Event Date: <?php echo date('m/d/y', strtotime($event_data['event_date'])); ?></p>
										<button type="button" onclick="setSessionValues(<?php echo $event_data['id'] ?>,<?php echo $mandatory ?> )" class="more btn-warning">More Info</button>
									</li>
							<?php
								}
							}
							?>

							<li data-bs-toggle="modal" data-bs-target="#eventModal">
								<i class='bx bxs-calendar-plus'></i>
							</li>
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
								":status"			=> "active",
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
									<li onclick="setSessionValues(<?php echo $event_data['id'] ?>,<?php echo $optional ?>)">

										<img src="../../src/img/<?php echo $event_data['event_poster'] ?>" alt="">
										<h4><?php echo $event_data['event_name'] ?></h4>
										<p>Event: Price <?php echo $event_data['event_price'] ?></p>
										<p>Event Date: <?php echo date('m/d/y', strtotime($event_data['event_date'])); ?></p>
										<button type="button" onclick="setSessionValues(<?php echo $event_data['id'] ?>,<?php echo $optional ?> )" class="more btn-warning">More Info</button>

									</li>
							<?php
								}
							}
							?>

							<li data-bs-toggle="modal" data-bs-target="#eventModal">
								<i class='bx bxs-calendar-plus'></i>
							</li>
						</ul>
					</section>
				</div>
			</div>
		</main>

		<!-- EDIT MODAL -->
		<div class="class-modal">
			<div class="modal fade" id="classModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
						<div class="header"></div>
						<div class="modal-header">
							<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-key'></i> Generate Access Token</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<section class="data-form-modals">
								<div class="registration">
									<form action="controller/access-token-controller.php?user_id=<?php echo $user_id ?>&course_id=<?php echo $courseId ?>&year_level_id=<?php echo $yearLevelId ?>" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
										<div class="row gx-5 needs-validation">

											<div class="col-md-12">
												<label for="access_token" class="form-label">Input numbers of token will generated<span> *</span></label>
												<input type="numbers" onkeyup="this.value = this.value.toUpperCase();" class="form-control numbers" inputmode="numeric" autocapitalize="on" autocomplete="off" name="access_token" id="access_token" required>
												<div class="invalid-feedback">
													Please provide a Number.
												</div>
											</div>
										</div>

										<div class="addBtn">
											<button type="submit" class="btn-dark" name="btn-add-access-token-mandatory" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Generate</button>
										</div>
									</form>
								</div>
							</section>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<?php
	include_once '../../configuration/footer.php';
	?>

	<script>


		function setSessionValues(eventId, eventType) {
			fetch('events-details.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: 'event_id=' + encodeURIComponent(eventId) + '&event_type=' + encodeURIComponent(eventType),
				})
				.then(response => {
					window.location.href = 'events-details';
				})
				.catch(error => {
					console.error('Error:', error);
				});
		}


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