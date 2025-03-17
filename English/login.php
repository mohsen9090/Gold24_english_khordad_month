<?php session_start(); error_reporting(E_ALL); 
ini_set('display_errors', 1); 
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']); 
    $password = trim($_POST['password']);
    
    $stmt = $conn->prepare("SELECT id, user_uid, 
    username FROM users WHERE username = ? AND 
    password = ?"); $stmt->bind_param("ss", 
    $username, $password); $stmt->execute(); 
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) { $user = 
        $result->fetch_assoc(); 
        $_SESSION['user_uid'] = 
        $user['user_uid']; $_SESSION['username'] 
        = $user['username'];
        
        // چاپ مقادیر برای دیباگ
        echo "<pre>Login Successful!\nSession 
        Data:\n"; print_r($_SESSION); echo 
        "</pre>";
        
        header("Location: profile.php"); exit;
    }
}
?>
