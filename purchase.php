<?php
// purchase.php
session_start(); $message = ""; // Variable to 
hold success or error messages if 
($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form and save the data to the 
    // database
    $email = $_POST['email']; $uid = 
    $_POST['uid']; $transaction_hash = 
    $_POST['transaction_hash']; $amount_trx = 
    $_POST['amount_trx']; // Directly using the 
    entered value $deposit_address = 
    $_POST['deposit_address'];
    // Validate amount_trx to ensure it's a valid 
    // number
    if (!is_numeric($amount_trx)) { $message = 
        "مقدار وارد شده برای مبلغ معتبر نیست.";
    } else {
        // Connection to database (use your 
        // actual credentials here)
        $servername = "localhost"; $username = 
        "gold24_user"; $password = 
        "random_password"; $dbname = "gold24_db"; 
        $conn = new mysqli($servername, 
        $username, $password, $dbname);
        
        if ($conn->connect_error) { 
            die("Connection failed: " . 
            $conn->connect_error);
        }
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO 
        purchases (email, user_id, 
        transaction_hash, amount_trx, 
        deposit_address) VALUES (?, ?, ?, ?, 
        ?)"); $stmt->bind_param("sssss", $email, 
        $uid, $transaction_hash, $amount_trx, 
        $deposit_address);
        // Execute the statement
        if ($stmt->execute()) { $message = "ثبت 
            خرید جدید با موفقیت انجام شد.";
        } else {
            $message = "خطا: " . $stmt->error;
        }
        $stmt->close(); $conn->close();
    }
}
?> <!DOCTYPE html> <html lang="en"> <head> <meta 
    charset="UTF-8"> <meta name="viewport" 
    content="width=device-width, 
    initial-scale=1.0"> <title>Purchase 
    Form</title> <style>
        body { font-family: Arial, sans-serif; 
            background-color: #1c1c1c; color: 
            #fff;
            display: flex; justify-content: 
            center; align-items: center; height: 
            100vh; margin: 0;
        }
        h1 { text-align: center; margin-bottom: 
            20px;
        }
        form { background-color: #2b1b17; 
            padding: 20px; border-radius: 8px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 
            0.2);
        }
        label { display: block; margin-bottom: 
            10px;
        }
        input { width: 100%; padding: 10px; 
            border: none; border-radius: 4px; 
            background-color: #654321; color: 
            #fff;
            margin-bottom: 15px;
        }
        button { display: block; width: 100%; 
            padding: 10px; background-color: 
            #8b0000;
            color: #fff; border: none; 
            border-radius: 4px; cursor: pointer; 
            font-size: 16px; transition: 
            background-color 0.3s ease;
        }
        button:hover { background-color: #ff6347;
        }
        .message { text-align: center; 
            margin-top: 20px; color: #ff6347; /* 
            Red color for error/success messages 
            */
        }
    </style> </head> <body> <h1>Purchase 
    Form</h1> <form method="POST" 
    action="purchase.php">
        <label for="email">Email:</label> <input 
        type="email" id="email" name="email" 
        required> <label for="uid">Profile 
        UID:</label> <input type="text" id="uid" 
        name="uid" required> <label 
        for="transaction_hash">Transaction 
        Hash:</label> <input type="text" 
        id="transaction_hash" 
        name="transaction_hash" required> <label 
        for="amount_trx">Amount 
        (TRX-TRC20):</label> <input type="text" 
        id="amount_trx" name="amount_trx" 
        required> <label 
        for="deposit_address">Deposit Address 
        (TRX):</label> <input type="text" 
        id="deposit_address" 
        name="deposit_address" required> <button 
        type="submit">Submit</button>
    </form> <?php if ($message): ?> <div 
        class="message"><?php echo $message; 
        ?></div>
    <?php endif; ?> </body> </html>
