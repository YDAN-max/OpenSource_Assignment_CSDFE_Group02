<?php
// logout.php
require_once 'auth.php';
$auth->logout();
header('Location: login.php');
exit();
?>