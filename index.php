<?php
include_once __DIR__ . '/src/api/api.php';
include_once 'dashboard/user/authentication/user-signin.php';
include_once 'configuration/settings-configuration.php';

$config = new SystemConfig();

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
    <title>E-CKET</title>

</head>

<body>

    <header>

        <a href="" id="logo" class="delete"><img src="src/img/E-CKET.png" alt="E-CKET"></a>
        <input type="checkbox" id="menu-bar">
        <label for="menu-bar" class="fas fa-bars"></label>

        <nav class="navbar">
            <a href="" id="navbar">Home</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="events" id="navbar">Events</a></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="" data-bs-toggle="modal" data-bs-target="#pre-registration" id="navbar">About us</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </nav>
    </header>
    <!-- Live queue Modal -->
    <section class="home" id="homes">
        <div class="content">
            <h3>Welcome to E-CKET<span> Your Easy Ticket Solution</span></h3>
            <p>We prioritize your convenience. Our user-friendly platform allows you to search, select, and secure your tickets effortlessly. With just a few clicks, you can secure your spot and get ready for an unforgettable experience.</p>
            <a href="" class="btn" data-bs-toggle="modal" data-bs-target="#pre-registration">Pre-Registration</a>
        </div>&nbsp;&nbsp;

        <div class="image">
            <img src="src/img/Events-pana.svg" alt="events">
        </div>
    </section>
    <section class="covid" id="cov">

        <h1 class="heading" data-aos-once="false"> Join the <span>E-CKET</span> Community Today, Your Trusted Event Companion </h1>

        <div class="column" id="column1">

            <div class="image">
                <img src="src/img/access-token.svg" alt="the-best-time-to-visit.sv">
            </div>

            <div class="content">
                <div class="ic">
                    <object data="src/img/icons8-access-50.png"></object>
                    <h3>Access Token</h3>
                </div>
                <p data-aos="fade-in">Once you have made the payment, you will receive an access token from your department chairperson.</p>
            </div>

        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <div class="column" id="column2">

            <div class="image">
                <img src="src/img/choose-event.svg" alt="remote-sign-in.svg">
            </div>

            <div class="content">
                <div class="ic">
                    <object data="src/img/icons8-events-58.png"></object>
                    <h3>Choose Events</h3>
                </div>
                <p data-aos="fade-in">Now, take a moment to explore and choose from a wide range of exciting events available. With so many options to consider, you can carefully select the events that align with your interests and preferences.</p>
            </div>


        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <div class="column" id="column3">

            <div class="image">
                <img src="src/img/get-ticket.svg" alt="img-covid-vaccine-pass">
            </div>

            <div class="content">
                <div class="ic">
                    <object data="src/img/icons8-ticket-64.png"></object>
                    <h3>Ticket with Barcode</h3>
                </div>
                <p data-aos="fade-in">After selecting event, you'll receive a ticket with event details and an attached e-ticket containing a unique barcode through gmail. This barcode serves as your entry pass, which you can present digitally or in print at the event venue. With our secure and convenient e-ticketing system, you can enjoy a seamless and hassle-free experience at your chosen event.</p>
            </div>

        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <div class="column" id="column4">

            <div class="image">
                <img src="src/img/Barcode-scanning.svg" alt="chat-customers.svg">
            </div>

            <div class="content">
                <div class="ic">
                    <object data="src/img/icons8-barcode-reader-50.png"></object>
                    <h3>Present the Barcode</h3>
                </div>
                <p data-aos="fade-in">
                    To access the event, simply present your e-ticket with the barcode at the entrance. Our scanning system will quickly validate your ticket and grant you admission. Say goodbye to paper tickets and enjoy a seamless entry process with our digital barcode system. It's quick, efficient, and ensures a smooth check-in experience for all attendees</p>
            </div>

        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <div class="column" id="column5">

            <div class="image">
                <img src="src/img/enjoy-event.svg" alt="chat-customers.svg">
            </div>

            <div class="content">
                <div class="ic">
                    <object data="src/img/icons8-party-64.png"></object>
                    <h3>Enjoy the Event</h3>
                </div>
                <p data-aos="fade-in">Embrace the moment and fully immerse yourself in the event. Enjoy the live experience, connect with others, and create lasting memories. Let go of all worries and fully engage with the music, performances, or activities. Make the most of this opportunity to have fun, be present, and cherish the magical atmosphere.</p>
            </div>

        </div>
    </section>

    <!-- modals -->
    <!-- Pre-Registration Modal -->
    <div class="pre-registration-modal">
        <div class="modal fade" id="pre-registration" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <a href="" id="logo"><img src="src/img/E-CKET.png" alt="E-CKET" style="width: 100px;"></a>
                        <p> Streamline Your Ticketing Experience</p>
                        <a href="" class="close" data-bs-dismiss="modal" aria-label="Close"><img src="src/img/caret-right-fill.svg" alt="close-btn" width="24" height="24"></a>
                    </div>
                    <div class="modal-body">
                        <div class="form-alert">
                            <span id="message"></span>
                        </div>
                        <form action="dashboard/student/controller/student-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                            <div class="row gx-5 needs-validation">
                                <input type="hidden" id="g-token" name="g-token">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">First Name <span style="font-size:17px; margin-top: 2rem; color:red; opacity:0.8;">*</span></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" maxlength="15" autocomplete="off" name="first_name" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" id="first_name" required>
                                    <div class="invalid-feedback">
                                        Please provide a First Name.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" maxlength="15" autocomplete="off" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" name="middle_name" id="middle_name">
                                    <div class="invalid-feedback">
                                        Please provide a Middle Name.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name <span style="font-size:17px; margin-top: 2rem; color:red; opacity:0.8;">*</span></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on" maxlength="15" autocomplete="off" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" name="last_name" id="last_name" required>
                                    <div class="invalid-feedback">
                                        Please provide a Last Name.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">Phone No. <span style="font-size:17px; margin-top: 2rem; color:red; opacity:0.8;">*</span></span></label>
                                    <div class="input-group flex-nowrap">
                                        <span class="input-group-text" id="addon-wrapping">+63</span>
                                        <input type="text" class="form-control numbers" inputmode="numeric" autocapitalize="off" autocomplete="off" name="phone_number" id="phone_number" minlength="10" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required placeholder="eg. 9776621929">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="email" class="form-label">Email <span style="font-size:17px; margin-top: 2rem; color:red; opacity:0.8;">*</span><span style="font-size: 10px; color: red;">(valid email)</span></label>
                                    <input type="email" class="form-control" autocapitalize="off" autocomplete="off" name="email" id="email" required placeholder="Ex. juan@email.com">
                                    <div class="invalid-feedback">
                                        Please provide a valid Email.
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="primary" name="btn-pre-register" id="btn-pre-register" onclick="return IsEmpty(); sexEmpty();">Submit</button>
                            </div>
                        </form>
                        <div class="terms">
                            <p>By pre-registering, you will agree to the following <a href="" data-bs-toggle="modal" data-bs-target="#terms">Terms & Conditions</a> of E-CKET.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Pre-Registration Modal -->

    <footer>

        <div class="pre-registration">
            <h3>PRE-REGISTERED NOW<br>
                <p>Streamline Your Ticketing Experience</p>
            </h3>
            <a href="#" data-bs-toggle="modal" data-bs-target="#pre-registration" class="btn">Get Ticket</a>

        </div>
        <h1 class="credit"> <?php echo $config->getSystemCopyright() ?></h1>
    </footer>
    <button id="scrollToTop" onclick="scrolltop();"><img src="src/img/icons8-slide-up-30.png"></button>

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