<?php session_start(); ini_set('display_errors', 
1); ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); if 
($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    // بررسی اینکه ایمیل وارد شده وجود دارد یا 
    // خیر
    $conn = new mysqli("localhost", "root", "", 
    "gold24_db"); if ($conn->connect_error) {
        die("Connection failed: " . 
        $conn->connect_error);
    }
    // جستجو برای ایمیل وارد شده
    $sql = "SELECT * FROM users WHERE email = 
    '$email'"; $result = $conn->query($sql); if 
    ($result->num_rows > 0) {
        // ایجاد کد تصادفی برای پسورد جدید
        $new_password = strval(rand(100000, 
        999999));
        // هش کردن پسورد جدید
        $hashed_password = 
        password_hash($new_password, 
        PASSWORD_BCRYPT);
        // بروزرسانی پسورد در دیتابیس
        $update_query = "UPDATE users SET 
        password = '$hashed_password' WHERE email 
        = '$email'"; if 
        ($conn->query($update_query) === TRUE) {
            // ارسال ایمیل با پسورد جدید
            mail($email, "Password Reset - 
            Gold24", "Your new password is: 
            $new_password"); echo "A new password 
            has been sent to your email.";
        } else {
            echo "Error updating password: " . 
            $conn->error;
        }
    } else {
        echo "No account found with that email.";
    }
    $conn->close();
}
?> <!-- فرم HTML --> <!DOCTYPE html> <html 
lang="en"> <head>
    <meta charset="UTF-8"> <meta name="viewport" 
    content="width=device-width, 
    initial-scale=1.0"> <title>Password 
    Reset</title> <style>
        body { font-family: Arial, sans-serif; 
            background-color: #f8f0e3; padding: 
            50px; text-align: center;
        }
        .container { max-width: 400px; margin: 0 
            auto; background-color: #d49a6a; 
            padding: 20px; border-radius: 8px; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 
            0.1);
        }
        h2 { font-size: 24px; color: #4e3629;
        }
        input { width: 100%; max-width: 300px; 
            padding: 10px; margin: 10px 0; 
            border: 1px solid #ddd; 
            border-radius: 4px; font-size: 16px; 
            background-color: #fff4e1; color: 
            #4e3629;
        }
        button { padding: 10px 20px; 
            background-color: #4CAF50; color: 
            white; border: none; border-radius: 
            4px; cursor: pointer; font-size: 
            16px;
        }
        button:hover { background-color: #45a049;
        }
        a { color: #4CAF50; text-decoration: 
            none; font-size: 14px;
        }
        a:hover { text-decoration: underline;
        }
    </style> </head> <body> <div 
    class="container">
        <h2>Password Reset</h2> <form 
        method="POST" 
        action="reset-password.php">
            <input type="email" name="email" 
            placeholder="Enter your email" 
            required> <br> <button 
            type="submit">Send New 
            Password</button>
        </form> </div> </body>
</html>
