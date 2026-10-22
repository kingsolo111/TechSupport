<?php
session_start();
require_once 'csrf.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        $error = 'انتهت صلاحية الجلسة، يرجى إعادة تحميل الصفحة والمحاولة مرة أخرى.';
    } elseif (!empty($_POST['website'] ?? '')) {
        // حقل فخ (honeypot) — أي إنسان حقيقي ما يملأه، البوتات تملأه تلقائيًا
        $error = 'حدث خطأ أثناء التسجيل.';
    } else {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            $error = 'يرجى تعبئة جميع الحقول.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'يرجى إدخال بريد إلكتروني صحيح.';
        } elseif (strlen($password) < 8) {
            $error = 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.';
        } else {
            require_once 'config.php';

            $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $check_result = $check->get_result();
            $check->close();

            if ($check_result->num_rows > 0) {
                $error = 'البريد الإلكتروني مستخدم بالفعل.';
                $conn->close();
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $hashed_password);

                if ($stmt->execute()) {
                    $stmt->close();
                    $conn->close();
                    header("Location: login.php");
                    exit;
                } else {
                    $error = 'حدث خطأ أثناء التسجيل.';
                    $stmt->close();
                    $conn->close();
                }
            }
        }
    }
}

$loggedInName = $_SESSION['user_name'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up — TechSupport</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-page">
  <div class="auth-glow ag1"></div>
  <div class="auth-glow ag2"></div>
  <div class="auth-card">
    <div style="text-align:center;margin-bottom:.5rem"><button id="langToggle" class="lang-btn" style="font-size:.75rem"><i class="fa fa-globe"></i> عربي</button></div>
    <h2><span data-en="Create" data-ar="أنشئ"></span> <span><span data-en="Account" data-ar="حساب"></span></span></h2>
    <p class="auth-sub" data-en="Join TechSupport and get instant help" data-ar="انضم لـTechSupport واحصل على مساعدة فورية"></p>
    <?php if ($error): ?>
    <p style="color:#f87171;font-size:.82rem;text-align:center;margin-bottom:1rem"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form class="auth-form" action="signup.php" method="POST">
      <?php echo csrf_field(); ?>
      <input type="text" name="website" value="" style="position:absolute;left:-9999px;top:-9999px" tabindex="-1" autocomplete="off">
      <div class="form-group"><label data-en="Full Name" data-ar="الاسم الكامل"></label><input type="text" name="name" placeholder="Enter your name" data-en-ph="Enter your name" data-ar-ph="اكتب اسمك" required></div>
      <div class="form-group"><label data-en="Email Address" data-ar="البريد الإلكتروني"></label><input type="email" name="email" placeholder="your@email.com" required></div>
      <div class="form-group"><label data-en="Password" data-ar="كلمة المرور"></label><input type="password" name="password" placeholder="Min 8 characters" data-en-ph="Min 8 characters" data-ar-ph="8 أحرف على الأقل" required minlength="8"></div>
      <button type="submit" class="auth-submit" data-en="Create Account" data-ar="إنشاء حساب"></button>
    </form>
    <p class="auth-footer"><span data-en="Already have an account?" data-ar="عندك حساب؟"></span> <a href="login.php"><span data-en="Sign in" data-ar="سجّل دخول"></span></a></p>
    <p class="auth-footer" style="margin-top:.4rem"><a href="index.html"><i class="fa fa-arrow-left"></i> <span data-en="Back to Home" data-ar="العودة للرئيسية"></span></a></p>
  </div>
</div>

<script>
const user = <?php echo json_encode($loggedInName); ?>;
const navUser=document.getElementById('navUser');
if(navUser){
  if(user){
    localStorage.setItem('ts_user', user);
    navUser.innerHTML=`<span style="color:var(--orange);font-size:.85rem;font-weight:600"><i class="fa fa-user"></i> ${user}</span> <a href="my-requests.php" style="color:var(--text2);font-size:.8rem;margin-left:.5rem">My Requests</a> <a href="logout.php" style="color:var(--text2);font-size:.8rem;margin-left:.5rem">Logout</a>`;
  }else{
    localStorage.removeItem('ts_user');
    navUser.innerHTML='<a href="login.php" class="nav-btn">Login</a>';
  }
}
</script>

<script src="lang.js"></script>
</body>
</html>
