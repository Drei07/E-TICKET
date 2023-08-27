<?php
include_once 'dashboard/user/authentication/user-signin.php';
include_once 'configuration/settings-configuration.php';
require_once __DIR__ . '/database/dbconfig2.php';


$config = new SystemConfig();


if ($_SESSION['eventId'] === NULL) {
    header('Location: ./');
    exit;
}

$event_id = $_SESSION['eventId'];

$pdoQuery = "SELECT * FROM events WHERE id = :id";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoResult->execute(array(":id" => $event_id));
$event_data = $pdoResult->fetch(PDO::FETCH_ASSOC)

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="src/img/<?php echo $config->getSystemLogo() ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="src/css/landing-page.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" rel="preload" as="style" onload="this.rel='stylesheet';this.onload=null" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" rel="preload" as="style" onload="this.rel='stylesheet';this.onload=null" href="https://unpkg.com/normalize.css@8.0.0/normalize.css">
    <title>Barcode Scanner</title>

</head>
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

    .scanner .content {
        display: block;
    }

    .scanner .content.hidden {
        display: none;
    }

    
</style>

<body>
    <!-- Live queue Modal -->

    <section class="scanner" id="homes">
        <div id="scanningIndicator" style="display: none;">
            <img src="src/img/barcode-scanning.gif" alt="Scanning" />
        </div>
        <div class="content">
            <div class="heading">
                <h1><?php echo $event_data['event_name'] ?></h1>
            </div>
            <div id="qr_reader__scan_region">
                <video id="video"></video>
            </div>
            <div id="sourceSelectPanel">
                <select id="sourceSelect"></select>
                <button id="toggleButton" onclick="toggleScanning()" class="btn-danger">Stop Scanning</button>
                <a href="dashboard/user/controller/barcode-scanner-controller.php?signout=1" class="btn-signout">Sign Out</a>
            </div>
            <form action="dashboard/student/controller/scan-barcode-controller.php?event_id=<?php echo $event_id ?>" method="POST" id="scanForm" style="display: none;">
                <input type="text" name="scan" id="scan" class="qrcode">
                <button type="submit" id="submitButton">Submit</button>
            </form>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="src/js/landing-page.js"></script>
    <script src="src/js/form.js"></script>
    <script src="src/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="src/node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>
    <script type="text/javascript">
        let selectedDeviceId;
        let codeReader;
        let scanning = true;

        function toggleScanning() {
            const toggleButton = document.getElementById('toggleButton');
            if (scanning) {
                scanning = false;
                toggleButton.textContent = 'Start Scanning';
                toggleButton.classList.remove('btn-danger');
                toggleButton.classList.add('btn-success');
                stopScanning();
            } else {
                scanning = true;
                toggleButton.textContent = 'Stop Scanning';
                toggleButton.classList.remove('btn-success');
                toggleButton.classList.add('btn-danger');
                startScanning();
            }
        }

        function showScanningIndicator() {
            const scanningIndicator = document.getElementById('scanningIndicator');
            scanningIndicator.style.display = 'block';

            const contentSection = document.querySelector('.scanner .content');
            contentSection.classList.add('hidden');

            // Hide the scanning indicator after 2 seconds
            setTimeout(() => {
                hideScanningIndicator();

                // Show the status message after the scanning indicator is hidden
                <?php
                if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
                ?>
                    swal({
                        title: "<?php echo $_SESSION['status_title']; ?>",
                        text: "<?php echo $_SESSION['status']; ?>",
                        icon: "<?php echo $_SESSION['status_code']; ?>",
                        button: false,
                        timer: <?php echo $_SESSION['status_timer']; ?>,
                    });
                <?php
                    unset($_SESSION['status']);
                }
                ?>
            }, 1300);
        }

        // Function to hide the scanning indicator
        function hideScanningIndicator() {
            const scanningIndicator = document.getElementById('scanningIndicator');
            scanningIndicator.style.display = 'none';

            const contentSection = document.querySelector('.scanner .content');
            contentSection.classList.remove('hidden');
        }


        function startScanning() {
            const videoElement = document.getElementById('video');
            const videoConstraints = {
                deviceId: {
                    exact: selectedDeviceId
                },
                advanced: [{
                    autoFocus: 'continuous'
                }]
            };

            // Disable the form submission button while scanning is in progress
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = true;

            // Wait for 2 seconds before starting the scanning process
            setTimeout(() => {
                codeReader.decodeFromConstraints({
                    video: videoConstraints
                }, videoElement, (result, err) => {
                    // The hideScanningIndicator() function is called automatically
                    // after 2 seconds, so no need to explicitly call it here.

                    if (result) {
                        document.getElementById('scan').value = result.text;
                        document.querySelectorAll('#qr_reader__scan_region > div').forEach((div) => {
                            div.classList.add('success');
                        });

                        // Enable the form submission button after scanning is complete
                        submitButton.disabled = false;

                        // Show the scanning indicator when scanning starts
                        if (result.text) {
                            document.getElementById('scanForm').submit();
                        }
                    }

                    if (err && !(err instanceof ZXing.NotFoundException)) {
                        document.getElementById('result').textContent = err;
                        // Enable the form submission button after scanning is complete
                        submitButton.disabled = false;
                    }
                });
            }, 1300); // 1.3 seconds timeout before starting the scanning process
        }


        function stopScanning() {
            codeReader.reset();
            document.getElementById('scan').value = '';
            document.querySelectorAll('#qr_reader__scan_region > div').forEach((div) => {
                div.classList.remove('success');
            });
        }

        window.addEventListener('load', function() {
            codeReader = new ZXing.BrowserMultiFormatReader();

            codeReader.listVideoInputDevices()
                .then((videoInputDevices) => {
                    const sourceSelect = document.getElementById('sourceSelect');
                    selectedDeviceId = videoInputDevices[0].deviceId;
                    if (videoInputDevices.length >= 1) {
                        videoInputDevices.forEach((element) => {
                            const sourceOption = document.createElement('option');
                            sourceOption.text = element.label;
                            sourceOption.value = element.deviceId;
                            sourceSelect.appendChild(sourceOption);
                        });

                        sourceSelect.onchange = () => {
                            selectedDeviceId = sourceSelect.value;
                        };

                        const sourceSelectPanel = document.getElementById('sourceSelectPanel');
                        sourceSelectPanel.style.display = 'block';
                    }

                    // Check if back camera exists
                    const backCamera = videoInputDevices.find(device => device.label.toLowerCase().includes('back'));
                    if (backCamera) {
                        selectedDeviceId = backCamera.deviceId;
                    }

                    // Start the camera automatically
                    if (scanning) {
                        showScanningIndicator(); // Call this function to display the scanning indicator on page load
                        startScanning();
                    }
                })
                .catch((err) => {
                    console.error(err);
                });
        });
        // Signout
        $('.btn-signout').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href')

            swal({
                    title: "Signout?",
                    text: "Are you sure do you want to signout?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willSignout) => {
                    if (willSignout) {
                        document.location.href = href;
                    }
                });
        })
    </script>
</body>

</html>