<?php
include_once 'header.php';

$year_level_id = $_GET['id'];

$stmt = $user->runQuery("SELECT * FROM year_level WHERE id=:id");
$stmt->execute(array(":id"=>$year_level_id));
$year_level_data = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include_once '../../configuration/header.php';
    ?>
	<title>Edit Year Level</title>
</head>
<body>

<div class="class-modal">
        <div class="modal fade" id="editModal" aria-labelledby="classModalLabel" aria-hidden="true"
            data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="header"></div>
                    <div class="modal-header">
                        <h5 class="modal-title" id="classModalLabel"><i class='bx bxs-edit'></i> Edit
                            Year Level</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            onclick="history.back()"></button>
                    </div>
                    <div class="modal-body">
                        <section class="data-form-modals">
                            <div class="registration">
                                <?php
                                // year level data
                                    $year_level_name        = $year_level_data['year_level'];
                                ?>
                                <form action="controller/year-level-controller.php?id=<?php echo $year_level_id ?>" method="POST"
                                    class="row gx-5 needs-validation" name="form" onsubmit="return validate()"
                                    novalidate style="overflow: hidden;">
                                    <div class="row gx-5 needs-validation">

                                        <div class="col-md-12">
											<label for="first_name" class="form-label">Year Level Name<span> *</span></label>
											<input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" value="<?php echo $year_level_name ?>"  autocomplete="off" name="year_level" id="year_level" required>
											<div class="invalid-feedback">
											Please provide a Year Level Name.
											</div>
										</div>


                                    </div>

                                    <div class="addBtn">
                                        <button type="submit" class="btn-dark" name="btn-edit-year-level" id="btn-add"
                                            onclick="return IsEmpty(); sexEmpty();">Update</button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>  

    <?php
    include_once '../../configuration/footer.php';
    ?>
    <script>
    //Load Modal
    $(window).on('load', function() {
        $('#editModal').modal('show');
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