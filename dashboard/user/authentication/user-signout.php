<?php
require_once 'user-class.php';
$user = new SUB_ADMIN();

if(!$user->isUserLoggedIn())
{
 $user->redirect('../../../');
}

if($user->isUserLoggedIn()!="")
{
 $user->logout();
 $user->redirect('../../../');
}
?>