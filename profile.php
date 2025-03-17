<?php
// فعال‌سازی نمایش خطا برای دیباگ
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); session_start();
// بررسی ورود کاربر
if (!isset($_SESSION['user_id'])) { 
    header("Location: /login.php"); exit();
}
// اتصال به دیتابیس
$servername = "gold24.io"; $username = 
"gold24_user"; $password = "random_password"; 
$dbname = "gold24_db"; $conn = new 
mysqli($servername, $username, $password, 
$dbname); if ($conn->connect_error) {
    die("Database Connection Failed: " . 
    $conn->connect_error);
}
$user_id = $_SESSION['user_id']; $update_message 
= '';
// پردازش درخواست‌های فرم
if ($_SERVER["REQUEST_METHOD"] == "POST") { if 
    (isset($_POST['edit'])) {
        $name = trim($_POST['name']); $phone = 
        trim($_POST['phone']); $city = 
        trim($_POST['city']); if (!empty($name) 
        && !empty($phone) && !empty($city)) {
            $sql = "UPDATE users SET name=?, 
            phone=?, city=? WHERE id=?"; $stmt = 
            $conn->prepare($sql); 
            $stmt->bind_param("sssi", $name, 
            $phone, $city, $user_id); 
            $stmt->execute(); if 
            ($stmt->affected_rows > 0) {
                $update_message = '<p 
                style="color: green;">تغییرات 
                ذخیره شد!</p>';
            } else {
                $update_message = '<p 
                style="color: orange;">تغییری 
                ایجاد نشد.</p>';
            }
            $stmt->close();
        } else {
            $update_message = '<p style="color: 
            red;">همه فیلدها الزامی هستند!</p>';
        }
    } elseif (isset($_POST['delete'])) {
        $sql = "DELETE FROM users WHERE id=?"; 
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("i", $user_id); 
        $stmt->execute(); $stmt->close(); 
        $conn->close(); session_destroy(); 
        header("Location: /register.php"); 
        exit();
    }
}
// دریافت اطلاعات کاربر
$sql = "SELECT uid, username, email, name, phone, 
city FROM users WHERE id = ?"; $stmt = 
$conn->prepare($sql); $stmt->bind_param("i", 
$user_id); $stmt->execute(); $result = 
$stmt->get_result(); $user = 
$result->fetch_assoc(); $stmt->close(); 
$conn->close(); $username_display = 
($user['username'] == 'default_username') ? '---' 
: $user['username'];
?> <!DOCTYPE html> <html lang="fa"> <head> <meta 
    charset="UTF-8"> <meta name="viewport" 
    content="width=device-width, 
    initial-scale=1.0"> <title>پروفایل - 
    Gold24</title> <style>
        body { font-family: Arial, sans-serif; 
            background-color: #f4f4f9; 
            text-align: center; margin: 0; 
            padding: 20px;
        }
        .profile-container { background: white; 
            padding: 20px; border-radius: 10px; 
            max-width: 400px; margin: auto; 
            box-shadow: 0 4px 8px 
            rgba(0,0,0,0.1);
        }
        h2 { color: #4CAF50; } p { font-size: 
        18px; margin: 10px 0; } 
        input[type="text"], input[type="submit"] 
        {
            padding: 10px; width: 90%; 
            margin-top: 5px;
        }
        .btn { display: block; text-decoration: 
            none; background: #1976D2; color: 
            white; padding: 10px; margin-top: 
            10px; border-radius: 5px; cursor: 
            pointer; border: none; width: 100%;
        }
        .btn:hover { background: #125a9d; } 
        .btn-delete {
            background: #FF5252;
        }
        .btn-delete:hover { background: #D32F2F; 
        }
        .message { padding: 10px; margin: 10px 0; 
            border-radius: 5px;
        }
    </style> </head> <body> <div 
    class="profile-container">
        <h2>پروفایل کاربر</h2> <?php echo 
        $update_message; ?>
        
        <p><strong>UID:</strong> <?php echo 
        htmlspecialchars($user['uid'] ?? '---'); 
        ?></p>
        
        <form method="post" action="<?php echo 
        htmlspecialchars($_SERVER["PHP_SELF"]); 
        ?>">
            <p><strong>نام:</strong> <input 
                type="text" name="name" 
                value="<?php echo 
                htmlspecialchars($user['name'] ?? 
                ''); ?>" required>
            </p> <p><strong>نام کاربری:</strong> 
            <?php echo 
            htmlspecialchars($username_display); 
            ?></p> <p><strong>ایمیل:</strong> 
            <?php echo 
            htmlspecialchars($user['email'] ?? 
            '---'); ?></p> 
            <p><strong>تلفن:</strong>
                <input type="text" name="phone" 
                value="<?php echo 
                htmlspecialchars($user['phone'] 
                ?? ''); ?>" required>
            </p> <p><strong>شهر:</strong> <input 
                type="text" name="city" 
                value="<?php echo 
                htmlspecialchars($user['city'] ?? 
                ''); ?>" required>
            </p>
            
            <input type="submit" name="edit" 
            value="ذخیره تغییرات" class="btn">
        </form>
        
        <form method="post" onsubmit="return 
        confirm('آیا مطمئن هستید؟ این عمل غیرقابل 
        برگشت است!');">
            <input type="submit" name="delete" 
            value="حذف حساب کاربری" class="btn 
            btn-delete">
        </form> <!-- لینک جدید که به 
        /English/index.php می‌رود --> <a 
        href="/English/index.php" 
        class="btn">بازگشت به خانه</a>
    </div> </body>
</html>
