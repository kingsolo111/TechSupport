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
<title>Self-Help Resources — TechSupport</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar" id="navbar">
  <a href="index.html" class="logo"><i class="fa fa-headset" style="color:var(--orange);margin-right:.3rem;font-size:1rem"></i>Tech<span>Support</span></a>
  <div class="nav-links" id="navLinks">
    <a href="index.html"><span data-en="Home" data-ar="الرئيسية"></span></a>
    <a href="self.php" class="active"><span data-en="Self-Help" data-ar="المساعدة الذاتية"></span></a>
    <a href="tips.html"><span data-en="Tips" data-ar="نصائح"></span></a>
    <a href="maintenance.html"><span data-en="Maintenance" data-ar="الصيانة"></span></a>
    <a href="ai.php"><span data-en="AI Assistant" data-ar="المساعد الذكي"></span></a>
    <a href="Support.php"><span data-en="Support" data-ar="الدعم الفني"></span></a>
    <button id="langToggle" class="lang-btn"><i class="fa fa-globe"></i> عربي</button>
    <div id="navUser"></div>
  </div>
  <button class="hamburger" onclick="toggleNav()"><i class="fa fa-bars"></i></button>
</nav>

<div class="sub-hero">
  <span class="section-label" data-en="Learn &amp; Fix" data-ar="تعلّم وأصلح"></span>
  <h1><span data-en="Self-Help" data-ar="مصادر"></span> <span><span data-en="Resources" data-ar="المساعدة الذاتية"></span></span></h1>
  <p data-en="Trusted tools to help you learn and solve technical issues on your own." data-ar="أدوات موثوقة تساعدك تتعلم وتحل مشاكلك التقنية بنفسك."></p>
</div>

<section style="padding-top:1rem">
  <div class="container">
    <span class="section-label" data-en="Trusted Platforms" data-ar="منصات موثوقة"></span>
    <h2 class="section-title"><span data-en="Learning" data-ar="أدوات"></span> <span><span data-en="Tools" data-ar="تعليمية"></span></span></h2>
    <div class="resources-grid">

      <div class="res-card">
        <div class="res-head"><div class="res-icon"><i class="fa fa-graduation-cap"></i></div><h3>W3Schools <span class="badge badge-free" data-en="Free" data-ar="مجاني"></span></h3></div>
        <p data-en="The most popular beginner-friendly reference for web technologies with live code examples." data-ar="أشهر مرجع سهل للمبتدئين بتقنيات الويب مع أمثلة كود حيّة."></p>
        <ul class="res-points">
          <li data-en="Step-by-step tutorials for beginners" data-ar="دروس خطوة بخطوة للمبتدئين"></li>
          <li data-en="Live &quot;Try it Yourself&quot; editor" data-ar="محرر تجربة مباشر"></li>
          <li data-en="Covers 20+ programming languages" data-ar="يغطي أكثر من 20 لغة برمجة"></li>
          <li data-en="Quick reference cheat sheets" data-ar="أوراق مرجعية سريعة"></li>
        </ul>
        <a href="https://www.w3schools.com" target="_blank" class="res-btn"><i class="fa fa-arrow-up-right-from-square"></i> <span data-en="Visit W3Schools" data-ar="زيارة W3Schools"></span></a>
      </div>

      <div class="res-card">
        <div class="res-head"><div class="res-icon"><i class="fa fa-book"></i></div><h3>DevDocs <span class="badge badge-free" data-en="Free" data-ar="مجاني"></span></h3></div>
        <p data-en="A fast searchable documentation hub combining official docs for hundreds of technologies." data-ar="مركز توثيق سريع وقابل للبحث يجمع الوثائق الرسمية لمئات التقنيات."></p>
        <ul class="res-points">
          <li data-en="Offline access support" data-ar="دعم الوصول بدون إنترنت"></li>
          <li data-en="Covers 200+ technologies" data-ar="يغطي أكثر من 200 تقنية"></li>
          <li data-en="Lightning-fast search" data-ar="بحث فائق السرعة"></li>
          <li data-en="Clean distraction-free interface" data-ar="واجهة نظيفة بدون تشتيت"></li>
        </ul>
        <a href="https://devdocs.io" target="_blank" class="res-btn"><i class="fa fa-arrow-up-right-from-square"></i> <span data-en="Visit DevDocs" data-ar="زيارة DevDocs"></span></a>
      </div>

      <div class="res-card">
        <div class="res-head"><div class="res-icon"><i class="fa fa-code"></i></div><h3>Visual Studio Code <span class="badge badge-tool" data-en="Tool" data-ar="أداة"></span></h3></div>
        <p data-en="The world's most popular free code editor by Microsoft with powerful extensions." data-ar="أشهر محرر أكواد مجاني بالعالم من مايكروسوفت مع إضافات قوية."></p>
        <ul class="res-points">
          <li data-en="Free and open-source" data-ar="مجاني ومفتوح المصدر"></li>
          <li data-en="10,000+ extensions" data-ar="أكثر من 10,000 إضافة"></li>
          <li data-en="Built-in terminal and debugger" data-ar="طرفية ومصحح أخطاء مدمج"></li>
          <li data-en="Works with every language" data-ar="يشتغل مع كل اللغات"></li>
        </ul>
        <a href="https://code.visualstudio.com" target="_blank" class="res-btn"><i class="fa fa-arrow-up-right-from-square"></i> <span data-en="Download VS Code" data-ar="تحميل VS Code"></span></a>
      </div>

      <div class="res-card">
        <div class="res-head"><div class="res-icon"><i class="fa-brands fa-github"></i></div><h3>GitHub Pages <span class="badge badge-free" data-en="Free Hosting" data-ar="استضافة مجانية"></span></h3></div>
        <p data-en="Host your website directly from a GitHub repository for free — no server required." data-ar="استضف موقعك مباشرة من مستودع GitHub مجانًا — بدون الحاجة لسيرفر."></p>
        <ul class="res-points">
          <li data-en="Free static site hosting" data-ar="استضافة مجانية للمواقع الثابتة"></li>
          <li data-en="Custom domain support" data-ar="دعم نطاق مخصص"></li>
          <li data-en="Auto-deploys on every push" data-ar="نشر تلقائي مع كل تحديث"></li>
          <li data-en="Free HTTPS included" data-ar="شهادة HTTPS مجانية"></li>
        </ul>
        <a href="https://pages.github.com" target="_blank" class="res-btn"><i class="fa fa-arrow-up-right-from-square"></i> <span data-en="Visit GitHub Pages" data-ar="زيارة GitHub Pages"></span></a>
      </div>

    </div>
  </div>
