<?php
session_start();
session_destroy();
?>
<script>
localStorage.removeItem('ts_user');
window.location.href = 'index.html';
</script>
