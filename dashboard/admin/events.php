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
					<span class="text">Events</span>
				</a>
			</li>
			<li>
				<a href="course-events">
					<i class='bx bxs-calendar'></i>
					<span class="text">Course Events</span>
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
			<div class="modal-button">
				<button type="button" data-bs-toggle="modal" data-bs-target="#classModal" class="btn-dark"><i class='bx bxs-plus-circle'></i> Add Events</button>
			</div>
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-calendar'></i>Events List</h3>
					</div>
					<button type="button" onclick="location.href='archives/events'" class="archives btn-dark"><i class='bx bxs-archive'></i> Archives</button>
					<!-- BODY -->
					<section class="data-table">
						<div class="searchBx">
							<input type="text" id="search-input-mandatory" placeholder="Search Events . . . . . ." class="search">
							<button class="searchBtn" type="button" onclick="searchMandatoryEvents()"><i class="bx bx-search icon"></i></button>
						</div>
						<ul class="box-info" id="mandatory-events">
							<?php
							$pdoQuery = "SELECT * FROM events WHERE status = :status ORDER BY id DESC";
							$pdoResult5 = $pdoConnect->prepare($pdoQuery);
							$pdoResult5->execute(array(
								":status"			=> "active"
							));
							if ($pdoResult5->rowCount() >= 1) {

								while ($event_data = $pdoResult5->fetch(PDO::FETCH_ASSOC)) {
									extract($event_data);
							?>
									<li onclick="setSessionValues(<?php echo $event_data['id'] ?>)">

										<img src="../../src/img/<?php echo $event_data['event_poster'] ?>" alt="">
										<h4><?php echo $event_data['event_name'] ?></h4>
										<p>Event Date: <?php echo date('m/d/y', strtotime($event_data['event_date'])); ?></p>
										<button type="button" onclick="setSessionValues(<?php echo $event_data['id'] ?>)" class="more btn-warning">More Info</button>

									</li>
							<?php
								}
							}
							?>

							<li data-bs-toggle="modal" data-bs-target="#classModal">
								<i class='bx bxs-calendar-plus'></i>
							</li>
						</ul>
					</section>
				</div>
			</div>
		</main>
		<div class="class-modal">
			<div class="modal fade" id="classModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
						<div class="header"></div>
						<div class="modal-header">
							<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-calendar'></i> Add Events</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<section class="data-form-modals">
								<div class="registration">
									<form action="controller/event-controller.php" method="POST" class="row gx-5 needs-validation" enctype="multipart/form-data" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
										<div class="row gx-5 needs-validation">

											<div class="col-md-12">
												<label for="event_name" class="form-label">Event Name<span> *</span></label>
												<input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" autocomplete="off" name="event_name" id="event_name" required>
												<div class="invalid-feedback">
													Please provide a Event Name.
												</div>
											</div>

											<div class="col-md-6">
												<label for="event_date" class="form-label">Event Date<span> *</span></label>
												<input type="date" class="form-control" autocomplete="off" name="event_date" id="event_date" required>
												<div class="invalid-feedback">
													Please provide a Event Date.
												</div>
											</div>

											<div class="col-md-6">
												<label for="event_time" class="form-label">Event Time<span> *</span></label>
												<input type="time" class="form-control" autocomplete="off" name="event_time" id="event_time" required>
												<div class="invalid-feedback">
													Please provide a Event Time.
												</div>
											</div>

											<div class="col-md-6">
												<label for="event_venue" class="form-label">Event Venue<span> *</span></label>
												<input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" autocomplete="off" name="event_venue" id="event_venue" required>
												<div class="invalid-feedback">
													Please provide a Event Venue.
												</div>
											</div>

											<!-- please add numbers only -->
											<div class="col-md-6">
												<label for="event_max_guest" class="form-label">Event Max Guest</label>
												<input type="numbers" onkeyup="this.value = this.value.toUpperCase();" class="form-control numbers" inputmode="numeric" autocapitalize="on" autocomplete="off" name="event_max_guest" id="event_max_guest">
												<div class="invalid-feedback">
													Please provide a Event Max Guest.
												</div>
											</div>

											<div class="col-md-6">
												<label for="event_price" class="form-label">Event Price <span> *</span></label>
												<input type="numbers" onkeyup="this.value = this.value.toUpperCase();" class="form-control numbers" inputmode="numeric" autocapitalize="on" autocomplete="off" name="event_price" id="event_price" required>
												<div class="invalid-feedback">
													Please provide a Event Price.
												</div>
											</div>

											<div class="col-md-12">
												<label for="event_rules" class="form-label">Event Rules</label>
												<textarea onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" autocomplete="off" name="event_rules" id="event_rules" rows="4" cols="40"></textarea>
												<div class="invalid-feedback">
													Please provide a Event Rules.
												</div>
											</div>

											<div class="col-md-12">
												<label for="event_poster" class="form-label">Event Poster<span> *</span></label>
												<input type="file" class="form-control" name="event_poster" id="event_poster" style="height: 33px;" required onchange="previewImage(event)">
												<div class="invalid-feedback">
													Please provide an Event Poster.
												</div>
											</div>

											<div class="col-md-12">
												<label for="event_poster" class="form-label">Preview</label>
												<img id="poster-preview" style="max-width: 50%; margin-top: 10px; display: none;">
											</div>

										</div>

										<div class="addBtn">
											<button type="submit" class="btn-dark" name="btn-add-event" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Add</button>
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
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<?php
	include_once '../../configuration/footer.php';
	?>

	<script>

		function setSessionValues(eventId) {
            fetch('events-details.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'event_id=' + encodeURIComponent(eventId),
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