<?php
include_once 'header.php';

$department_id = $_GET['id'];

$stmt = $user->runQuery("SELECT * FROM department WHERE id=:id");
$stmt->execute(array(":id"=>$department_id));
$department_data = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include_once '../../configuration/header.php';
    ?>
	<title>Edit Department</title>
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
                            Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            onclick="history.back()"></button>
                    </div>
                    <div class="modal-body">
                        <section class="data-form-modals">
                            <div class="registration">
                                <?php
                                // department data
                                $department_name = $department_data['department'];
                                $department_logo = $department_data['department_logo'];
                                ?>
                                <form action="controller/department-controller.php?id=<?php echo $department_id ?>" method="POST"
                                    class="row gx-5 needs-validation" enctype="multipart/form-data" name="form" onsubmit="return validate()"
                                    novalidate style="overflow: hidden;">
                                    <div class="row gx-5 needs-validation">

                                        <div class="col-md-12">
                                            <label for="department" class="form-label">Department Name<span> *</span></label>
                                            <input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control"
                                                autocapitalize="on" value="<?php echo $department_name ?>" autocomplete="off" name="department"
                                                id="department" required>
                                            <div class="invalid-feedback">
                                                Please provide a Department Name.
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="department_logo" class="form-label">Department Logo</label>
                                            <input type="file" class="form-control" name="department_logo" id="department_logo" style="height: 33px;">
                                            <div class="invalid-feedback">
                                                Please provide a Logo.
                                            </div>
                                        </div>
                                        <?php if (!empty($department_logo)) { ?>
                                                <img src="../../src/img/<?php echo $department_logo ?>" alt="Department Logo" style="width: 100px;">
                                            <?php } ?>
                                    </div>
                                    <div class="addBtn">
                                        <button type="submit" class="btn-dark" name="btn-edit-department" id="btn-add"
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