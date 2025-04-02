<?php 
session_start();ob_start();ini_set('display_errors',1);error_reporting(E_ALL);$conn=new 
mysqli("localhost","gold24_user","random_password","gold24_db");$conn->set_charset("utf8mb4");$email=$_SESSION['email']??'';$user_id=$_SESSION['user_uid']??'';if(empty($email)||empty($user_id)){header("Location:login.php");exit;}function 
verifyTronTransaction($hash){$website_wallet="TV7ECDeWTYH3FFhMgZktHNxGsawhxWLRgd";$url="https://apilist.tronscan.org/api/transaction-info?hash=".$hash;$opts=['http'=>['method'=>'GET','header'=>['Accept:application/json','User-Agent:Mozilla/5.0']],'ssl'=>['verify_peer'=>false,'verify_peer_name'=>false]];$response=file_get_contents($url,false,stream_context_create($opts));if($response===false)throw 
new Exception("خطا در تأیید 
تراکنش");$data=json_decode($response,true);if(!isset($data['confirmed'])||!$data['confirmed'])throw 
new Exception("تراکنش هنوز تأیید نشده 
است");if(!isset($data['contractRet'])||$data['contractRet']!=='SUCCESS')throw 
new Exception("تراکنش ناموفق 
بود");$contractData=$data['contractData'];if(!isset($contractData['to_address'])||$contractData['to_address']!==$website_wallet)throw 
new Exception("آدرس مقصد نامعتبر 
است");$txAmount=$contractData['amount']/1000000;if($txAmount<20)throw 
new Exception("حداقل مبلغ 20 TRX است (دریافت شده: 
{$txAmount} 
TRX)");return['amount'=>$txAmount,'from'=>$contractData['owner_address'],'to'=>$contractData['to_address'],'status'=>true];}if($_SERVER["REQUEST_METHOD"]=="POST"){header('Content-Type:application/json;charset=UTF-8');try{$transaction_hash=trim($_POST['transaction_hash']);if(empty($transaction_hash)||strlen($transaction_hash)!==64||!ctype_xdigit($transaction_hash))throw 
new Exception("فرمت هش تراکنش نامعتبر 
است");$stmt=$conn->prepare("SELECT id FROM 
purchases WHERE 
transaction_hash=?");$stmt->bind_param("s",$transaction_hash);$stmt->execute();if($stmt->get_result()->num_rows>0)throw 
new Exception("این هش تراکنش قبلاً استفاده شده 
است");$verification=verifyTronTransaction($transaction_hash);if($verification['status']){$conn->begin_transaction();try{$stmt=$conn->prepare("INSERT 
INTO purchases 
(email,user_id,transaction_hash,amount_trx,deposit_address,lesson_id) 
VALUES 
(?,?,?,?,?,?)");$deposit_address="TV7ECDeWTYH3FFhMgZktHNxGsawhxWLRgd";$lesson_id="all";$stmt->bind_param("ssdssi",$email,$user_id,$transaction_hash,$verification['amount'],$deposit_address,$lesson_id);if(!$stmt->execute())throw 
new Exception("خطا در ثبت خرید: 
".$stmt->error);for($lesson_id=6;$lesson_id<=12;$lesson_id++){$stmt=$conn->prepare("INSERT 
INTO lesson_locks 
(user_id,lesson_id,is_locked,unlock_date) VALUES 
(?,?,0,CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE 
is_locked=0,unlock_date=CURRENT_TIMESTAMP");$stmt->bind_param("si",$user_id,$lesson_id);if(!$stmt->execute())throw 
new Exception("خطا در باز کردن درس: 
".$stmt->error);}$conn->commit();ob_clean();echo 
json_encode(['success'=>true,'message'=>"خرید با 
موفقیت انجام 
شد!",'lesson_ids'=>range(6,12),'is_locked'=>0],JSON_UNESCAPED_UNICODE);exit;}catch(Exception$e){$conn->rollback();throw$e;}}else{ob_clean();echo 
json_encode(['success'=>false,'message'=>"تراکنش 
ناموفق بود. لطفاً مجدداً تلاش 
کنید."],JSON_UNESCAPED_UNICODE);exit;}}catch(Exception$e){ob_clean();echo 
json_encode(['success'=>false,'message'=>$e->getMessage()],JSON_UNESCAPED_UNICODE);exit;}}?> 
<!DOCTYPE html><html 
lang="fa"dir="rtl"><head><meta 
charset="UTF-8"><meta 
name="viewport"content="width=device-width,initial-scale=1.0"><title>خرید 
همه درس‌ها (6 تا 
12)</title><style>:root{--primary:#654321;--secondary:#8B4513;--accent:#D2691E;--background:#1C1C1C;--card-bg:#2B1B17;--text:#FFF;--gold:#FFD700;--success:#28a745}*{box-sizing:border-box;margin:0;padding:0}body{background:var(--background);color:var(--text);font-family:'Vazirmatn',sans-serif;line-height:1.6;min-height:100vh;display:flex;justify-content:center;align-items:center;padding:20px}.container{background:var(--card-bg);border-radius:15px;box-shadow:0 
10px 30px 
rgba(0,0,0,0.3);padding:30px;width:100%;max-width:500px;position:relative;overflow:hidden}h2{color:var(--gold);text-align:center;margin-bottom:30px;font-size:24px}.wallet-info{background:rgba(101,67,33,0.1);padding:20px;border-radius:10px;margin-bottom:25px;border:1px 
solid 
rgba(255,215,0,0.2)}.wallet-address{background:rgba(0,0,0,0.2);padding:15px;border-radius:8px;margin:10px 
0;word-break:break-all;font-family:monospace;font-size:14px;border:1px 
dashed 
var(--gold);color:var(--gold)}.form-group{margin-bottom:20px}label{display:block;margin-bottom:8px;color:var(--gold);font-weight:bold}input{width:100%;padding:12px 
15px;border:2px solid 
rgba(101,67,33,0.5);border-radius:8px;font-size:16px;transition:all 
0.3s 
ease;background:rgba(0,0,0,0.2);color:var(--text)}input:focus{border-color:var(--gold);outline:none;box-shadow:0 
0 0 3px 
rgba(255,215,0,0.2)}.readonly-input{color:var(--text)!important;background:rgba(0,0,0,0.5)!important;cursor:default!important}button{width:100%;padding:12px;background:linear-gradient(45deg,var(--gold),var(--accent));color:#000;border:none;border-radius:8px;font-size:16px;font-weight:bold;cursor:pointer;transition:all 
0.3s 
ease;margin-top:10px}button:hover{transform:translateY(-2px);box-shadow:0 
5px 15px 
rgba(255,215,0,0.3)}#loading,#error,#success{padding:15px;border-radius:8px;margin-top:20px;text-align:center;display:none}#loading{background:rgba(255,215,0,0.1);border:1px 
solid 
rgba(255,215,0,0.2);color:var(--gold)}#error{background:rgba(220,53,69,0.1);color:#dc3545;border:1px 
solid 
rgba(220,53,69,0.2)}#success{background:rgba(40,167,69,0.1);color:var(--success);border:1px 
solid 
rgba(40,167,69,0.2)}</style></head><body><div 
class="container"><h2>خرید همه درس‌ها (6 تا 
12)</h2><div class="wallet-info"><p>حداقل مبلغ: 
20 TRX</p><p>آدرس کیف پول:</p><div 
class="wallet-address"id="wallet-address">TV7ECDeWTYH3FFhMgZktHNxGsawhxWLRgd</div></div><form 
id="purchaseForm"method="post"><div 
class="form-group"><label>ایمیل:</label><input 
type="email"class="readonly-input"value="<?php 
echo htmlspecialchars($email); 
?>"readonly></div><div 
class="form-group"><label>شناسه 
پروفایل:</label><input 
type="text"class="readonly-input"value="<?php 
echo htmlspecialchars($user_id); 
?>"readonly></div><div 
class="form-group"><label>هش 
تراکنش:</label><input 
type="text"name="transaction_hash"id="transaction_hash"placeholder="هش 
تراکنش را وارد کنید"required></div><button 
type="submit"id="submit">ثبت خرید</button><div 
id="loading"style="display:none;">در حال بررسی 
تراکنش...</div><div 
id="error"style="display:none;"></div><div 
id="success"style="display:none;"></div></form></div><script 
src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script><script>$(document).ready(function(){$('#purchaseForm').submit(function(e){e.preventDefault();var 
transaction_hash=$('#transaction_hash').val();$.ajax({url:'purchase_all.php',type:'POST',dataType:'json',data:{transaction_hash:transaction_hash},beforeSend:function(){$('#loading').show();$('#error').hide();$('#success').hide();},success:function(response){$('#loading').hide();if(response.success){$('#purchaseForm').hide();let 
lessonList=response.lesson_ids.map(id=>`<li>• درس 
${id} باز شد</li>`).join('');$('#success').html(` 
<div class="success-message">
                <div 
                style="font-size:48px;margin-bottom:10px;">✅</div> 
                <p 
                style="color:#28a745;font-size:18px;font-weight:bold;margin-bottom:15px;">خرید 
                با موفقیت انجام شد!</p> <p 
                style="color:#28a745;margin-bottom:15px;">درس‌های 
                باز شده:</p> <ul 
                style="color:#28a745;font-size:14px;margin-bottom:20px;">${lessonList}</ul> 
                <div class="notice" 
                style="background:rgba(255,193,7,0.1);padding:15px;border-radius:6px;text-align:right;">
                    <p 
                    style="color:#856404;font-size:14px;margin-bottom:10px;">⚠️ 
                    توجه:</p> <ul 
                    style="color:#856404;font-size:13px;list-style:none;padding:0;">
                        <li 
                        style="margin-bottom:8px;">• 
                        این خرید به صورت اشتراک 
                        ماهانه است</li> <li 
                        style="margin-bottom:8px;">• 
                        دسترسی به محتوا دائمی 
                        نیست</li> <li 
                        style="margin-bottom:8px;">• 
                        قوانین سایت ممکن است 
                        تغییر کند</li> <li 
                        style="margin-bottom:8px;">• 
                        قیمت‌ها همیشگی نیستند</li>
                    </ul> </div> <a 
                href="index.php" 
                style="display:inline-block;background:#28a745;color:white;text-decoration:none;padding:12px 
                25px;border-radius:5px;font-weight:bold;margin-top:10px;">بازگشت 
                به صفحه اصلی</a>
            </div> `).show();
    }else{$('#error').text(response.message).show();}},error:function(){$('#loading').hide();$('#error').text('خطایی رخ داد، دوباره تلاش کنید.').show();}});});});</script></body></html>
