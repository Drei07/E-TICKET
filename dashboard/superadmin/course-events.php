<?php
include_once 'header.php';
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
			<li>
				<a href="events">
					<i class='bx bxs-calendar'></i>
					<span class="text">Events</span>
				</a>
			</li>
            <li class="active">
				<a href="course-events">
					<i class='bx bxs-calendar'></i>
					<span class="text">Course Events</span>
				</a>
			</li>
			<li>
				<a href="admin">
                    <i class='bx bxs-user-account'></i>
					<span class="text">Admin</span>
				</a>
			</li>
			<li>
				<a href="sub-admin">
					<i class='bx bxs-user-plus'></i>
					<span class="text">Sub-admin</span>
				</a>
			</li>
			<li>
				<a href="department">
					<i class='bx bxs-buildings'></i>
					<span class="text">Department</span>
				</a>
			</li>
			<li>
				<a href="course">
					<i class='bx bxs-book-alt'></i>
					<span class="text">Course</span>
				</a>
			</li>
			<li>
				<a href="year-level">
					<i class='bx bxs-graduation'></i>
					<span class="text">Year Level</span>
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
				<a href="settings">
					<i class='bx bxs-cog'></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="audit-trail">
					<i class='bx bxl-blogger'></i>
					<span class="text">Audit Trail</span>
				</a>
			</li>
			<li>
				<a href="authentication/superadmin-signout" class="btn-signout">
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
					<input type="search" placeholder="Search...">
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
							<a href="">Course Events</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="modal-button">
				<button type="button" data-bs-toggle="modal" data-bs-target="#classModal" class="btn-dark"><i class='bx bxs-plus-circle'></i> Add Course Event</button>
			</div>
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-book'></i> List of Course Events</h3>
					</div>
					<button type="button" onclick="location.href='archives/course-events'" class="archives btn-dark"><i class='bx bxs-archive'></i> Archives</button>
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
							$pdoResult->execute(array(":status" => "active"));
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
												<img src="../../src/img/<?php echo $department_data['department_logo']; ?>" alt="department_logo">
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
											<a href="controller/course-event-controller.php?id=<?php echo $course_event['id'] ?>&delete_course_event" class="delete"><i class='bx bxs-trash icon-2'></i></a>
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
		<!-- MODALS -->
		<div class="class-modal">
			<div class="modal fade" id="classModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
						<div class="header"></div>
						<div class="modal-header">
							<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-calendar'></i> Add Course Event</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<section class="data-form-modals">
								<div class="registration">
									<form action="controller/course-event-controller?" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
										<div class="row gx-5 needs-validation">

											<div class="col-md-12">
												<label for="course" class="form-label">Course / Program<span> *</span></label>
												<select type="text" class="form-select form-control" name="course" id="course" required>
													<option selected disabled value="">Select Course</option>
													<?php
													$pdoQuery = "SELECT * FROM course WHERE status = :status";
													$pdoResult2 = $pdoConnect->prepare($pdoQuery);
													$pdoResult2->execute(array(":status" => "active"));

													while ($course_data = $pdoResult2->fetch(PDO::FETCH_ASSOC)) {
													?>
														<option value="<?php echo $course_data['id']; ?> "><?php echo $course_data['course'];  ?></option>
													<?php
													}
													?>
												</select>
												<div class="invalid-feedback">
													Please select a Course.
												</div>
											</div>

											<div class="col-md-12">
												<label for="year_level" class="form-label">Year Level<span> *</span></label>
												<select type="text" class="form-select form-control" name="year_level" id="year_level" required>
													<option selected disabled value="">Select Year Level</option>
													<?php
													$pdoQuery = "SELECT * FROM year_level WHERE status = :status ";
													$pdoResult = $pdoConnect->prepare($pdoQuery);
													$pdoResult->execute(array(":status" => "active"));

													while ($year_level_data = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
													?>
														<option value="<?php echo $year_level_data['id']; ?> "><?php echo $year_level_data['year_level'];  ?></option>
													<?php
													}
													?>
												</select>
												<div class="invalid-feedback">
													Please select a Year Level.
												</div>
											</div>


										</div>

										<div class="addBtn">
											<button type="submit" class="btn-dark" name="btn-add-course-event" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Add</button>
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
		function setSessionValues(courseId, yearLevelId) {
			fetch('course-events-list.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: 'course_id=' + encodeURIComponent(courseId) + '&year_level_id=' + encodeURIComponent(yearLevelId),
				})
				.then(response => {
					window.location.href = 'course-events-list';
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