</section>

<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div><a href="index.html" class="logo"><i class="fa fa-headset" style="color:var(--orange);margin-right:.3rem;font-size:1rem"></i>Tech<span>Support</span></a><p data-en="Your reliable partner for all computer support needs." data-ar="شريكك الموثوق لكل احتياجات الدعم الفني."></p></div>
      <div class="footer-col"><h4 data-en="Pages" data-ar="الصفحات"></h4><a href="index.html"><span data-en="Home" data-ar="الرئيسية"></span></a><a href="tips.html"><span data-en="Tips" data-ar="نصائح"></span></a><a href="maintenance.html"><span data-en="Maintenance" data-ar="الصيانة"></span></a><a href="ai.php"><span data-en="AI Assistant" data-ar="المساعد الذكي"></span></a><a href="Support.php"><span data-en="Support" data-ar="الدعم الفني"></span></a></div>
      <div class="footer-col"><h4 data-en="Contact" data-ar="تواصل معنا"></h4><p><i class="fa fa-phone"></i> +966558230367</p><p><i class="fa fa-envelope"></i> hooooxx551@gmail.com</p></div>
    </div>
    <div class="footer-bottom"><p data-en="© 2025 TechSupport. All rights reserved." data-ar="© 2025 TechSupport. جميع الحقوق محفوظة."></p></div>
  </div>
</footer>
<script>
window.addEventListener('scroll',()=>document.getElementById('navbar').classList.toggle('scrolled',window.scrollY>50));
function toggleNav(){document.getElementById('navLinks').classList.toggle('open')}
const user = <?php echo json_encode($loggedInName); ?>;
const navUser=document.getElementById('navUser');
if(navUser){
  if(user){
    localStorage.setItem('ts_user', user);
    navUser.innerHTML=`<span style="color:var(--orange);font-size:.85rem;font-weight:600"><i class="fa fa-user"></i> ${user}</span> <a href="my-requests.php" style="color:var(--text2);font-size:.8rem">My Requests</a> <a href="logout.php" style="color:var(--text2);font-size:.8rem">Logout</a>`;
  }else{
    localStorage.removeItem('ts_user');
    navUser.innerHTML='<a href="login.php" class="nav-btn">Login</a>';
  }
}
</script>

<script src="lang.js"></script>
</body>
</html>
