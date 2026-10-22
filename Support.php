<?php
session_start();
require_once 'csrf.php';

// هذي الصفحة تتطلب تسجيل دخول — بدون جلسة صحيحة يرجع المستخدم لصفحة الدخول
if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error   = '';
$success = isset($_GET['success']) && $_GET['success'] === '1';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        $error = 'انتهت صلاحية الجلسة، يرجى إعادة تحميل الصفحة والمحاولة مرة أخرى.';
    } elseif (!empty($_POST['website'] ?? '')) {
        // حقل فخ (honeypot) ضد البوتات
        $error = 'حدث خطأ. يرجى المحاولة مرة أخرى.';
    } else {

        // منع إرسال طلبات متكررة بسرعة (طلب واحد كل 30 ثانية لنفس الجلسة)
        $lastSubmit = $_SESSION['last_support_submit'] ?? 0;
        if ((time() - $lastSubmit) < 30) {
            $error = 'يرجى الانتظار قليلاً قبل إرسال طلب آخر.';
        } else {
            $name    = trim($_POST['name']    ?? '');
            $phone   = trim($_POST['phone']   ?? '');
            $email   = trim($_POST['email']   ?? '');
            $message = trim($_POST['message'] ?? '');

            if (empty($name) || empty($phone) || empty($email) || empty($message)) {
                $error = 'Please fill in all required fields.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Please enter a valid email address.';
            } elseif (!preg_match('/^[0-9+\s-]{7,20}$/', $phone)) {
                $error = 'Please enter a valid phone number.';
            } else {
                require_once 'config.php';

                $userId = $_SESSION['user_id'];
                $stmt = $conn->prepare("INSERT INTO support_requests (user_id, name, phone, email, message) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $userId, $name, $phone, $email, $message);

                if ($stmt->execute()) {
                    $_SESSION['last_support_submit'] = time();
                    $stmt->close();
                    $conn->close();
                    header("Location: Support.php?success=1");
                    exit;
                } else {
                    $error = 'An error occurred. Please try again.';
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
<title>Technical Support — TechSupport</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar" id="navbar">
  <a href="index.html" class="logo"><i class="fa fa-headset" style="color:var(--orange);margin-right:.3rem;font-size:1rem"></i>Tech<span>Support</span></a>
  <div class="nav-links" id="navLinks">
    <a href="index.html"><span data-en="Home" data-ar="الرئيسية"></span></a>
    <a href="self.php"><span data-en="Self-Help" data-ar="المساعدة الذاتية"></span></a>
    <a href="tips.html"><span data-en="Tips" data-ar="نصائح"></span></a>
    <a href="maintenance.html"><span data-en="Maintenance" data-ar="الصيانة"></span></a>
    <a href="ai.php"><span data-en="AI Assistant" data-ar="المساعد الذكي"></span></a>
    <a href="Support.php" class="active"><span data-en="Support" data-ar="الدعم الفني"></span></a>
    <button id="langToggle" class="lang-btn"><i class="fa fa-globe"></i> عربي</button>
    <div id="navUser"></div>
  </div>
  <button class="hamburger" onclick="toggleNav()"><i class="fa fa-bars"></i></button>
</nav>
<div class="sub-hero">
  <span class="section-label" data-en="We're Here to Help" data-ar="احنا هنا لمساعدتك"></span>
  <h1><span data-en="Technical" data-ar="الدعم"></span> <span><span data-en="Support" data-ar="الفني"></span></span></h1>
  <p data-en="Fill in the form and our team will get back to you within 24 hours." data-ar="عبي الفورم وفريقنا بيتواصل معك خلال 24 ساعة."></p>
</div>
<section style="padding-top:0">
  <div class="container">
    <div class="success-msg" id="successMsg" style="<?php echo $success ? 'display:block' : ''; ?>">
      <i class="fa fa-circle-check"></i>
      <h3 data-en="Request Sent Successfully!" data-ar="تم إرسال الطلب بنجاح!"></h3>
      <p data-en="Thank you for reaching out. Our team will contact you within 24 hours." data-ar="شكرًا لتواصلك. فريقنا بيتواصل معك خلال 24 ساعة."></p>
      <a href="index.html" class="btn-primary" style="margin-top:1.25rem;display:inline-flex"><span data-en="Back to Home" data-ar="العودة للرئيسية"></span></a>
    </div>
    <div class="support-wrap" id="supportForm" style="<?php echo $success ? 'display:none' : ''; ?>">
      <h3 style="font-family:'Syne',sans-serif;font-size:1.2rem;font-weight:700;margin-bottom:.25rem" data-en="Submit a Request" data-ar="أرسل طلبك"></h3>
      <p style="color:var(--text2);font-size:.82rem;margin-bottom:1.6rem" data-en="All fields required. We respond within 24 hours." data-ar="كل الحقول مطلوبة. نرد خلال 24 ساعة."></p>
      <?php if ($error): ?>
      <p style="color:#f87171;font-size:.82rem;margin-bottom:1rem"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
      <form action="Support.php" method="POST">
        <?php echo csrf_field(); ?>
        <input type="text" name="website" value="" style="position:absolute;left:-9999px;top:-9999px" tabindex="-1" autocomplete="off">
        <div class="form-group"><label data-en="Full Name" data-ar="الاسم الكامل"></label><input type="text" name="name" placeholder="Enter your full name" data-en-ph="Enter your full name" data-ar-ph="اكتب اسمك الكامل" required></div>
        <div class="form-group"><label data-en="Phone Number" data-ar="رقم الهاتف"></label><input type="tel" name="phone" placeholder="+966 5** *** ****" required></div>
        <div class="form-group"><label data-en="Email Address" data-ar="البريد الإلكتروني"></label><input type="email" name="email" placeholder="techsupport.sa@gmail.com" required></div>
        <div class="form-group"><label data-en="Describe Your Issue" data-ar="اوصف مشكلتك"></label><textarea name="message" placeholder="Describe the problem in detail..." data-en-ph="Describe the problem in detail..." data-ar-ph="اوصف المشكلة بالتفصيل..." required></textarea></div>
        <button type="submit" class="submit-btn"><i class="fa fa-paper-plane"></i> <span data-en="Send Request" data-ar="إرسال الطلب"></span></button>
      </form>
    </div>
  </div>
</section>
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div><a href="index.html" class="logo"><i class="fa fa-headset" style="color:var(--orange);margin-right:.3rem;font-size:1rem"></i>Tech<span>Support</span></a><p data-en="Your reliable partner for all computer support needs." data-ar="شريكك الموثوق لكل احتياجات الدعم الفني."></p></div>
      <div class="footer-col"><h4 data-en="Pages" data-ar="الصفحات"></h4><a href="index.html"><span data-en="Home" data-ar="الرئيسية"></span></a><a href="self.php"><span data-en="Self-Help" data-ar="المساعدة الذاتية"></span></a><a href="maintenance.html"><span data-en="Maintenance" data-ar="الصيانة"></span></a><a href="ai.php"><span data-en="AI Assistant" data-ar="المساعد الذكي"></span></a></div>
      <div class="footer-col"><h4 data-en="Contact" data-ar="تواصل معنا"></h4><p><i class="fa fa-phone"></i> +966558230367</p><p><i class="fa fa-envelope"></i> hooooxx551@gmail.com</p></div>
    </div>
    <div class="footer-bottom"><p data-en="© 2025 TechSupport. All rights reserved." data-ar="© 2025 TechSupport. جميع الحقوق محفوظة."></p></div>
  </div>
</footer>
<script>
window.addEventListener('scroll',()=>document.getElementById('navbar').classList.toggle('scrolled',window.scrollY>50));
function toggleNav(){document.getElementById('navLinks').classList.toggle('open')}
</script>

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
