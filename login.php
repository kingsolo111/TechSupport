<?php
session_start();
require_once 'csrf.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // حماية CSRF
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        $error = 'انتهت صلاحية الجلسة، يرجى إعادة تحميل الصفحة والمحاولة مرة أخرى.';
    } else {

        // تقييد بسيط لمحاولات الدخول المتكررة (حماية أولية من brute-force)
        $_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? 0;
        $_SESSION['login_last_try'] = $_SESSION['login_last_try'] ?? 0;

        if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['login_last_try']) < 60) {
            $error = 'محاولات دخول كثيرة جدًا. يرجى الانتظار دقيقة والمحاولة مرة أخرى.';
        } else {
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($email) || empty($password)) {
                $error = 'يرجى تعبئة جميع الحقول.';
            } else {
                require_once 'config.php';

                $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                $ok = false;
                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    if (password_verify($password, $row['password'])) {
                        $ok = true;
                        session_regenerate_id(true);
                        $_SESSION['user_id']        = $row['id'];
                        $_SESSION['user_name']      = $row['name'];
                        $_SESSION['login_attempts'] = 0;
                    }
                }
                $stmt->close();
                $conn->close();

                if ($ok) {
                    header("Location: index.html");
                    exit;
                }

                $_SESSION['login_attempts']++;
                $_SESSION['login_last_try'] = time();
                $error = 'بيانات الدخول غير صحيحة.';
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
<title>Login — TechSupport</title>
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
    <h2><span data-en="Welcome" data-ar="أهلاً"></span> <span><span data-en="Back" data-ar="بعودتك"></span></span></h2>
    <p class="auth-sub" data-en="Sign in to your TechSupport account" data-ar="سجّل دخولك لحسابك بـTechSupport"></p>
    <?php if ($error): ?>
    <p style="color:#f87171;font-size:.82rem;text-align:center;margin-bottom:1rem"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form class="auth-form" action="login.php" method="POST">
      <?php echo csrf_field(); ?>
      <div class="form-group"><label data-en="Email Address" data-ar="البريد الإلكتروني"></label><input type="email" name="email" placeholder="your@email.com" required></div>
      <div class="form-group"><label data-en="Password" data-ar="كلمة المرور"></label><input type="password" name="password" placeholder="Enter your password" data-en-ph="Enter your password" data-ar-ph="اكتب كلمة المرور" required></div>
      <button type="submit" class="auth-submit" data-en="Sign In" data-ar="تسجيل الدخول"></button>
    </form>
    <p class="auth-footer"><span data-en="Don't have an account?" data-ar="ما عندك حساب؟"></span> <a href="signup.php"><span data-en="Create one" data-ar="أنشئ واحد"></span></a></p>
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
