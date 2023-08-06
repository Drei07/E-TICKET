

<?php
	try {

		// localhost
		$pdoConnect = new PDO("mysql:host=localhost;dbname=tarlac", "root", "");

		// Live
		// $pdoConnect = new PDO("mysql:host=localhost;dbname=u297724503_ecket", "u297724503_ecket", "E-cket@2023");
		$pdoConnect->setAttribute(PDO:: ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);

	}
	catch (PDOException $exc){
		echo $exc -> getMessage();
	}
    catch (PDOException $exc){
        echo $exc -> getMessage();
    exit();
    }
?>