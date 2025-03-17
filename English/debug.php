<?php session_start(); error_reporting(E_ALL); 
ini_set('display_errors', 1);
// اتصال به دیتابیس
$servername = "localhost"; $username = 
"gold24_user"; $password = "random_password"; 
$dbname = "gold24_db"; $conn = new 
mysqli($servername, $username, $password, 
$dbname); $conn->set_charset("utf8mb4"); echo 
"<pre>"; echo "Session Data:\n"; 
print_r($_SESSION); echo "\n\nDatabase Data:\n";
// بررسی مقادیر در دیتابیس
if(isset($_SESSION['user_uid'])) { $user_uuid = 
    $_SESSION['user_uid']; $stmt = 
    $conn->prepare("SELECT * FROM users WHERE 
    user_uid = ?"); $stmt->bind_param("s", 
    $user_uuid); $stmt->execute(); $result = 
    $stmt->get_result();
    
    if($result->num_rows > 0) { 
        print_r($result->fetch_assoc());
    } else {
        echo "No user found with UUID: " . 
        htmlspecialchars($user_uuid);
    }
}
echo "\n\nAll Users in Database:\n"; $result = 
$conn->query("SELECT id, user_uid, username FROM 
users LIMIT 5"); while($row = 
$result->fetch_assoc()) {
    print_r($row);
}
echo "</pre>";
?>
