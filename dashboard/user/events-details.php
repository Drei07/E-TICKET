<?php
include_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Retrieve the values from the POST request
	$eventId = isset($_POST['event_id']) ? $_POST['event_id'] : '';
  
	// Store the values in session variables
	$_SESSION['sub_admin_event_id'] = $eventId;
  }
  
  // Retrieve the values from session variables
  $eventId = isset($_SESSION['sub_admin_event_id']) ? $_SESSION['sub_admin_event_id'] : '';

$stmt = $user->runQuery("SELECT * FROM events WHERE id=:id");
$stmt->execute(array(":id"=>$eventId));
$event_data = $stmt->fetch(PDO::FETCH_ASSOC);


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
					<h1>Event Details</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a class="active" href="events">Course Events</a>
						</li>
						<li>|</li>
						<li>
							<a class="active" href="course-events">Events</a>
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
						<div class="action">
						<button type="button" data-bs-toggle="modal" data-bs-target="#classModal" class="btn2 btn-warning"><i class='bx bxs-key'></i> Generate Access Token</button>
						<button type="button" onclick="location.href='controller/access-token-controller.php?event_id=<?php echo $eventId ?>&print-access-tokens=1'" class="btn2 btn-warning"><i class='bx bxs-printer'></i> Print Access Token</button>
					</div>
					</div>
				</li>
			</ul>

			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-user-detail'></i> List of Registered</h3>
					</div>
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

		<div class="class-modal">
			<div class="modal fade" id="classModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
					<div class="header"></div>
						<div class="modal-header">
							<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-key' ></i> Generate Access Token</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
						<section class="data-form-modals">
							<div class="registration">
								<form action="controller/access-token-controller.php?user_id=<?php echo $user_id ?>&event_id=<?php echo $eventId ?>" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
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
										<button type="submit" class="btn-dark" name="btn-add-access-token" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Generate</button>
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