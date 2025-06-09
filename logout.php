<?php
session_start();
setcookie('user_id', '', time() - 3600, "/"); // zrušenie cookie
session_destroy();
header("Location: index.php");
exit();
?>