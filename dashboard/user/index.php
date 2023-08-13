<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php
	include_once '../../configuration/header.php';
	?>
	<title>Dashboard</title>
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
			<li class="active">
				<a href="./">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="course-events">
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
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Dashboard</a>
						</li>
					</ul>
				</div>
			</div>

			<ul class="dashboard_data">
				<li>
					<i class='bx bx-key'></i>
					<span class="text">
						<?php
						$pdoQuery = "SELECT * FROM access_token WHERE user_id = :user_id";
						$pdoResult1 = $pdoConnect->prepare($pdoQuery);
						$pdoResult1->execute(array(":user_id" => $_SESSION['sub_adminSession']));

						$count = $pdoResult1->rowCount();

						echo
						"
									<h3>$count</h3>
								";
						?>
						<p>Access Tokens</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-file-pdf'></i>
					<span class="text">
						<?php
						$pdoQuery = "SELECT * FROM pdf_file WHERE user_id = :user_id";
						$pdoResult1 = $pdoConnect->prepare($pdoQuery);
						$pdoResult1->execute(array(":user_id" => $_SESSION['sub_adminSession']));

						$count = $pdoResult1->rowCount();

						echo
						"
									<h3>$count</h3>
								";
						?>
						<p>PDF Files</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-calendar-event'></i>
					<span class="text">
						<?php
						$stmt = $user->runQuery("SELECT * FROM course WHERE department_id=:department_id");
						$stmt->execute(array(":department_id" => $user_department));
						$pdoResult0 = $stmt->fetchAll(PDO::FETCH_ASSOC);

						$total_count = 0; // Initialize the total count variable

						foreach ($pdoResult0 as $course_data) {
							$course_id = $course_data['id'];
							$pdoQuery = "SELECT * FROM course_event WHERE status = :status AND course_id = :course_id ORDER BY id DESC";
							$pdoResult = $pdoConnect->prepare($pdoQuery);
							$pdoResult->execute(array(":status" => "active", ":course_id" => $course_id));

							$count = $pdoResult->rowCount(); // Get the count of rows for the current course
							$total_count += $count; // Add the current count to the total count
						}

						echo "<h3>$total_count</h3>"; // Output the total count
						?>

						<p>Course Events</p>
					</span>
				</li>
			</ul>

		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<?php
	include_once '../../configuration/footer.php';
	?>

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