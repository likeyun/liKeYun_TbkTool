<?php
session_start();
unset($_SESSION["tbktools.admin"]);
header('Location:../admin/Login.php');
?>