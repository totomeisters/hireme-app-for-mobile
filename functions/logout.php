<?php
if (isset($_SESSION)) {
    session_destroy();
}
header("Location: ../auth-login-basic.php");
exit;
?>
