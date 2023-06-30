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
			<span class="text">DOMINICAN<br><p>COLLEGE OF TARLAC</p></span>
		</a>
		<ul class="side-menu top">
			<li>
				<a href="./">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li  class="active">
				<a href="events">
					<i class='bx bxs-calendar' ></i>
					<span class="text">Events</span>
				</a>
			</li>
			<li>
				<a href="events-logs">
					<i class='bx bx-calendar-event'></i>
					<span class="text">Events logs</span>
				</a>
			</li>
			<li>
				<a href="access-token">
                    <i class='bx bxs-key' ></i>
					<span class="text">Access Token</span>
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
					<i class='bx bxs-graduation' ></i>
					<span class="text">Year Level</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu top">
			<li>
				<a href="settings">
					<i class='bx bxs-cog' ></i>
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
				<a href="authentication/admin-signout" class="btn-signout">
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
					<h1>Events</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="home">Home</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Events</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="modal-button">
				<button type="button" data-bs-toggle="modal" data-bs-target="#classModal"  class="btn-dark"><i class='bx bxs-plus-circle'></i> Add Course Event</button>
			</div>
            <div class="info-data">

				<?php

					$pdoQuery = "SELECT * FROM course_event WHERE status = :status";
					$pdoResult = $pdoConnect->prepare($pdoQuery);
					$pdoResult->execute(array
					( 
						":status"	=> "active"
					));	
					if($pdoResult->rowCount() >= 1)
					{	
					
						while($course_event=$pdoResult->fetch(PDO::FETCH_ASSOC)){
							extract($course_event);
				?>

						<div class="card">
							<div class="head">
								<div class="body" onclick="location.href='events?course_id=<?php echo $course_event['course_id'] ?>&year_level_id=<?php echo $course_event['year_level_id'] ?>'">
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
										$department_data= $pdoResult3->fetch(PDO::FETCH_ASSOC);
									?>
									<img src="../../src/img/<?php echo $department_data['department_logo']; ?>" alt="department_logo"> 
									<h2>
										<?php echo $course_data['course']; ?>
										<br>
										<?php
											$year_level_id = $course_event['year_level_id'];
											$pdoQuery = "SELECT * FROM year_level WHERE id = :id";
											$pdoResult4 = $pdoConnect->prepare($pdoQuery);
											$pdoExec = $pdoResult4->execute(array(":id" => $year_level_id));
											$year_level_data= $pdoResult4->fetch(PDO::FETCH_ASSOC);
										?>
										<?php echo $year_level_data['year_level']; ?>
										<br>
										
										<label><?php echo $department_data['department'] ?></label>
									</h2>
								</div>
								<a href="controller/course-event-controller.php?Id=<?php echo $course_event['id'] ?>" class="delete-baby"><i class='bx bxs-trash icon'></i></a>
							</div>
						</div>				

				<?php
						}
					}
					else{
				?>
					<h1 class="no-data">No Baby's Found</h1>
				<?php
					}
				?>
			</div>
		</main>
		<!-- MODALS -->
		<div class="class-modal">
			<div class="modal fade" id="classModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
					<div class="header"></div>
						<div class="modal-header">
							<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-graduation' ></i> Add Course Event</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
						<section class="data-form-modals">
							<div class="registration">
								<form action="controller/user-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
									<div class="row gx-5 needs-validation">

                                        <div class="col-md-6">
											<label for="first_name" class="form-label">First Name<span> *</span></label>
											<input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on"  autocomplete="off" name="first_name" id="first_name" required>
											<div class="invalid-feedback">
											Please provide a First Name.
											</div>
										</div>


										<div class="col-md-6">
											<label for="middle_name" class="form-label">Middle Name</label>
											<input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on"  autocomplete="off" name="middle_name" id="middle_name">
										</div>


										<div class="col-md-6">
											<label for="last_name" class="form-label">Last Name<span> *</span></label>
											<input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on"  autocomplete="off" name="last_name" id="last_name" required>
											<div class="invalid-feedback">
											Please provide a Last Name.
											</div>
										</div>

                                        <div class="col-md-6" >
                                            <label for="phone_number" class="form-label">Phone Number<span> *</span></label>
                                            <div class="input-group flex-nowrap">
                                            <span class="input-group-text" id="addon-wrapping">+63</span>
                                            <input type="text" class="form-control numbers"  autocapitalize="off" inputmode="numeric" autocomplete="off" name="phone_number" id="phone_number" required minlength="10" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  placeholder="10-digit number">
                                            </div>
                                        </div>

										<div class="col-md-12">
											<label for="email" class="form-label">Email<span> *</span></label>
											<input type="email" class="form-control" autocapitalize="on"  autocomplete="off" name="email" id="email" required>
											<div class="invalid-feedback">
											Please provide a Email.
											</div>
										</div>

									</div>

									<div class="addBtn">
										<button type="submit" class="btn-dark" name="btn-add-sub-admin" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Add</button>
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