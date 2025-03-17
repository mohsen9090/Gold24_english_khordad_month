<?php session_start(); if 
(!isset($_SESSION['user_uid'])) {
    header("Location: /login.php"); exit();
}
// اطلاعات دیتابیس
$servername = "gold24.io"; $username = 
"gold24_user"; $password = "random_password"; 
$dbname = "gold24_db"; $conn = new 
mysqli($servername, $username, $password, 
$dbname); if ($conn->connect_error) {
    die("Connection failed: " . 
    $conn->connect_error);
}
// دریافت uid از session
$user_uid = $_SESSION['user_uid'];
// دریافت اطلاعات کاربر بر اساس UID
$sql = "SELECT name, email, uid FROM users WHERE 
uid = ?"; $stmt = $conn->prepare($sql); 
$stmt->bind_param("s", $user_uid); 
$stmt->execute(); $stmt->bind_result($name, 
$email, $uid); $stmt->fetch(); $stmt->close(); 
$conn->close(); ?> <!DOCTYPE html> <html 
lang="en"> <head>
    <meta charset="UTF-8"> <meta name="viewport" 
    content="width=device-width, 
    initial-scale=1.0"> <title>User 
    Profile</title> <style>
        body { font-family: Arial, sans-serif; 
            background-color: #f4f4f9; 
            text-align: center; padding: 50px;
        }
        .profile-container { background: white; 
            padding: 20px; border-radius: 10px; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 
            0.1); display: inline-block;
        }
        .profile-container h2 { color: #4CAF50;
        }
        .logout-btn { background-color: #E91E63; 
            color: white; padding: 10px 20px; 
            border: none; border-radius: 5px; 
            text-decoration: none; cursor: 
            pointer; display: inline-block;
        }
        .logout-btn:hover { background-color: 
            #d81b60;
        }
    </style> </head> <body> <div 
    class="profile-container">
        <h2>Welcome, <?php echo 
        htmlspecialchars($name); ?>!</h2> 
        <p><strong>Email:</strong> <?php echo 
        htmlspecialchars($email); ?></p> 
        <p><strong>UID:</strong> <?php echo 
        htmlspecialchars($uid); ?></p> <a 
        href="/logout.php" 
        class="logout-btn">Logout</a>
    </div> </body>
</html>
