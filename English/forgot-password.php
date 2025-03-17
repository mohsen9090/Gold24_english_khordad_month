<?php require 'vendor/autoload.php'; // اطمینان 
حاصل کنید که مسیر صحیح است use 
PHPMailer\PHPMailer\PHPMailer; use 
PHPMailer\PHPMailer\Exception; $errorMessage = 
''; $successMessage = ''; if 
($_SERVER['REQUEST_METHOD'] == 'POST') {
    // دریافت ایمیل از فرم
    $email = $_POST['email'];
    
    // ایجاد یک نمونه از PHPMailer
    $mail = new PHPMailer(true);
    
    try {
        // تنظیمات SMTP
        $mail->isSMTP(); $mail->Host = 
        'smtp.gmail.com'; $mail->SMTPAuth = true; 
        $mail->Username = 
        'mohsenbanihashemi4@gmail.com'; // ایمیل 
        خود را وارد کنید $mail->Password = 'vtjc 
        wfmg blae qwuw'; // رمز عبور اپلیکیشن 
        $mail->SMTPSecure = 
        PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587; $mail->CharSet = 
        'UTF-8';
        // تنظیمات گیرندگان ایمیل
        $mail->setFrom('mohsenbanihashemi4@gmail.com', 
        'Gold24'); $mail->addAddress($email); // 
        ایمیل کاربر را اضافه می‌کنیم
        
        // تولید توکن و لینک بازیابی
        $token = bin2hex(random_bytes(32)); // 
        توکن تصادفی $resetLink = 
        "https://gold24.io/English/reset-password.php?token=" 
        . $token;
        // تنظیمات محتوا
        $mail->isHTML(true); $mail->Subject = 
        'Reset Your Password - Gold24'; 
        $mail->Body = "
            <div style='font-family: Arial, 
            sans-serif;'>
                <h2>Password Reset Request</h2> 
                <p>Hello,</p> <p>You have 
                requested to reset your password. 
                Click the link below to reset 
                it:</p> <p><a 
                href='{$resetLink}'>Reset 
                Password</a></p> <p>If you didn't 
                request this, please ignore this 
                email.</p>
            </div> ";
        
        // ارسال ایمیل
        $mail->send(); $successMessage = 
        "Password reset link has been sent to 
        your email. Please check your inbox.";
    } catch (Exception $e) {
        $errorMessage = "Message could not be 
        sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?> <!DOCTYPE html> <html lang="en"> <head> <meta 
    charset="UTF-8"> <meta name="viewport" 
    content="width=device-width, 
    initial-scale=1.0"> <title>Forgot Password - 
    Gold24</title> <style>
        body { font-family: Arial, sans-serif; 
            background-color: #f8f0e3; /* رنگ 
            زمینه نوتلایی */ display: flex; 
            justify-content: center; align-items: 
            center; height: 100vh; margin: 0;
        }
        .container { width: 300px; padding: 20px; 
            background-color: #d49a6a; /* رنگ 
            اصلی نوتلا */ box-shadow: 0px 4px 
            12px rgba(0, 0, 0, 0.1); 
            border-radius: 8px; text-align: 
            center;
        }
        h2 { font-size: 24px; color: #4e3629; /* 
            رنگ تیره برای عنوان */
        }
        input[type="email"] { width: 100%; 
            padding: 10px; margin: 10px 0; 
            border: 1px solid #ced4da; 
            border-radius: 5px; background-color: 
            #fff4e1; /* رنگ پس‌زمینه ورودی */
            color: #4e3629;
        }
        button { width: 100%; padding: 10px; 
            background-color: #4CAF50; color: 
            white; border: none; border-radius: 
            5px; cursor: pointer;
        }
        button:hover { background-color: #218838;
        }
        .error { color: #dc3545; 
            background-color: #f8d7da; border: 
            1px solid #f5c6cb; padding: 10px; 
            margin: 10px 0; border-radius: 5px;
        }
        .success { color: #155724; 
            background-color: #d4edda; border: 
            1px solid #c3e6cb; padding: 10px; 
            margin: 10px 0; border-radius: 5px;
        }
    </style> </head> <body> <div 
    class="container">
        <h2>Forgot Password</h2> <?php if 
        ($errorMessage): ?>
            <div class="error"><?php echo 
            $errorMessage; ?></div>
        <?php endif; ?> <?php if 
        ($successMessage): ?>
            <div class="success"><?php echo 
            $successMessage; ?></div>
        <?php endif; ?> <form method="POST" 
        action="">
            <input type="email" name="email" 
            placeholder="Enter your email" 
            required> <button 
            type="submit">Send</button>
        </form> </div> </body>
</html>
