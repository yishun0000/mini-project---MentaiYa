<?php
/* =========================================
   USER LOGOUT & SESSION TERMINATION
   ========================================= */
session_start();
session_destroy();

header('Location: /login.php');
exit;
?>