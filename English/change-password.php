<?php session_start(); ini_set('display_errors', 
1); ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
// ثبت خطاها در فایل لاگ
error_log("Accessing change-password.php");
// بررسی لاگین بودن کاربر
if (!isset($_SESSION['user_id'])) { 
    error_log("User not logged in - redirecting 
    to index.php"); header('Location: 
    /index.php'); exit();
}
// متغیرهای خطا و موفقیت
$success_message = ''; $error_message = ''; 
$debug_info = ''; try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
        error_log("Processing POST request");
        
        // بررسی وجود فیلدها
        $current_password = 
        $_POST['current_password'] ?? ''; 
        $new_password = $_POST['new_password'] ?? 
        ''; $confirm_password = 
        $_POST['confirm_password'] ?? '';
        
        // اعتبارسنجی ورودی‌ها
        if (empty($current_password) || 
        empty($new_password) || 
        empty($confirm_password)) {
            throw new Exception('All fields are 
            required.');
        }
        
        if ($new_password !== $confirm_password) 
        {
            throw new Exception('New passwords do 
            not match.');
        }
        
        if (strlen($new_password) < 8) { throw 
            new Exception('Password must be at 
            least 8 characters long.');
        }
        
        // اینجا کد تغییر رمز عبور در دیتابیس را 
        // قرار دهید ...
        
        $success_message = 'Password changed 
        successfully!'; error_log("Password 
        changed successfully for user ID: " . 
        $_SESSION['user_id']);
    }
} catch (Exception $e) {
    $error_message = $e->getMessage(); 
    error_log("Error in change-password.php: " . 
    $e->getMessage()); $debug_info = "Error 
    occurred at: " . date('Y-m-d H:i:s');
}
?> <!DOCTYPE html> <html lang="en"> <head> <meta 
    charset="UTF-8"> <meta name="viewport" 
    content="width=device-width, 
    initial-scale=1.0"> <title>Change 
    Password</title> <style>
        /* استایل مشابه با فرم تغییر پسورد */ 
    </style>
</head> <body> <div class="container"> <h2>Change 
        Password</h2>
        
        <?php if ($success_message): ?> <div 
            class="message success"><?php echo 
            htmlspecialchars($success_message); 
            ?></div>
        <?php endif; ?> <?php if 
        ($error_message): ?>
            <div class="message error"><?php echo 
            htmlspecialchars($error_message); 
            ?></div>
        <?php endif; ?> <?php if ($debug_info): 
        ?>
            <div class="debug"><?php echo 
            htmlspecialchars($debug_info); 
            ?></div>
        <?php endif; ?> <form method="POST" 
        onsubmit="return validateForm()">
            <div class="form-group"> <label 
                for="current_password">Current 
                Password</label> <input 
                type="password" 
                id="current_password" 
                name="current_password" required>
            </div> <div class="form-group"> 
                <label for="new_password">New 
                Password</label> <input 
                type="password" id="new_password" 
                name="new_password" required>
            </div> <div class="form-group"> 
                <label 
                for="confirm_password">Confirm 
                New Password</label> <input 
                type="password" 
                id="confirm_password" 
                name="confirm_password" required>
            </div> <button type="submit">Change 
            Password</button>
        </form> <a href="/English/index.php" 
        class="back-link">Back to Dashboard</a>
    </div> <script> function validateForm() { var 
        newPass = 
        document.getElementById('new_password').value; 
        var confirmPass = 
        document.getElementById('confirm_password').value;
        
        if (newPass.length < 8) { alert('Password 
            must be at least 8 characters long'); 
            return false;
        }
        
        if (newPass !== confirmPass) { alert('New 
            passwords do not match'); return 
            false;
        }
        
        return true;
    }
    </script> </body>
</html>
