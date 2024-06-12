<?php
session_start();
session_unset();
session_destroy();
header("Location: yardimci_admin_giris.html");
exit();
?>
