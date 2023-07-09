<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php
	include_once '../../configuration/header.php';
	?>
	<title>Events</title>
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
				<a href="events">
					<i class='bx bxs-calendar'></i>
					<span class="text">Course Events</span>
				</a>
			</li>
			<li>
				<a href="access-tokens">
					<i class='bx bxs-key'></i>
					<span class="text">Access Tokens</span>
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
					<h1>Events</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Events</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-book'></i> List of Course Events</h3>
					</div> <!-- BODY -->
					<section class="data-table">
						<div class="info-data">
							<div class="searchBx">
								<input type="text" id="search-input" placeholder="Search Course . . . . . ." class="search">
								<button class="searchBtn" type="button" onclick="searchCourseEvents()"><i class="bx bx-search icon"></i></button>
							</div>
							<?php
							$stmt = $user->runQuery("SELECT * FROM course WHERE department_id=:department_id");
							$stmt->execute(array(":department_id" => $user_department));
							$course_data = $stmt->fetch(PDO::FETCH_ASSOC);
							$course_id = $course_data['id'];

							$pdoQuery = "SELECT * FROM course WHERE department_id=:department_id";
							$pdoResult0 = $pdoConnect->prepare($pdoQuery);
							$pdoResult0->execute(array(":department_id" => $user_department));

							while ($course_data = $pdoResult0->fetch(PDO::FETCH_ASSOC)) {
								$course_id = $course_data['id'];

								$pdoQuery = "SELECT * FROM course_event WHERE status = :status AND course_id = :course_id ORDER BY id DESC";
								$pdoResult = $pdoConnect->prepare($pdoQuery);
								$pdoResult->execute(array(":status" => "active", ":course_id" => $course_id));
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
							<?php
								}
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