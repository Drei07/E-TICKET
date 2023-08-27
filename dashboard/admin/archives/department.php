<?php
include_once '../../../database/dbconfig2.php';
require_once '../authentication/admin-class.php';
include_once '../../../configuration/settings-configuration.php';


// instances of the classes
$config = new SystemConfig();
$user = new ADMIN();

if(!$user->isUserLoggedIn())
{
 $user->redirect('../../../../private/admin/');
}

// retrieve user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
$stmt->execute(array(":uid"=>$_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// retrieve profile user and full name
$user_id                = $user_data['id'];
$user_profile           = $user_data['profile'];
$user_fname             = $user_data['first_name'];
$user_mname             = $user_data['middle_name'];
$user_lname             = $user_data['last_name'];
$user_fullname          = $user_data['last_name'] . ", " . $user_data['first_name'];
$user_sex               = $user_data['sex'];
$user_birth_date        = $user_data['date_of_birth'];
$user_age               = $user_data['age'];
$user_civil_status      = $user_data['civil_status'];
$user_phone_number      = $user_data['phone_number'];
$user_email             = $user_data['email'];
$user_last_update       = $user_data['updated_at'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="../../../src/img/<?php echo $config->getSystemLogo() ?>">
	<link rel="stylesheet" href="../../../src/node_modules/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../../src/node_modules/boxicons/css/boxicons.min.css">
	<link rel="stylesheet" href="../../../src/node_modules/aos/dist/aos.css">
    <link rel="stylesheet" href="../../../src/css/admin.css?v=<?php echo time(); ?>">
	<title>Department Archives</title>
</head>
<body>

<!-- Loader -->
<div class="loader"></div>

	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="" class="brand">
			<img src="../../../src/img/<?php echo $config->getSystemLogo() ?>" alt="logo">
			<span class="text">DOMINICAN<br><p>COLLEGE OF TARLAC</p></span>
		</a>
		<ul class="side-menu top">
			<li>
				<a href="../">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="../events">
					<i class='bx bxs-calendar' ></i>
					<span class="text">Events</span>
				</a>
			</li>
			<li>
				<a href="../course-events">
					<i class='bx bxs-calendar'></i>
					<span class="text">Course Events</span>
				</a>
			</li>
			<li   class="active">
				<a href="../department">
				<i class='bx bxs-buildings'></i>
				<span class="text">Department</span>
				</a>
			</li>
			<li>
				<a href="../course">
					<i class='bx bxs-book-alt'></i>
					<span class="text">Course</span>
				</a>
			</li>
			<li>
				<a href="../year-level">
					<i class='bx bxs-graduation' ></i>
					<span class="text">Year Level</span>
				</a>
			</li>
			<li>
				<a href="../sub-admin">
					<i class='bx bxs-user-plus'></i>
					<span class="text">User Account</span>
				</a>
			</li>
			<li>
				<a href="../pdf-files">
					<i class='bx bxs-file-pdf'></i>
					<span class="text">PDF Files</span>
				</a>
			</li>
		</ul>
		
		<ul class="side-menu top">
			<li>
				<a href="../settings">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="../audit-trail">
					<i class='bx bxl-blogger'></i>
					<span class="text">Audit Trail</span>
				</a>
			</li>
			<li>
				<a href="../authentication/admin-signout" class="btn-signout">
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
					<button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
				</div>
			</form>
			<div class="username">
                <span>Hello, <label for=""><?php echo $user_fname ?></label></span>
            </div>
			<a href="profile" class="profile" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Profile">
				<img src="../../../src/img/<?php echo $user_profile ?>">
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Department Archives</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="../">Home</a>
						</li>
						<li>|</li>
						<li>
							<a class="active" href="../department">Department</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Archives</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-buildings' ></i> List of Archives Department</h3>
					</div>
                    <!-- BODY -->
                    <section class="data-table">
                        <div class="searchBx">
                            <input type="input" placeholder="search department . . . . . ." class="search" name="search_box" id="search_box"><button class="searchBtn"><i class="bx bx-search icon"></i></button>
                        </div>

                        <div class="table">
                        <div id="dynamic_content">
                        </div>
                    </section>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

    <script src="../../../src/node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <script src="../../../src/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="../../../src/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../../../src/js/loader.js"></script>
    <script src="../../../src/js/form.js"></script>
    <script src="../../../src/js/tooltip.js"></script>
	<script src="../../../src/js/admin.js"></script>

	<script>

	//live search---------------------------------------------------------------------------------------//
	$(document).ready(function(){

	load_data(1);

	function load_data(page, query = '')
	{
	$.ajax({
		url:"tables/department-table.php",
		method:"POST",
		data:{page:page, query:query},
		success:function(data)
		{
		$('#dynamic_content').html(data);
		}
	});
	}

	$(document).on('click', '.page-link', function(){
	var page = $(this).data('page_number');
	var query = $('#search_box').val();
	load_data(page, query);
	});

	$('#search_box').keyup(function(){
	var query = $('#search_box').val();
	load_data(1, query);
	});

	});

	</script>
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