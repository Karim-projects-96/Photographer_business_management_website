<?php
// client/logout.php
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
session_start();
session_destroy();
header("Location: ../public/index.php");
exit();
?>
