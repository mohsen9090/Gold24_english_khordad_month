<?php $transaction_hash = 
"f18b2544dc12953842b8b147ae80ccd84b66991ef61c4e89456a835bc78f7b21"; 
if (preg_match('/^[a-fA-F0-9]{64}$/', 
$transaction_hash)) {
    echo "فرمت هش تراکنش صحیح است.";
} else {
    echo "فرمت هش تراکنش نامعتبر است.";
}
?>
