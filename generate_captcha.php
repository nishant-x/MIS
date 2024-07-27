<?php
session_start();

// Generate random captcha string
$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
$_SESSION['captcha'] = $randomString;

echo $randomString;
?>
