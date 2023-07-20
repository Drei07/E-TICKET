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
    <link rel="stylesheet" rel="preload" as="style" onload="this.rel='stylesheet';this.onload=null" href="https://unpkg.com/milligram@1.3.0/dist/milligram.min.css">
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
                <div id="qr_shaded_region">
                    <!-- top -->
                    <div id="top_left"></div>
                    <div id="top_right"></div>
                    <!-- bottom -->
                    <div id="bottom_left"></div>
                    <div id="bottom_right"></div>
                    <!-- top -->
                    <div id="top_left_vertical"></div>
                    <div id="top_right_vertical"></div>
                    <!-- bottom -->
                    <div id="bottom_left_vertical"></div>
                    <div id="bottom_right_vertical"></div>
                </div>
                <video id="video"></video>
            </div>
            <div id="sourceSelectPanel">
                <select id="sourceSelect"></select>
                <button id="toggleButton" onclick="toggleScanning()" class="btn-danger">Stop Scanning</button>
            </div>

            <form action="dashboard/student/controller/scan-barcode.php?event_id=<?php echo $event_id?>" method="POST" id="scanForm" style="display: none;">
                <input type="text" name="scan" id="scan" class="qrcode">  
            </form>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="src/js/landing-page.js"></script>
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

        function startScanning() {
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
                }, videoElement, (result, err) => {
                    if (result) {
                        document.getElementById('scan').value = result.text;
                        document.querySelectorAll('#qr_shaded_region > div').forEach((div) => {
                            div.classList.add('success');
                        });

                        if (result.text) {
                document.getElementById('scanForm').submit();
            }
                    }
                    if (err && !(err instanceof ZXing.NotFoundException)) {
                        document.getElementById('result').textContent = err;
                    }
                });
            }, 500);
        }

        function stopScanning() {
            codeReader.reset();
            document.getElementById('scan').value = '';
            document.querySelectorAll('#qr_shaded_region > div').forEach((div) => {
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