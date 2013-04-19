<!DOCTYPE html>

<html>
<head>
</head>

<body>
    <?php
        @session_start();
        session_destroy();
        echo "<script>window.location = '../../'; </script>";
    ?>
</body>
</html>
