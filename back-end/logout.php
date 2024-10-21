<?php
require 'classes/db.php';
require 'classes/Auth.php';

$auth = new Auth($conn);
$auth->logout();
header("location: ../front-end/index.html");
exit();
?>