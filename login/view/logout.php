<?php session_start(); ?>
<!DOCTYPE html>

<html>
<head>
</head>

<body>
    <?php
        $_SESSION = array();
        session_destroy();
        echo "<script>window.location = '../../'; </script>";
    ?>
</body>
</html>
