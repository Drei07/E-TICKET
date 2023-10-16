<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php
	include_once '../../configuration/header.php';
	?>
	<title>Department</title>
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
			<li>
				<a href="course-events">
					<i class='bx bxs-calendar'></i>
					<span class="text">Course Events</span>
				</a>
			</li>
			<li class="active">
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
				<a href="sub-admin">
					<i class='bx bxs-user-plus'></i>
					<span class="text">User Account</span>
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
				<a href="authentication/admin-signout" class="btn-signout">
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
					<h1>Department</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Department</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="modal-button">
				<button type="button" data-bs-toggle="modal" data-bs-target="#classModal" class="btn-dark"><i class='bx bxs-plus-circle'></i> Add Department</button>
			</div>
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-buildings'></i> List of Department</h3>
					</div>
					<button type="button" onclick="location.href='archives/department'" class="archives btn-dark"><i class='bx bxs-archive'></i> Archives</button>
					<!-- BODY -->
					<section class="data-table">
						<div class="searchBx">
							<input type="input" placeholder="Search Department . . . . . ." class="search" name="search_box" id="search_box"><button class="searchBtn"><i class="bx bx-search icon"></i></button>
						</div>

						<div class="table">
							<div id="dynamic_content">
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
							<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-buildings'></i> Add Department</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
						</div>
						<div class="modal-body">
							<section class="data-form-modals">
								<div class="registration">
									<form action="controller/department-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" enctype="multipart/form-data" novalidate style="overflow: hidden;">
										<div class="row gx-5 needs-validation">

											<div class="col-md-12">
												<label for="department" class="form-label">Department Name<span> *</span></label>
												<input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" autocomplete="off" name="department" id="department" required>
												<div class="invalid-feedback">
													Please provide a Department Name.
												</div>
											</div>


											<div class="col-md-12">
												<label for="department_logo" class="form-label">Department Logo<span> *</span></label>
												<input type="file" class="form-control" name="department_logo" id="department_logo" style="height: 33px ;" required  onchange="previewImage(event)">
												<div class="invalid-feedback">
													Please provide a Logo.
												</div>
											</div>

											<div class="col-md-12">
												<label for="event_poster" class="form-label">Preview</label>
												<img id="poster-preview" style="max-width: 50%; margin-top: 10px; display: none;">
											</div>

										</div>

										<div class="addBtn">
											<button type="submit" class="btn-dark" name="btn-add-department" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Add</button>
										</div>
									</form>
								</div>
							</section>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<?php
	include_once '../../configuration/footer.php';
	?>

	<script>
		//live search---------------------------------------------------------------------------------------//
		$(document).ready(function() {

			load_data(1);

			function load_data(page, query = '') {
				$.ajax({
					url: "tables/department-table.php",
					method: "POST",
					data: {
						page: page,
						query: query
					},
					success: function(data) {
						$('#dynamic_content').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				load_data(page, query);
			});

			$('#search_box').keyup(function() {
				var query = $('#search_box').val();
				load_data(1, query);
			});

		});
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