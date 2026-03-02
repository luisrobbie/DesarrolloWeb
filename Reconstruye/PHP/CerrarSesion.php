<?php
session_start();
session_unset();
session_destroy();
echo "<script>window.close();</script>";
exit;
?>
