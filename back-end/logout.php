<?php
require 'classes/db.php';
require 'classes/Auth.php';

$auth = new Auth($conn);
$auth->logout();
header("location: login.php");
exit();
?>