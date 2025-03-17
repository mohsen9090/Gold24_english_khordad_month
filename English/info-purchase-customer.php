<?php ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); $servername = 
"localhost"; $username = "gold24_user"; $password 
= "random_password"; $dbname = "gold24_db"; $conn 
= new mysqli($servername, $username, $password, 
$dbname); if ($conn->connect_error) {
    die("Connection failed: " . 
    $conn->connect_error);
}
$sql = "SELECT id, email, user_id, 
transaction_hash, amount_trx, deposit_address, 
created_at
        FROM purchases ORDER BY created_at DESC"; 
$result = $conn->query($sql); $purchases = []; if 
($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) { 
        $purchases[] = $row;
    }
}
$conn->close(); ?> <!DOCTYPE html> <html 
lang="en"> <head>
    <meta charset="UTF-8"> <meta name="viewport" 
    content="width=device-width, 
    initial-scale=1.0"> <title>Purchase 
    Information</title> <link 
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" 
    rel="stylesheet"> <style>
        body { font-family: 'Poppins', 
            sans-serif; background-color: 
            #1C1C1C;
            color: #E5B77E; padding: 20px; 
            margin: 0;
        }
        .container { max-width: 1400px; margin: 0 
            auto; background: #2B1810; padding: 
            30px; border-radius: 15px; 
            box-shadow: 0 8px 32px rgba(150, 75, 
            0, 0.2); border: 1px solid rgba(165, 
            93, 22, 0.3);
        }
        h1 { text-align: center; color: #FFD700; 
            margin-bottom: 30px; font-size: 
            2.5em; text-shadow: 0 2px 4px 
            rgba(0,0,0,0.3);
        }
        .table-container { overflow-x: auto;
        }
        table { width: 100%; border-collapse: 
            collapse; margin-top: 20px; 
            background: rgba(43, 24, 16, 0.8);
        }
        th, td { padding: 15px; text-align: left; 
            border-bottom: 1px solid rgba(165, 
            93, 22, 0.3);
        }
        th { background-color: #A55D16; color: 
            #FFD700;
            font-weight: 600; text-transform: 
            uppercase; font-size: 0.9em; 
            letter-spacing: 1px; white-space: 
            nowrap;
        }
        tr:hover { background-color: rgba(165, 
            93, 22, 0.1);
        }
        td { color: #E5B77E; max-width: 200px; 
            overflow: hidden; text-overflow: 
            ellipsis;
        }
        .hash-cell { font-family: monospace; 
            font-size: 0.9em;
        }
        .amount-cell { font-weight: 600; color: 
            #FFD700;
        }
        .date-cell { white-space: nowrap; color: 
            #A55D16;
        }
        .empty-message { text-align: center; 
            padding: 40px; color: #A55D16; 
            font-style: italic;
        }
        @media (max-width: 768px) { .container { 
                padding: 15px; margin: 10px;
            }
            th, td { padding: 10px; font-size: 
                0.85em;
            }
            h1 { font-size: 1.8em;
            }
        }
    </style> </head> <body> <div 
    class="container">
        <h1>Purchase Information</h1> <div 
        class="table-container">
            <?php if (!empty($purchases)): ?> 
                <table>
                    <tr> <th>ID</th> 
                        <th>Email</th> <th>User 
                        ID</th> <th>Transaction 
                        Hash</th> <th>Amount 
                        TRX</th> <th>Deposit 
                        Address</th> <th>Created 
                        At</th>
                    </tr> <?php foreach 
                    ($purchases as $purchase): ?>
                        <tr> <td><?php echo 
                            htmlspecialchars($purchase['id'] 
                            ?? ''); ?></td> 
                            <td><?php echo 
                            htmlspecialchars($purchase['email'] 
                            ?? ''); ?></td> 
                            <td><?php echo 
                            htmlspecialchars($purchase['user_id'] 
                            ?? ''); ?></td> <td 
                            class="hash-cell"><?php 
                            echo 
                            htmlspecialchars($purchase['transaction_hash'] 
                            ?? ''); ?></td> <td 
                            class="amount-cell"><?php 
                            echo 
                            htmlspecialchars($purchase['amount_trx'] 
                            ?? ''); ?></td> 
                            <td><?php echo 
                            htmlspecialchars($purchase['deposit_address'] 
                            ?? ''); ?></td> <td 
                            class="date-cell"><?php 
                            echo 
                            htmlspecialchars($purchase['created_at'] 
                            ?? ''); ?></td>
                        </tr> <?php endforeach; 
                    ?>
                </table> <?php else: ?> <div 
                class="empty-message">No 
                purchases have been recorded 
                yet.</div>
            <?php endif; ?> </div> </div> </body>
</html>
