<?php
include_once __DIR__ . '/src/api/api.php';
include_once 'dashboard/user/authentication/user-signin.php';
include_once 'configuration/settings-configuration.php';

$config = new SystemConfig();


if ($_SESSION['eventId'] === NULL) {
    header('Location: ./');
    exit;
}

$event_id = $_SESSION['eventId'];

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
</style>

<body>
    <!-- Live queue Modal -->

    <section class="scanner" id="homes">
        <div class="content">
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
        let isProcessingScan = false;

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

        function handleScanResult(resultText) {
        // Perform any processing needed with the scan result here
        // For example, you can display the result in an element on the page
        document.getElementById('scan').value = resultText;
        document.getElementById('scanForm').submit();
    }

    function processScanResult(result, err) {
        if (result) {
            document.querySelectorAll('#qr_reader__scan_region > div').forEach((div) => {
                div.classList.add('success');
            });

            if (result.text) {
                handleScanResult(result.text); // Call the function to handle the scan result
            }
        }
        if (err && !(err instanceof ZXing.NotFoundException)) {
            document.getElementById('result').textContent = err;
        }

        // Reset the flag after processing the scan
        isProcessingScan = false;
    }

    function startScanning() {
        if (isProcessingScan) {
            // A scan is already in progress, ignore the current call
            return;
        }

        isProcessingScan = true; // Set the flag to indicate a scan is in progress

        setTimeout(() => {
            const videoElement = document.getElementById('video');
            const videoConstraints = {
                deviceId: {
                    exact: selectedDeviceId
                },
                advanced: [{
                    autoFocus: 'continuous'
                }]
            };

            codeReader.decodeFromConstraints({
                video: videoConstraints
            }, videoElement, processScanResult); // Pass the processScanResult function as the callback

        }, 1500);
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
                    const sourceSelect = document.getElementById('sourceSelect')
                    selectedDeviceId = videoInputDevices[0].deviceId
                    if (videoInputDevices.length >= 1) {
                        videoInputDevices.forEach((element) => {
                            const sourceOption = document.createElement('option')
                            sourceOption.text = element.label
                            sourceOption.value = element.deviceId
                            sourceSelect.appendChild(sourceOption)
                        })

                        sourceSelect.onchange = () => {
                            selectedDeviceId = sourceSelect.value;
                        };

                        const sourceSelectPanel = document.getElementById('sourceSelectPanel')
                        sourceSelectPanel.style.display = 'block'
                    }

                    // Check if back camera exists
                    const backCamera = videoInputDevices.find(device => device.label.toLowerCase().includes('back'))
                    if (backCamera) {
                        selectedDeviceId = backCamera.deviceId
                    }

                    // Start the camera automatically
                    if (scanning) {
                        startScanning();
                    }
                })
                .catch((err) => {
                    console.error(err)
                })
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