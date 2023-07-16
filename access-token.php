<?php
include_once __DIR__ . '/src/api/api.php';
include_once 'dashboard/user/authentication/user-signin.php';
include_once 'configuration/settings-configuration.php';

$config = new SystemConfig();


if ($_SESSION['access-token'] == NULL) {
    header('Location: ./');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="src/img/<?php echo $config->getSystemLogo() ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="src/css/landing-page.css?v=<?php echo time(); ?>">
    <title>Access Token</title>

</head>

<body>
<style>
    section {
        padding-top: .1rem;

    }

    @media (max-width:1200px) {
        section {
            min-height: 100vh;
            padding: 0 10%;
            padding-top: none;
            padding-bottom: none;


        }
    }
</style>
    <!-- Live queue Modal -->
    <section class="home" id="homes">
        <div class="content">
            <h3>Enter Access Token</h3>
            <p>Please enter the access token that has been provided to you by your department chairperson.</p>
            <div class="verify">
                <form action="dashboard/student/controller/student-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                    <div class="row gx-5 needs-validation">

                        <div class="col-md-12">
                            <input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" maxlength="10" autocomplete="off" name="access_token" id="access_token" required>
                        </div>

                    </div>
                    <div class="addBtn">
                        <button type="submit" class="btn btn-dark" name="btn-access-token" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Submit</button>
                    </div>
                    <a href="./" style="color: #000; text-decoration: none; font-weight: 700;">Back<img src="src/img/caret-right-fill.svg" alt="close-btn" width="13" height="13"></a>
                </form>
            </div>
        </div>&nbsp;&nbsp;

        <div class="image">
            <img src="src/img/access-token.svg" alt="access-token">
        </div>
    </section>
    <script src="src/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="src/js/landing-page.js"></script>
    <script src="src/node_modules/sweetalert/dist/sweetalert.min.js"></script>

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