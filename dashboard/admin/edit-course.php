<?php
include_once 'header.php';

$course_id = $_GET['id'];

$stmt = $user->runQuery("SELECT * FROM course WHERE id=:id");
$stmt->execute(array(":id"=>$course_id));
$course_data = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include_once '../../configuration/header.php';
    ?>
	<title>Edit Course</title>
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
                            Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            onclick="history.back()"></button>
                    </div>
                    <div class="modal-body">
                        <section class="data-form-modals">
                            <div class="registration">
                                <?php
                                // course data
                                    $department_id         = $course_data['department_id'];
                                    $course_name        = $course_data['course'];

                                    $stmt = $user->runQuery("SELECT * FROM department WHERE id=:id");
                                    $stmt->execute(array(":id"=>$department_id));
                                    $department_data = $stmt->fetch(PDO::FETCH_ASSOC);

                                ?>
                                <form action="controller/course-controller.php?id=<?php echo $course_id ?>" method="POST"
                                    class="row gx-5 needs-validation" name="form" onsubmit="return validate()"
                                    novalidate style="overflow: hidden;">
                                    <div class="row gx-5 needs-validation">

                                        <div class="col-md-12">
											<label for="services" class="form-label">Department<span> *</span></label>
											<select type="text" class="form-select form-control"  name="department" id="department"  required>
                                            <option selected value="<?php echo $department_data['id'] ?>"><?php echo $department_data['department'] ?></option>
												<?php
													$pdoQuery = "SELECT * FROM department ";
													$pdoResult = $pdoConnect->prepare($pdoQuery);
													$pdoResult->execute();
													
														while($department_data=$pdoResult->fetch(PDO::FETCH_ASSOC)){
															?>
															<option value="<?php echo $department_data['id']; ?> " ><?php echo $department_data['department'];  ?></option>
															<?php
														}
												?>
											</select>
											<div class="invalid-feedback">
												Please select a Department.
											</div>
										</div>

                                        <div class="col-md-12">
											<label for="first_name" class="form-label">Course Name<span> *</span></label>
											<input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" value="<?php echo $course_name ?>"  autocomplete="off" name="course_name" id="course_name" required>
											<div class="invalid-feedback">
											Please provide a Course Name.
											</div>
										</div>


                                    </div>

                                    <div class="addBtn">
                                        <button type="submit" class="btn-dark" name="btn-edit-course" id="btn-add"
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