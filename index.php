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

        <a href="" id="qtracker"><img src="src/img/E-CKET.png" alt="E-CKET"></a>
        <input type="checkbox" id="menu-bar">
        <label for="menu-bar" class="fas fa-bars"></label>

        <nav class="navbar">
            <a href="" id="navbar">Home</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="" data-bs-toggle="modal" data-bs-target="#pre-registration" id="navbar">Events</a></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="" data-bs-toggle="modal" data-bs-target="#live" id="navbar">About us</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="private/sub-admin/" id="login">Sign in</a>
        </nav>
    </header>
    <!-- Live queue Modal -->
    <section class="home" id="homes">
        <div class="content">
            <h3>Welcome to E-CKET<span> Your Easy Ticket Solution</span></h3>
            <p>We prioritize your convenience. Our user-friendly platform allows you to search, select, and secure your tickets effortlessly. With just a few clicks, you can secure your spot and get ready for an unforgettable experience.</p>
            <a href="" class="btn">Get Ticket</a>
        </div>&nbsp;&nbsp;

        <div class="image">
            <img src="src/img/Events-pana.svg" alt="Qminder-Queueing-Solution">
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
</body>

</html>