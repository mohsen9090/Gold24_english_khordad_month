<?php session_start();
// تنظیم نمایش ارورها برای دیباگ (بعدا غیر فعال 
// کن)
error_reporting(E_ALL); ini_set('display_errors', 
1);
// بررسی ورود کاربر
if (!isset($_SESSION['user_id'])) { 
    http_response_code(401); exit();
}
// اگر کاربر لاگین کرده، اجازه دسترسی بده
http_response_code(200);
?>
