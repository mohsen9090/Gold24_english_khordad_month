<?php session_start(); $captcha = 
substr(str_shuffle("23456789ABCDEFGHJKLMNPQRSTUVWXYZ"), 
0, 6); $_SESSION['captcha'] = $captcha; echo 
$captcha;
?>
