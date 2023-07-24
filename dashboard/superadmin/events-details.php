<?php
include_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Retrieve the values from the POST request
	$eventId = isset($_POST['event_id']) ? $_POST['event_id'] : '';
  
	// Store the values in session variables
	$_SESSION['event_id'] = $eventId;
  }
  
  // Retrieve the values from session variables
  $eventId = isset($_SESSION['event_id']) ? $_SESSION['event_id'] : '';

$stmt = $user->runQuery("SELECT * FROM events WHERE id=:id");
$stmt->execute(array(":id"=>$eventId));
$event_data = $stmt->fetch(PDO::FETCH_ASSOC);


// Fetch data from event_access_key table
$accessKeyStmt = $user->runQuery("SELECT * FROM event_access_key WHERE event_id = :event_id");
$accessKeyStmt->execute(array(":event_id" => $eventId));
$accessKeyData = $accessKeyStmt->fetch(PDO::FETCH_ASSOC);

// access key
$access_key_id = $accessKeyData['id'];
$access_key = $accessKeyData['access_key'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include_once '../../configuration/header.php';
    ?>
	<title>Event Details</title>
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
					<i class='bx bxs-graduation' ></i>
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
				<a href="authentication/superadmin-signout" class="btn-signout">
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
					<h1>Event Details</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a class="active" href="events">Events</a>
						</li>
                        <li>|</li>
                        <li>
							<a href="">Events Details</a>
						</li>
					</ul>
				</div>
			</div>
			<ul class="events-details">
				<li>
					<img src="../../src/img/<?php echo $event_data['event_poster'] ?>" alt="">
					<div class="details">
						<h1><?php echo $event_data['event_name'] ?></h1>
						<p><strong>Event Date / Time:</strong> <?php echo date('m/d/y', strtotime($event_data['event_date'])); ?> - <?php echo date("h:i A", strtotime($event_data['event_time'])); ?></p>
						<p><strong>Event Venue:</strong> <?php echo $event_data['event_venue'] ?></p>
						<p><strong>Event Rules:</strong> <?php echo $event_data['event_rules'] ?></p>
						<p><strong>Event Price:</strong> <?php echo $event_data['event_price'] ?></p>
						<p><strong>Max Guest:</strong> <?php echo $event_data['event_max_guest'] ?></p>
						<?php if ($event_data['status'] == 'active') { ?>
					<div class="action">
						<button type="button" data-bs-toggle="modal" data-bs-target="#access_key" class="btn btn-success"><i class='bx bxs-key'></i> Access key</button>
						<button type="button" data-bs-toggle="modal" data-bs-target="#classModal" class="btn btn-warning"><i class='bx bxs-edit'></i> Edit</button>
						<button type="button" class="btn btn-danger"><a href="controller/event-controller?id=<?php echo $event_data['id'] ?>&delete_event=1" class="delete"><i class='bx bxs-trash'></i> Delete</a></button>
					</div>
				<?php } else if ($event_data['status'] == 'disabled') { ?>
					<div class="action">
						<button type="button" data-bs-toggle="modal" data-bs-target="#access_key" class="btn btn-success"><i class='bx bxs-key'></i> Access key</button>
						<button type="button" data-bs-toggle="modal" data-bs-target="#classModal" class="btn btn-warning"><i class='bx bxs-edit'></i> Edit</button>
						<button type="button" class="btn btn-success" ><a href="controller/event-controller?id=<?php echo $event_data['id'] ?>&activate_event=1" class="activate" ><i class='bx bxs-check-circle'></i> Activate</a></button>
						
					</div>
				<?php } ?>
					</div>
				</li>
			</ul>

			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-user-detail'></i> List of Registered</h3>
					</div>
						<button type="button" onclick="location.href='archives/'" class="archives btn-dark"><i class='bx bxs-archive' ></i> Archives</button>
                    <!-- BODY -->
                    <section class="data-table">
                        <div class="searchBx">
                            <input type="input" placeholder="search . . . . . ." class="search" name="search_box" id="search_box"><button class="searchBtn"><i class="bx bx-search icon"></i></button>
                        </div>

                        <div class="table">
                        <div id="dynamic_content">
                        </div>
                    </section>
				</div>
			</div>
		</main>
		<!-- ACCESS KEY -->
		<div class="class-modal">
			<div class="modal fade" id="access_key" tabindex="-1" aria-labelledby="access_keyLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
						<div class="header"></div>
						<div class="modal-header">
							<h5 class="modal-title" id="access_keyLabel"><i class='bx bxs-key'></i> Access Key</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<section class="data-form-modals">
								<div class="registration">
									<form action="controller/event-controller.php?access_key_id=<?php echo $access_key_id ?>" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
										<div class="row gx-5 needs-validation">

											<div class="col-md-12">
											<input disabled type="text" value="<?php echo $access_key?>" class="form-control">
												<div class="invalid-feedback">
													Please provide a Number.
												</div>
											</div>
										</div>

										<div class="addBtn">
										<?php if ($accessKeyData['status'] == 'active') { ?>
											<button type="submit" class="btn-danger" name="btn_deactivate_access_key" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Disabled</button>
										<?php } else if ($accessKeyData['status'] == 'disabled') { ?>
											<button type="submit" class="btn-success" name="btn_activate_access_key" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Activate</button>
										<?php } ?>

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
		<div class="class-modal">
			<div class="modal fade" id="classModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
						<div class="header"></div>
						<div class="modal-header">
							<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-calendar'></i> Edit Events</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<section class="data-form-modals">
								<div class="registration">
									<form action="controller/event-controller.php?id=<?php echo $event_data['id'] ?>" method="POST" class="row gx-5 needs-validation" enctype="multipart/form-data" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
										<div class="row gx-5 needs-validation">

											<div class="col-md-12">
												<label for="event_name" class="form-label">Event Name<span> *</span></label>
												<input type="text" value="<?php echo $event_data['event_name']?>" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" autocomplete="off" name="event_name" id="event_name" required>
												<div class="invalid-feedback">
													Please provide a Event Name.
												</div>
											</div>

											<div class="col-md-6">
												<label for="event_date" class="form-label">Event Date<span> *</span></label>
												<input type="date" value="<?php echo $event_data['event_date']?>" class="form-control" autocomplete="off" name="event_date" id="event_date" required>
												<div class="invalid-feedback">
													Please provide a Event Date.
												</div>
											</div>

											<div class="col-md-6">
												<label for="event_time" class="form-label">Event Time<span> *</span></label>
												<input type="time" value="<?php echo $event_data['event_time']?>" class="form-control" autocomplete="off" name="event_time" id="event_time" required>
												<div class="invalid-feedback">
													Please provide a Event Time.
												</div>
											</div>

											<div class="col-md-6">
												<label for="event_venue" class="form-label">Event Venue<span> *</span></label>
												<input type="text" value="<?php echo $event_data['event_venue']?>" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" autocomplete="off" name="event_venue" id="event_venue" required>
												<div class="invalid-feedback">
													Please provide a Event Venue.
												</div>
											</div>

											<!-- please add numbers only -->
											<div class="col-md-6">
												<label for="event_max_guest" class="form-label">Event Max Guest</label>
												<input type="numbers" value="<?php echo $event_data['event_max_guest']?>" onkeyup="this.value = this.value.toUpperCase();" class="form-control numbers" inputmode="numeric" autocapitalize="on" autocomplete="off" name="event_max_guest" id="event_max_guest">
												<div class="invalid-feedback">
													Please provide a Event Max Guest.
												</div>
											</div>

											<div class="col-md-6">
												<label for="event_price" class="form-label">Event Price <span> *</span></label>
												<input type="numbers" value="<?php echo $event_data['event_price']?>" onkeyup="this.value = this.value.toUpperCase();" class="form-control numbers" inputmode="numeric" autocapitalize="on" autocomplete="off" name="event_price" id="event_price" required>
												<div class="invalid-feedback">
													Please provide a Event Price.
												</div>
											</div>

											<div class="col-md-6">
												<label for="event_rules" class="form-label">Event Rules</label>
												<textarea value="<?php echo $event_data['event_rules']?>" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" autocomplete="off" name="event_rules" id="event_rules" rows="4" cols="40"><?php echo $event_data['event_rules']?></textarea>
												<div class="invalid-feedback">
													Please provide a Event Rules.
												</div>
											</div>

											<div class="col-md-12">
												<label for="event_poster" class="form-label">Event Poster<span> *</span></label>
												<input type="file"  class="form-control" name="event_poster" id="event_poster" style="height: 33px;">
												<div class="invalid-feedback">
													Please provide an Event Poster.
												</div>
											</div>
											<?php if (!empty($event_data['event_poster'])) { ?>
                                                <img src="../../src/img/<?php echo $event_data['event_poster'] ?>" alt="Event poster" style="width: 50%;">
                                            <?php } ?>

										</div>

										<div class="addBtn">
											<button type="submit" class="btn-dark" name="btn-edit-event" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Update</button>
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