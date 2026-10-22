<?php
session_start();

// هذي الصفحة تتطلب تسجيل دخول
if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$loggedInName = $_SESSION['user_name'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AI Assistant — TechSupport</title>
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
    <a href="ai.php" class="active"><span data-en="AI Assistant" data-ar="المساعد الذكي"></span></a>
    <a href="Support.php"><span data-en="Support" data-ar="الدعم الفني"></span></a>
    <button id="langToggle" class="lang-btn"><i class="fa fa-globe"></i> عربي</button>
    <div id="navUser"></div>
  </div>
  <button class="hamburger" onclick="toggleNav()"><i class="fa fa-bars"></i></button>
</nav>

<div class="sub-hero">
  <span class="section-label" data-en="Powered by AI" data-ar="مدعوم بالذكاء الاصطناعي"></span>
  <h1><span data-en="AI" data-ar="المساعد"></span> <span><span data-en="Assistant" data-ar="الذكي"></span></span></h1>
  <p data-en="Pick your problem and the bot will help instantly" data-ar="اختر مشكلتك وسيساعدك البوت فوراً"></p>
</div>

<section style="padding-top:0">
  <div class="container">
    <div style="max-width:700px;margin:0 auto">
      <h2 style="text-align:center;margin-bottom:.5rem">🔍 <span data-en="Pick Your Problem" data-ar="اختر مشكلتك"></span></h2>
      <p style="text-align:center;color:var(--text2);margin-bottom:2rem" data-en="Click any problem and the bot will open and help you directly" data-ar="اضغط على أي مشكلة وسيفتح البوت ويساعدك مباشرة"></p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem">
        <button class="diag-btn" onclick="askBot('الكمبيوتر بطيء، كيف أسرعه؟')"><i class="fa fa-gauge-high"></i><span data-en="Slow Computer" data-ar="الكمبيوتر بطيء"></span></button>
        <button class="diag-btn" onclick="askBot('ما عندي إنترنت، كيف أحل المشكلة؟')"><i class="fa fa-wifi"></i><span data-en="No Internet" data-ar="لا يوجد إنترنت"></span></button>
        <button class="diag-btn" onclick="askBot('ظهرت شاشة زرقاء Blue Screen، وش أسوي؟')"><i class="fa fa-triangle-exclamation"></i><span data-en="Blue Screen" data-ar="شاشة زرقاء"></span></button>
        <button class="diag-btn" onclick="askBot('أشك في وجود فيروس أو برنامج ضار في جهازي، كيف أتخلص منه؟')"><i class="fa fa-bug"></i><span data-en="Virus or Malware" data-ar="فيروس أو برنامج ضار"></span></button>
        <button class="diag-btn" onclick="askBot('الجهاز يسخن كثيراً، كيف أحل مشكلة الحرارة؟')"><i class="fa fa-temperature-high"></i><span data-en="Overheating" data-ar="الجهاز يسخن"></span></button>
        <button class="diag-btn" onclick="askBot('الطابعة لا تعمل، كيف أصلحها؟')"><i class="fa fa-print"></i><span data-en="Printer Issue" data-ar="مشكلة طابعة"></span></button>
        <button class="diag-btn" onclick="askBot('الشاشة لا تعمل أو لا تعرض صورة، وش أسوي؟')"><i class="fa fa-display"></i><span data-en="Screen Issue" data-ar="مشكلة شاشة"></span></button>
        <button class="diag-btn" onclick="askBot('الجهاز لا يشتغل أو لا يبدأ التشغيل، كيف أصلحه؟')"><i class="fa fa-power-off"></i><span data-en="Device Won't Turn On" data-ar="الجهاز لا يشتغل"></span></button>
      </div>

      <div style="text-align:center;margin-top:2.5rem;padding:1.5rem;background:var(--bg2);border-radius:12px;border:1px solid var(--border)">
        <p style="color:var(--text2);margin-bottom:1rem" data-en="Or talk to the bot directly about any problem" data-ar="أو تكلم البوت مباشرة عن أي مشكلة"></p>
        <button onclick="askBot('')" style="background:var(--orange);color:#000;border:none;border-radius:8px;padding:.75rem 2rem;cursor:pointer;font-size:.95rem;font-weight:600;font-family:var(--font-body)">
          <i class="fa fa-robot"></i> <span data-en="Open AI Assistant" data-ar="افتح المساعد الذكي"></span>
        </button>
      </div>
    </div>
  </div>
</section>

<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div><a href="index.html" class="logo"><i class="fa fa-headset" style="color:var(--orange);margin-right:.3rem;font-size:1rem"></i>Tech<span>Support</span></a><p data-en="Your reliable partner for all computer support needs." data-ar="شريكك الموثوق لكل احتياجات الدعم الفني."></p></div>
      <div class="footer-col"><h4 data-en="Pages" data-ar="الصفحات"></h4><a href="index.html"><span data-en="Home" data-ar="الرئيسية"></span></a><a href="self.php"><span data-en="Self-Help" data-ar="المساعدة الذاتية"></span></a><a href="maintenance.html"><span data-en="Maintenance" data-ar="الصيانة"></span></a><a href="Support.php"><span data-en="Support" data-ar="الدعم الفني"></span></a></div>
      <div class="footer-col"><h4 data-en="Contact" data-ar="تواصل معنا"></h4><p><i class="fa fa-phone"></i> +966558230367</p><p><i class="fa fa-envelope"></i> hooooxx551@gmail.com</p></div>
    </div>
    <div class="footer-bottom"><p data-en="© 2025 TechSupport. All rights reserved." data-ar="© 2025 TechSupport. جميع الحقوق محفوظة."></p></div>
  </div>
</footer>

<!-- Botpress Chat -->
<script src="https://cdn.botpress.cloud/webchat/v3.6/inject.js"></script>
<script src="https://files.bpcontent.cloud/2026/06/23/12/20260623123206-WOCNDZ4H.js"></script>

<script>
window.addEventListener('scroll',()=>document.getElementById('navbar').classList.toggle('scrolled',window.scrollY>50));
function toggleNav(){document.getElementById('navLinks').classList.toggle('open')}

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

function askBot(question){
  try {
    window.botpress.open();
    if(question){
      setTimeout(()=>{
        window.botpress.sendMessage(question);
      }, 1200);
    }
  } catch(e) {
    alert('البوت لم يحمّل بعد، انتظر ثانية وحاول مرة ثانية');
  }
}
</script>
<script src="lang.js"></script>
</body>
</html>
