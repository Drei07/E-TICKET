<?php
include_once '../../database/dbconfig2.php';
require_once 'authentication/superadmin-class.php';
include_once '../../configuration/settings-configuration.php';

// instances of the classes
$config = new SystemConfig();
$user = new SUPERADMIN();

if(!$user->isUserLoggedIn())
{
 $user->redirect('../../private/superadmin/');
}

// retrieve user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
$stmt->execute(array(":uid"=>$_SESSION['superadminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// retrieve profile user and full name
$user_id                = $user_data['id'];
$user_profile           = $user_data['profile'];
$user_fname             = $user_data['first_name'];
$user_mname             = $user_data['middle_name'];
$user_lname             = $user_data['last_name'];
$user_fullname          = $user_data['last_name'] . ", " . $user_data['first_name'];
$user_sex               = $user_data['sex'];
$user_birth_date        = $user_data['date_of_birth'];
$user_age               = $user_data['age'];
$user_civil_status      = $user_data['civil_status'];
$user_phone_number      = $user_data['phone_number'];
$user_email             = $user_data['email'];
$user_last_update       = $user_data['updated_at'];
