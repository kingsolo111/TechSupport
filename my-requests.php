<?php
session_start();

if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, phone, email, message, created_at FROM support_requests WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}
$stmt->close();
$conn->close();

$loggedInName = $_SESSION['user_name'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Requests — TechSupport</title>
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
    <a href="Support.php"><span data-en="Support" data-ar="الدعم الفني"></span></a>
    <button id="langToggle" class="lang-btn"><i class="fa fa-globe"></i> عربي</button>
    <div id="navUser"></div>
  </div>
  <button class="hamburger" onclick="toggleNav()"><i class="fa fa-bars"></i></button>
</nav>

<div class="sub-hero">
  <span class="section-label" data-en="Your Account" data-ar="حسابك"></span>
  <h1><span data-en="My" data-ar="طلباتي"></span> <span><span data-en="Requests" data-ar=""></span></span></h1>
<!-- note: heading reads "طلباتي" alone in Arabic by design, matching the orange-highlighted word styling -->
  <p data-en="All the support requests you've submitted." data-ar="كل طلبات الدعم الفني اللي أرسلتها."></p>
</div>

<section style="padding-top:0">
  <div class="container">
    <?php if (empty($requests)): ?>
      <div class="support-wrap" style="text-align:center">
        <p style="color:var(--text2);margin-bottom:1.25rem" data-en="You haven't submitted any support requests yet." data-ar="ما سويت أي طلب دعم فني لحد الحين."></p>
        <a href="Support.php" class="btn-primary" style="display:inline-flex"><i class="fa fa-paper-plane"></i> <span data-en="Submit a Request" data-ar="أرسل طلب"></span></a>
      </div>
    <?php else: ?>
      <div style="display:flex;flex-direction:column;gap:1rem">
        <?php foreach ($requests as $r): ?>
        <div class="support-wrap">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.75rem;flex-wrap:wrap;gap:.5rem">
            <span style="color:var(--orange);font-weight:700;font-size:.85rem"><i class="fa fa-clock"></i> <?php echo htmlspecialchars($r['created_at']); ?></span>
            <span style="color:var(--text2);font-size:.8rem"><?php echo htmlspecialchars($r['phone']); ?> · <?php echo htmlspecialchars($r['email']); ?></span>
          </div>
          <p style="color:var(--text1);line-height:1.6"><?php echo nl2br(htmlspecialchars($r['message'])); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
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
