<?php session_start(); ini_set('display_errors', 
0); error_reporting(0); require_once 
'vendor/autoload.php'; $dotenv = 
Dotenv\Dotenv::createImmutable(__DIR__); 
$dotenv->load(); if(!isset($_SESSION['user_id'])) 
{
    header("Location: login.php"); exit();
}
try { $conn = new mysqli($_ENV['DB_HOST'], 
    $_ENV['DB_USER'], $_ENV['DB_PASS'], 
    $_ENV['DB_NAME']); if($conn->connect_error) {
        throw new Exception("خطا در اتصال به 
        دیتابیس");
    }
    $conn->set_charset("utf8mb4"); $user_id = 
    $_SESSION['user_uid'] ?? 'all'; $username = 
    $_SESSION['user_name'] ?? null; $lessons = 
    range(1, 12); $locked_lessons = array_fill(1, 
    12, true); if($user_id != 'all') {
        $stmt = $conn->prepare("SELECT lesson_id, 
        is_locked FROM lesson_locks WHERE user_id 
        = ?"); $stmt->bind_param("s", $user_id); 
        $stmt->execute(); $result = 
        $stmt->get_result(); while($row = 
        $result->fetch_assoc()) {
            $locked_lessons[$row['lesson_id']] = 
            $row['is_locked'] == 1;
        }
    }
    for($i = 1; $i <= 5; $i++) { 
        $locked_lessons[$i] = false;
    }
} catch(Exception $e) {
    error_log($e->getMessage()); die("خطا در 
    اتصال به دیتابیس");
}
$course_videos = [ 
    'https://gold24.io/English/c3e4c3145f8a1374bb5511328d8dd7d9.MP4', 
    'https://gold24.io/English/99d975b681b3580821ffe35b3c84c34c.MP4', 
    'https://gold24.io/English/e7942b24f73989fd6e94ed66f13a5245.MP4', 
    'https://gold24.io/English/b78f5bdc712232920a030900a6fc65e2.MP4', 
    'https://gold24.io/English/cb509dd51fb54ae76a5eba0c22eceab4.MP4', 
    'https://gold24.io/English/386d485b6d91d435dad096967bd50b50.MP4', 
    'https://gold24.io/English/49e718c4cd215b584bed8e012006924c.MP4', 
    'https://gold24.io/English/5b7e503f907c2a099dfda791f3267182.MP4', 
    'https://gold24.io/English/4fdeb4bdf50a5e94a8e97a01b4ec172a.MP4', 
    'https://gold24.io/English/21176fb17cb0029baf3930187eed0b4b.MP4', 
    'https://gold24.io/English/8e9bf54acbf767eb63e736d1dd91681f.MP4', 
    'https://gold24.io/English/1e15e59f08db4a9e97e9a0a45f7830f1.MP4'
]; ?> <!DOCTYPE html> <html lang="fa" dir="rtl"> 
<head>
    <meta charset="UTF-8"> <meta name="viewport" 
    content="width=device-width, 
    initial-scale=1.0"> <title>آموزش زبان انگلیسی 
    | English.Online24</title>
    <meta name="description" content="یادگیری 
    زبان انگلیسی با جدیدترین روش‌ها! دسترسی به 
    دوره‌های مکالمه، گرامر و آزمون‌های بین‌المللی + 
    پشتیبانی ۲۴/۷"> <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
    rel="stylesheet"> <link rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"> 
    <link rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"> 
    <style>
        body { background: #1C1C1C; font-family: 
            'IRANSans', sans-serif; color: #FFF; 
            padding: 20px; margin: 0;
        }
        .header { background: linear-gradient(to 
            right, #8B0000, #A52A2A); padding: 
            15px; border-radius: 12px; 
            text-align: center; color: white;
        }
        .container { display: flex; gap: 20px; 
            padding: 20px;
        }
        .dashboard { width: 250px; background: 
            #2B1B17;
            padding: 20px; border-radius: 12px;
        }
        .dashboard h3 { color: #FFD700; 
            margin-bottom: 15px;
        }
        .dashboard-buttons { display: flex; gap: 
            10px; flex-wrap: wrap;
        }
        .dashboard-button { flex: 1; background: 
            #007bff;
            color: #FFF; padding: 8px 15px; 
            border-radius: 8px; text-decoration: 
            none; text-align: center; transition: 
            0.3s; min-width: 100px; display: 
            flex; align-items: center; 
            justify-content: center;
        }
        .dashboard-button:hover { background: 
            #0056b3;
            transform: translateY(-2px); color: 
            #FFF;
        }
        .course-grid { display: grid; 
            grid-template-columns: 
            repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 20px;
        }
        .course-card { background: #2B1B17; 
            border: 2px solid #FFD700; padding: 
            20px; border-radius: 12px; 
            transition: transform 0.3s;
        }
        .course-card:hover { transform: 
            translateY(-5px);
        }
        .course-card h2 { font-size: 24px; 
            text-transform: uppercase; color: 
            #FFD700;
        }
        .course-card .media-container { 
            margin-top: 20px; width: 100%; 
            border-radius: 8px; overflow: hidden;
        }
        .course-card .media-container video { 
            width: 100%; border-radius: 8px; 
            display: block;
        }
        .button { display: inline-block; padding: 
            14px 24px; font-size: 16px; 
            font-weight: bold; border-radius: 
            12px; text-align: center; 
            text-decoration: none; transition: 
            all 0.3s ease-in-out; box-shadow: 0px 
            4px 8px rgba(0,0,0,0.2); width: 100%; 
            margin-bottom: 10px;
        }
        .button-yellow { background: 
            linear-gradient(135deg, #ffcc00, 
            #e6a700);
            color: #1a1a1a; border: none;
        }
        .button-yellow:hover { background: 
            linear-gradient(135deg, #ffd633, 
            #f2b000);
            transform: scale(1.05);
        }
        .button-gray { background-color: #a0a5b0; 
            color: #fff; border: none;
        }
        .button-gray:hover { background-color: 
            #c0c5d0;
            transform: scale(1.05);
        }
        .button-container { display: flex; 
            flex-direction: column; gap: 10px; 
            margin-top: 10px;
        }
        @media (max-width: 768px) { .container { 
                flex-direction: column;
            }
            .dashboard { width: auto;
            }
            .dashboard-buttons { flex-direction: 
                row;
            }
            .dashboard-button { flex: 1 1 
                calc(50% - 10px);
            }
            .course-card .media-container { 
                max-width: 100%; margin: 20px 
                auto;
            }
            .course-card .media-container video { 
                width: 100%; height: auto;
            }
        }
    </style> </head> <body> <div class="header 
    wow fadeInDown" data-wow-duration="1s">
        <h1>English.Online24 | پلتفرم تخصصی آموزش 
        زبان انگلیسی</h1>
    </div> <div class="container"> <div 
        class="dashboard wow fadeInLeft" 
        data-wow-duration="1s">
            <h3>پنل کاربری</h3> <?php 
            if(!empty($user_id) && $user_id != 
            'all'): ?>
                <div class="user-info"> 
                    <p><strong>نام 
                    کاربری:</strong> <?= 
                    htmlspecialchars($username ?? 
                    "") ?></p> <p><strong>کد 
                    کاربری:</strong> <?= 
                    htmlspecialchars($user_id ?? 
                    "") ?></p>
                </div> <?php endif; ?> <div 
            class="dashboard-buttons">
                <a href="profile.php" 
                class="dashboard-button"><i 
                class="fas fa-user"></i> 
                پروفایل</a> <a 
                href="settings.php" 
                class="dashboard-button"><i 
                class="fas fa-cog"></i> 
                تنظیمات</a> <a 
                href="history_purchase.php" 
                class="dashboard-button"><i 
                class="fas fa-history"></i> 
                تاریخچه خرید</a> <a 
                href="logout.php" 
                class="dashboard-button"><i 
                class="fas fa-sign-out-alt"></i> 
                خروج</a>
            </div> </div> <div 
        class="course-container">
            <div class="course-grid"> <?php 
                foreach($lessons as $lesson): ?>
                    <div class="course-card wow 
                    fadeInUp" 
                    data-wow-duration="1s">
                        <h2>درس <?= $lesson 
                        ?></h2> <div 
                        class="media-container">
                            <?php if($lesson <= 
                            count($course_videos)): 
                            ?>
                                <video controls> 
                                    <source 
                                    src="<?= 
                                    $course_videos[$lesson-1] 
                                    ?>" 
                                    type="video/mp4">
                                </video> <?php 
                            endif; ?>
                        </div> <div 
                        class="button-container">
                            <?php 
                            if(!$locked_lessons[$lesson]): 
                            ?>
                                <a 
                                href="lesson<?= 
                                $lesson ?>.html" 
                                class="button 
                                button-yellow">شروع 
                                یادگیری</a> <a 
                                href="grammar<?= 
                                $lesson ?>.html" 
                                class="button 
                                button-gray">گرامر 
                                درس <?= $lesson 
                                ?></a> <a 
                                href="vocab<?= 
                                $lesson ?>.html" 
                                class="button 
                                button-gray">لغت 
                                درس <?= $lesson 
                                ?></a>
                            <?php else: ?> <?php 
                                if($lesson >= 6 
                                && $lesson <= 
                                12): ?>
                                    <a 
                                    href="purchase.php?lesson=<?= 
                                    $lesson ?>" 
                                    class="button 
                                    button-yellow">خرید 
                                    دوره</a> <a 
                                    href="purchase.php?lesson=<?= 
                                    $lesson 
                                    ?>&type=grammar" 
                                    class="button 
                                    button-gray">خرید 
                                    گرامر</a> <a 
                                    href="purchase.php?lesson=<?= 
                                    $lesson 
                                    ?>&type=vocabulary" 
                                    class="button 
                                    button-gray">خرید 
                                    لغت</a>
                                <?php endif; ?> 
                            <?php endif; ?>
                        </div> </div> <?php 
                endforeach; ?>
            </div> </div> </div> <footer 
    style="text-align:center;margin-top:20px;">
        <div class="contact" style="margin:20px 
        0;font-size:1.1em;">
            پشتیبانی تلگرام: <a 
            href="https://t.me/rosegold181" 
            style="color:#0088cc;text-decoration:none">@rosegold181</a>
        </div> <p>© <?= date('Y') ?> 
        English.Online24</p> <div 
        style="text-align:center;padding:20px;margin-top:30px;border-top:1px 
        solid rgba(255,215,0,0.2);">
            <div 
            style="font-size:12px;color:#888;margin-bottom:15px;">
                🔔 در صورت قطعی موقت نگران نباشید 
                - سایت در حال بروزرسانی و افزودن 
                امکانات جدید است
            </div> </div> </footer> <script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
    <script 
    src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script> 
    <script>new WOW().init();</script>
</body>
</html>
