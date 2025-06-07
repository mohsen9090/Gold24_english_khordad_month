<?php session_start(); require_once 
'vendor/autoload.php'; $dotenv = 
Dotenv\Dotenv::createImmutable(__DIR__); 
$dotenv->load(); if 
(!isset($_SESSION['user_id'])) {
    header("Location:login.php"); exit();
}
try { $conn = new mysqli($_ENV['DB_HOST'], 
    $_ENV['DB_USER'], $_ENV['DB_PASS'], 
    $_ENV['DB_NAME']); if ($conn->connect_error) 
    {
        throw new Exception("خطا در اتصال به 
        دیتابیس");
    }
    $conn->set_charset("utf8mb4");
    
    $user_id = $_SESSION['user_uid'] ?? 'all'; 
    $username = $_SESSION['user_name'] ?? null; 
    $lessons = range(1, 12);
    
    // همه دروس آزاد
    $locked_lessons = array_fill(1, 12, false);
    
} catch (Exception $e) {
    error_log($e->getMessage()); die("خطا در 
    اتصال به دیتابیس");
}
// ویدیوهای کورس
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
]; $lesson_titles = [ 1 => 'مقدمات و خوش‌آمدگویی', 
    2 => 'معرفی خود و دیگران', 3 => 'خانواده و 
    روابط', 4 => 'فعالیت‌های روزانه', 5 => 'غذا و 
    نوشیدنی', 6 => 'خرید و بازار', 7 => 'سفر و 
    گردشگری', 8 => 'کار و تحصیل', 9 => 'سلامتی و 
    پزشکی', 10 => 'ورزش و سرگرمی', 11 => 'فناوری 
    و اینترنت', 12 => 'محیط زیست و طبیعت'
]; ?> <!DOCTYPE html> <html lang="fa" dir="rtl"> 
<head>
    <meta charset="UTF-8"> <meta name="viewport" 
    content="width=device-width, 
    initial-scale=1.0"> <title>آموزش زبان انگلیسی 
    | English.Online24</title>
    <meta name="description" content="یادگیری 
    زبان انگلیسی با جدیدترین روش‌ها! همه دروس 
    آزاد">
    
    <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
    rel="stylesheet"> <link rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
    <link rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style> * { margin: 0; padding: 0; 
            box-sizing: border-box;
        }
        body { background: #000000; font-family: 
            'Tahoma', 'IRANSans', sans-serif; 
            color: #f0f0f0; padding: 20px; 
            margin: 0; min-height: 100vh;
        }
        .header { background: linear-gradient(to 
            right, #8B0000, #A52A2A); padding: 
            20px; border-radius: 15px; 
            text-align: center; color: white; 
            box-shadow: 0 4px 15px 
            rgba(139,0,0,0.3); margin-bottom: 
            30px;
        }
        .header h1 { margin: 0; font-size: 
            2.2rem;
        }
        .header p { margin-top: 10px; font-size: 
            1.1em; color: #FFD700;
        }
        .success-alert { background: 
            linear-gradient(135deg, #28a745, 
            #20c997);
            color: white; padding: 15px; 
            border-radius: 10px; text-align: 
            center; font-weight: bold; 
            margin-bottom: 20px; animation: pulse 
            2s infinite;
        }
        @keyframes pulse { 0%, 100% { transform: 
            scale(1); } 50% { transform: 
            scale(1.02); }
        }
        .container { display: flex; gap: 25px; 
            padding: 20px;
        }
        .dashboard { width: 280px; background: 
            rgba(44,31,26,0.9); padding: 25px; 
            border-radius: 15px; box-shadow: 0 
            4px 20px rgba(0,0,0,0.3); border: 1px 
            solid rgba(255,215,0,0.1); height: 
            fit-content;
        }
        .dashboard h3 { color: #FFD700; 
            margin-bottom: 20px; font-size: 
            1.5rem; text-shadow: 0 2px 4px 
            rgba(0,0,0,0.3);
        }
        .user-info { background: rgba(255, 215, 
            0, 0.1); padding: 15px; 
            border-radius: 10px; margin-bottom: 
            20px; border: 1px solid rgba(255, 
            215, 0, 0.3);
        }
        .user-info p { margin: 5px 0; color: 
            #f0f0f0;
        }
        .dashboard-buttons { display: flex; gap: 
            12px; flex-wrap: wrap; margin-bottom: 
            20px;
        }
        .dashboard-button { flex: 1; background: 
            linear-gradient(135deg, #007bff, 
            #0056b3);
            color: #FFF; padding: 18px 24px; 
            border-radius: 10px; text-decoration: 
            none; text-align: center; transition: 
            all 0.3s ease; min-width: 110px; 
            display: flex; align-items: center; 
            justify-content: center; box-shadow: 
            0 4px 10px rgba(0,123,255,0.2);
        }
        .dashboard-button:hover { transform: 
            translateY(-3px); background: 
            linear-gradient(135deg, #0056b3, 
            #004494);
            color: #FFF; box-shadow: 0 6px 15px 
            rgba(0,123,255,0.3);
        }
        .course-grid { display: grid; 
            grid-template-columns: 
            repeat(auto-fit, minmax(340px, 1fr)); 
            gap: 25px; justify-content: center;
        }
        .course-card { background: #1a1a1a; 
            border: 2px solid 
            rgba(255,215,0,0.3); padding: 25px; 
            border-radius: 15px; transition: all 
            0.3s ease; box-shadow: 0 4px 20px 
            rgba(0,0,0,0.2); position: relative; 
            cursor: pointer;
        }
        .course-card:hover { transform: 
            translateY(-5px); box-shadow: 0 8px 
            25px rgba(0,0,0,0.3); border-color: 
            #FFD700;
        }
        .course-card h2 { font-size: 24px; 
            text-transform: uppercase; color: 
            #FFD700;
            margin-bottom: 20px; text-shadow: 0 
            2px 4px rgba(0,0,0,0.3);
        }
        .course-card-header { display: flex; 
            justify-content: space-between; 
            align-items: center; margin-bottom: 
            15px;
        }
        .lesson-subtitle { font-size: 0.9rem; 
            color: #ccc; margin-top: 5px;
        }
        .status-tag { background: 
            linear-gradient(135deg, #28a745, 
            #20c997);
            color: white; padding: 6px 12px; 
            border-radius: 15px; font-size: 14px; 
            font-weight: bold; display: flex; 
            align-items: center; gap: 5px;
        }
        .media-container { margin: 20px 0; width: 
            100%; border-radius: 12px; overflow: 
            hidden; box-shadow: 0 4px 15px 
            rgba(0,0,0,0.3);
        }
        .media-container video { width: 100%; 
            border-radius: 12px; display: block; 
            background: #000;
        }
        .button { display: inline-block; padding: 
            16px 28px; font-size: 16px; 
            font-weight: bold; border-radius: 
            12px; text-align: center; 
            text-decoration: none; transition: 
            all 0.3s ease; box-shadow: 0 4px 12px 
            rgba(0,0,0,0.2); width: 100%; 
            margin-bottom: 12px; display: flex; 
            align-items: center; justify-content: 
            center; gap: 8px;
        }
        .button:hover { transform: scale(1.03); 
            text-decoration: none;
        }
        .button-yellow { background: 
            linear-gradient(135deg, #FFD700, 
            #FFA500);
            color: #121212; border: none;
        }
        .button-yellow:hover { background: 
            linear-gradient(135deg, #FFA500, 
            #FF8C00);
            color: #121212; box-shadow: 0 6px 
            15px rgba(255,215,0,0.3);
        }
        .button-blue { background: 
            linear-gradient(135deg, #007bff, 
            #0056b3);
            color: #fff; border: none;
        }
        .button-blue:hover { background: 
            linear-gradient(135deg, #0056b3, 
            #004494);
            color: #fff; box-shadow: 0 6px 15px 
            rgba(0,123,255,0.3);
        }
        .button-purple { background: 
            linear-gradient(135deg, #9C27B0, 
            #673AB7);
            color: #fff; border: none;
        }
        .button-purple:hover { background: 
            linear-gradient(135deg, #673AB7, 
            #512DA8);
            color: #fff; box-shadow: 0 6px 15px 
            rgba(156,39,176,0.3);
        }
        .button-green { background: 
            linear-gradient(135deg, #00C853, 
            #009624);
            color: #fff; border: none;
        }
        .button-green:hover { background: 
            linear-gradient(135deg, #009624, 
            #007722);
            color: #fff; box-shadow: 0 6px 15px 
            rgba(0,200,83,0.3);
        }
        .button-red { background: 
            linear-gradient(135deg, #F44336, 
            #E53935);
            color: #fff; border: none;
        }
        .button-red:hover { background: 
            linear-gradient(135deg, #E53935, 
            #C62828);
            color: #fff; box-shadow: 0 6px 15px 
            rgba(244,67,54,0.3);
        }
        .button-container { display: grid; 
            grid-template-columns: 
            repeat(auto-fit, minmax(150px, 1fr)); 
            gap: 12px; margin-top: 20px;
        }
        .show-more-btn { position: absolute; 
            bottom: 10px; left: 50%; transform: 
            translateX(-50%); text-align: center; 
            padding: 10px 20px; background: 
            linear-gradient(135deg, #6c757d, 
            #495057);
            cursor: pointer; border: none; 
            border-radius: 20px; color: #fff; 
            font-weight: bold; transition: all 
            0.3s ease;
        }
        .show-more-btn:hover { background: 
            linear-gradient(135deg, #495057, 
            #343a40);
            transform: translateX(-50%) 
            translateY(-2px);
        }
        @media(max-width:768px) { .container { 
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
            
            .button-container { 
                grid-template-columns: 1fr;
            }
        }
    </style> </head> <body> <!-- Header --> <div 
    class="header wow fadeInDown" 
    data-wow-duration="1s">
        <h1><i class="fas fa-graduation-cap"></i> 
        English.Online24 | آموزش زبان 
        انگلیسی</h1> <p>پشتیبانی ۲۴ ساعته در <a 
        href="https://t.me/rosegold181" 
        target="_blank" 
        style="color:#fff;text-decoration:underline;">تلگرام</a></p>
    </div> <!-- Success Alert --> <div 
    class="success-alert">
        <i class="fas fa-unlock-alt"></i> تبریک! 
        همه دروس آزاد شده - دسترسی کامل فعال <i 
        class="fas fa-crown"></i>
    </div> <!-- Main Container --> <div 
    class="container">
        <!-- Dashboard --> <div class="dashboard 
        wow fadeInLeft" data-wow-duration="1s">
            <h3><i class="fas 
            fa-user-circle"></i> پنل کاربری</h3>
            
            <?php if (!empty($user_id) && 
            $user_id != 'all'): ?> <div 
            class="user-info">
                <p><strong><i class="fas 
                fa-user"></i> نام 
                کاربری:</strong> <?= 
                htmlspecialchars($username ?? 
                "کاربر") ?></p> <p><strong><i 
                class="fas fa-id-card"></i> کد 
                کاربری:</strong> <?= 
                htmlspecialchars($user_id ?? "") 
                ?></p> <p><strong><i class="fas 
                fa-crown"></i> وضعیت:</strong> 
                <span style="color: 
                #FFD700;">Premium 
                Active</span></p> </div> <?php 
            endif; ?>
            
            <div class="dashboard-buttons"> <a 
                href="profile.php" 
                class="dashboard-button">
                    <i class="fas fa-user"></i> 
                    پروفایل
                </a> <a href="settings.php" 
                class="dashboard-button">
                    <i class="fas fa-cog"></i> 
                    تنظیمات
                </a> <a 
                href="history_purchase.php" 
                class="dashboard-button">
                    <i class="fas 
                    fa-history"></i> تاریخچه خرید
                </a> <a href="logout.php" 
                class="dashboard-button">
                    <i class="fas 
                    fa-sign-out-alt"></i> خروج
                </a> </div> </div> <!-- Course 
        Container --> <div 
        class="course-container">
            <div class="course-grid"> <?php 
                foreach ($lessons as $lesson): ?> 
                <div class="course-card wow 
                fadeInUp" data-wow-duration="1s" 
                data-lesson="<?= $lesson ?>">
                    <div 
                    class="course-card-header">
                        <div> <h2><i class="fas 
                            fa-book-open"></i> 
                            درس <?= $lesson 
                            ?></h2> <div 
                            class="lesson-subtitle"><?= 
                            $lesson_titles[$lesson] 
                            ?? 'موضوع تخصصی' 
                            ?></div>
                        </div> <div 
                        class="status-tag">
                            <i class="fas 
                            fa-unlock"></i> آزاد
                        </div> </div>
                    
                    <div 
                    class="course-card-content">
                        <!-- Video Section --> 
                        <?php if ($lesson <= 
                        count($course_videos)): 
                        ?> <div 
                        class="media-container">
                            <video controls 
                            preload="metadata">
                                <source src="<?= 
                                $course_videos[$lesson 
                                - 1] ?>" 
                                type="video/mp4"> 
                                مرورگر شما از 
                                ویدیو پشتیبانی 
                                نمی‌کند.
                            </video> </div> <?php 
                        endif; ?>
                        
                        <!-- Action Buttons --> 
                        <div 
                        class="button-container">
                            <a href="lesson<?= 
                            $lesson ?>.html" 
                            class="button 
                            button-yellow">
                                <i class="fas 
                                fa-play"></i> 
                                شروع یادگیری
                            </a> <a 
                            href="conversation<?= 
                            $lesson ?>.html" 
                            class="button 
                            button-blue">
                                <i class="fas 
                                fa-comments"></i> 
                                مکالمه
                            </a> <a 
                            href="grammar<?= 
                            $lesson ?>.html" 
                            class="button 
                            button-blue">
                                <i class="fas 
                                fa-language"></i> 
                                گرامر
                            </a> <a 
                            href="slang_lesson<?= 
                            $lesson ?>.html" 
                            class="button 
                            button-purple">
                                <i class="fas 
                                fa-quote-left"></i> 
                                اصطلاحات
                            </a> <a href="test<?= 
                            $lesson ?>.html" 
                            class="button 
                            button-red">
                                <i class="fas 
                                fa-tasks"></i> 
                                تست آموزشی
                            </a> <a 
                            href="vocab<?= 
                            $lesson ?>.html" 
                            class="button 
                            button-green">
                                <i class="fas 
                                fa-spell-check"></i> 
                                لغت
                            </a> <a 
                            href="IELTS<?= 
                            $lesson ?>.html" 
                            class="button 
                            button-green">
                                <i class="fas 
                                fa-award"></i> 
                                تمرین IELTS
                            </a> </div> </div>
                    
                    <button class="show-more-btn" 
                    onclick="toggleCard(this, <?= 
                    $lesson ?>)">
                        <i class="fas 
                        fa-chevron-up"></i> بستن
                    </button> </div> <?php 
                endforeach; ?>
            </div> </div> </div> <script>
        // Initialize cards as expanded
        document.addEventListener('DOMContentLoaded', 
        function() {
            const cards = 
            document.querySelectorAll('.course-card');
            
            cards.forEach(card => {
                // Set initial state as expanded
                card.classList.add('expanded'); 
                const content = 
                card.querySelector('.course-card-content'); 
                if (content) {
                    content.style.opacity = '1'; 
                    content.style.height = 
                    'auto'; 
                    content.style.overflow = 
                    'visible';
                }
            });
            // Add click feedback to buttons
            document.querySelectorAll('.button').forEach(button 
            => {
                button.addEventListener('click', 
                function(e) {
                    console.log('Button 
                    clicked:', 
                    this.textContent.trim());
                    
                    // Visual feedback
                    const originalBg = 
                    this.style.background; 
                    this.style.transform = 
                    'scale(0.95)';
                    
                    setTimeout(() => { 
                        this.style.transform = 
                        'scale(1.03)';
                    }, 100);
                    
                    setTimeout(() => { 
                        this.style.transform = 
                        '';
                    }, 300);
                });
            });
            console.log('Platform loaded - All 
            lessons unlocked and visible');
        });
        // Card toggle function
        function toggleCard(btn, lessonNum) { 
            const card = 
            btn.closest('.course-card'); const 
            content = 
            card.querySelector('.course-card-content'); 
            const isExpanded = 
            card.classList.contains('expanded');
            
            if (isExpanded) {
                // Collapse
                card.classList.remove('expanded'); 
                content.style.opacity = '0'; 
                content.style.height = '0'; 
                content.style.overflow = 
                'hidden'; btn.innerHTML = '<i 
                class="fas fa-chevron-down"></i> 
                نمایش بیشتر';
            } else {
                // Expand
                card.classList.add('expanded'); 
                content.style.opacity = '1'; 
                content.style.height = 'auto'; 
                content.style.overflow = 
                'visible'; btn.innerHTML = '<i 
                class="fas fa-chevron-up"></i> 
                بستن';
                
                // Load video if not loaded
                const video = 
                content.querySelector('video'); 
                if (video && 
                !video.hasAttribute('data-loaded')) 
                { video.load(); 
                    video.setAttribute('data-loaded', 
                    'true');
                }
            }
        }
        // Add WOW.js initialization if available
        if (typeof WOW !== 'undefined') { new 
            WOW().init();
        }
    </script> <!-- Load WOW.js for animations --> 
    <script 
    src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script> 
    <script>
        new WOW().init(); </script> </body>
</html>
