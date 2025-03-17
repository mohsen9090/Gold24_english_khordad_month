<?php session_start(); error_reporting(0); if 
(!isset($_SESSION['user_uid'])) {
    header("Location: login.php"); exit;
}
$servername = "localhost"; $username = 
"gold24_user"; $password = "random_password"; 
$dbname = "gold24_db"; try {
    $conn = new mysqli($servername, $username, 
    $password, $dbname); 
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("خطای اتصال به پایگاه داده");
}
$user_uuid = $_SESSION['user_uid']; $stmt = 
$conn->prepare("SELECT 
username,email,phone,city,user_uid FROM users 
WHERE user_uid=?"); $stmt->bind_param("s", 
$user_uuid); $stmt->execute(); $result = 
$stmt->get_result(); if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc(); 
    $username = $user_data['username'] ?: ''; 
    $email = $user_data['email'] ?: ''; $phone = 
    $user_data['phone'] ?: ''; $city = 
    $user_data['city'] ?: '';
} else {
    header("Location: login.php"); exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $new_username = trim($_POST['username']); 
    $new_phone = trim($_POST['phone']) ?: ''; 
    $new_city = trim($_POST['city']) ?: ''; if 
    (empty($new_username)) {
        echo "<script>alert('نام کاربری الزامی 
        است');</script>";
    } else {
        $update_stmt = $conn->prepare("UPDATE 
        users SET username=?, phone=?, city=? 
        WHERE user_uid=?"); 
        $update_stmt->bind_param("ssss", 
        $new_username, $new_phone, $new_city, 
        $user_uuid); if ($update_stmt->execute()) 
        {
            echo "<script>alert('اطلاعات با 
            موفقیت بروزرسانی 
            شد');window.location.reload();</script>";
        }
    }
}
$conn->close(); ?> <!DOCTYPE html> <html 
lang="fa" dir="rtl"> <head>
    <meta charset="UTF-8"> <meta name="viewport" 
    content="width=device-width, 
    initial-scale=1.0"> <title>پروفایل 
    کاربری</title> <style>
        :root {
            --dark-brown: #3C2A21; 
            --medium-brown: #8B6B44; 
            --light-brown: #D9B88C; --gold: 
            #FFD700;
            --cream: #F5E6D3; --text-light: #FFF; 
            --text-dark: #2C1810;
        }
        * { box-sizing: border-box; margin: 0; 
            padding: 0;
        }
        body { background: 
            linear-gradient(135deg, 
            var(--dark-brown), 
            var(--medium-brown)); font-family: 
            'Vazirmatn', sans-serif; min-height: 
            100vh; margin: 0; padding: 20px; 
            color: var(--text-light); display: 
            flex; justify-content: center; 
            align-items: center;
        }
        .profile-container { background: 
            linear-gradient(to bottom right, 
            var(--medium-brown), 
            var(--light-brown)); width: 90%; 
            max-width: 500px; border-radius: 
            15px; padding: 30px; box-shadow: 0 
            10px 30px rgba(0, 0, 0, 0.3); 
            position: relative;
        }
        .profile-container::before { content: ''; 
            position: absolute; top: 0; left: 0; 
            right: 0; height: 5px; background: 
            linear-gradient(to right, 
            var(--gold), var(--light-brown));
        }
        .header { text-align: center; 
            margin-bottom: 30px;
        }
        .profile-title { font-size: 24px; 
            font-weight: bold; color: 
            var(--gold); margin-bottom: 10px;
        }
        .user-info { color: var(--cream); 
            font-size: 14px;
        }
        .form-group { margin-bottom: 20px;
        }
        .form-group label { display: block; 
            color: var(--gold); margin-bottom: 
            8px; font-size: 14px;
        }
        .form-group input { width: 100%; padding: 
            12px; background: rgba(255, 255, 255, 
            0.1); border: 1px solid 
            var(--light-brown); border-radius: 
            8px; color: var(--text-light); 
            font-size: 15px;
        }
        .form-group input:focus { outline: none; 
            border-color: var(--gold); 
            box-shadow: 0 0 10px rgba(255, 215, 
            0, 0.2);
        }
        .readonly-input { background: rgba(255, 
            255, 255, 0.05) !important; 
            border-color: var(--gold) !important; 
            cursor: not-allowed !important;
        }
        .button-container { display: flex; gap: 
            15px; margin-top: 30px;
        }
        .btn { flex: 1; padding: 12px; border: 
            none; border-radius: 8px; font-size: 
            15px; font-weight: bold; cursor: 
            pointer; transition: 0.3s; 
            text-align: center; text-decoration: 
            none;
        }
        .save-btn { background: 
            linear-gradient(45deg, var(--gold), 
            var(--light-brown)); color: 
            var(--text-dark);
        }
        .save-btn:hover { transform: 
            translateY(-2px);
        }
        .back-btn { background: rgba(255, 255, 
            255, 0.1); color: var(--cream); 
            border: 1px solid var(--cream);
        }
        .back-btn:hover { background: rgba(255, 
            255, 255, 0.2);
        }
        @media (max-width: 480px) { 
            .profile-container {
                padding: 20px;
            }
            .button-container { flex-direction: 
                column;
            }
            .btn { width: 100%; margin-bottom: 
                10px;
            }
        }
    </style> </head> <body> <div 
    class="profile-container">
        <div class="header"> <h2 
            class="profile-title">پروفایل 
            کاربری</h2> <div 
            class="user-info"><?= $email ?></div>
        </div> <form method="POST"> <div 
            class="form-group">
                <label>نام کاربری:</label> <input 
                type="text" name="username" 
                value="<?= $username ?>" 
                required>
            </div> <div class="form-group"> 
                <label>ایمیل:</label> <input 
                type="email" value="<?= $email 
                ?>" class="readonly-input" 
                readonly>
            </div> <div class="form-group"> 
                <label>تلفن:</label> <input 
                type="text" name="phone" 
                value="<?= $phone ?>">
            </div> <div class="form-group"> 
                <label>شهر:</label> <input 
                type="text" name="city" 
                value="<?= $city ?>">
            </div> <div class="button-container"> 
                <button type="submit" class="btn 
                save-btn">ذخیره تغییرات</button> 
                <a href="/English/index.php" 
                class="btn back-btn">بازگشت به 
                خانه</a>
            </div> </form> </div> </body>
</html>
