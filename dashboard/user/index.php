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
			<span class="text">DOMINICAN<br><p>COLLEGE OF TARLAC</p></span>
		</a>
		<ul class="side-menu top">
			<li  class="active">
				<a href="./">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="events">
					<i class='bx bxs-calendar' ></i>
					<span class="text">Events</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu top">
			<li>
				<a href="authentication/user-signout" class="btn-signout">
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
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
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
					<i class='bx bx-user-circle'></i>
					<span class="text">
					<?php
								$pdoQuery = "SELECT * FROM 	users WHERE user_type = :user_type";
								$pdoResult1 = $pdoConnect->prepare($pdoQuery);
								$pdoResult1->execute(array(":user_type" => 2));

								$count = $pdoResult1->rowCount();

								echo
								"
									<h3>$count</h3>
								";
							?>
						<p>Sub-admin</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-buildings' ></i>
					<span class="text">
							<?php
								$pdoQuery = "SELECT * FROM department";
								$pdoResult1 = $pdoConnect->prepare($pdoQuery);
								$pdoResult1->execute();

								$count = $pdoResult1->rowCount();

								echo
								"
									<h3>$count</h3>
								";
							?>
						<p>Department</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-book' ></i>
					<span class="text">
							<?php
								$pdoQuery = "SELECT * FROM course";
								$pdoResult1 = $pdoConnect->prepare($pdoQuery);
								$pdoResult1->execute();

								$count = $pdoResult1->rowCount();

								echo
								"
									<h3>$count</h3>
								";
							?>
						<p>Course</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-graduation'></i>
					<span class="text">
					<?php
								$pdoQuery = "SELECT * FROM  year_level";
								$pdoResult1 = $pdoConnect->prepare($pdoQuery);
								$pdoResult1->execute();

								$count = $pdoResult1->rowCount();

								echo
								"
									<h3>$count</h3>
								";
							?>
						<p>Year Level</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-calendar' ></i>
					<span class="text">
							<?php
								$pdoQuery = "SELECT * FROM events";
								$pdoResult1 = $pdoConnect->prepare($pdoQuery);
								$pdoResult1->execute();

								$count = $pdoResult1->rowCount();

								echo
								"
									<h3>$count</h3>
								";
							?>
						<p>Events</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-calendar-event'></i>
					<span class="text">
					<?php
								$pdoQuery = "SELECT * FROM  course_event";
								$pdoResult1 = $pdoConnect->prepare($pdoQuery);
								$pdoResult1->execute();

								$count = $pdoResult1->rowCount();

								echo
								"
									<h3>$count</h3>
								";
							?>
						<p>Course Events</p>
					</span>
				</li>
				<li>
					<i class='bx bx-calendar'></i>
					<span class="text">
					<?php
								$pdoQuery = "SELECT * FROM 	events WHERE event_type = :event_type";
								$pdoResult1 = $pdoConnect->prepare($pdoQuery);
								$pdoResult1->execute(array(":event_type" => "MANDATORY"));

								$count = $pdoResult1->rowCount();

								echo
								"
									<h3>$count</h3>
								";
							?>
						<p>Mandatory Events</p>
					</span>
				</li>
				<li>
					<i class='bx bx-calendar'></i>
					<span class="text">
					<?php
								$pdoQuery = "SELECT * FROM 	events WHERE event_type = :event_type";
								$pdoResult1 = $pdoConnect->prepare($pdoQuery);
								$pdoResult1->execute(array(":event_type" => "OPTIONAL"));

								$count = $pdoResult1->rowCount();

								echo
								"
									<h3>$count</h3>
								";
							?>
						<p>Optional Events</p>
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