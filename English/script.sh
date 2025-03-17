#!/bin/bash
transaction_hash="f18b2544dc12953842b8b147ae80ccd84b66991ef61c4e89456a835bc78f7b21" 
wallet_address="D91cBaDY1ZcX1py7j66SawQiQ9dUnvyPFr" 
min_amount=7 function 
verify_dogecoin_transaction() {
    local hash=$1 local wallet=$2 local min=$3 
    local 
    url="https://dogechain.info/api/v1/transaction/$hash" 
    local response=$(curl -s "$url")
    
    if [ -z "$response" ]; then echo "خطا در 
        دریافت اطلاعات تراکنش" return 1
    fi
    
    local amount_received=$(echo $response | jq 
    -r '.transactions[0].outputs[] | 
    select(.address == "'$wallet'") | .value') if 
    [ -z "$amount_received" ]; then
        echo "تراکنش نامعتبر است یا مبلغ کمتر از 
        حداقل است" return 1
    fi
    
    if (( $(echo "$amount_received >= $min" | bc 
    -l) )); then
        local from=$(echo $response | jq -r 
        '.transactions[0].inputs[0].address') 
        echo "تراکنش معتبر است. مبلغ دریافتی: 
        $amount_received DOGE" echo "آدرس 
        فرستنده: $from" echo "آدرس گیرنده: 
        $wallet" return 0
    else echo "تراکنش نامعتبر است یا مبلغ کمتر از 
        حداقل است" return 1
    fi
}
verify_dogecoin_transaction "$transaction_hash" "$wallet_address" "$min_amount"
