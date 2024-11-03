<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shelfscape: Logout</title>
    <script type="text/javascript">
        alert('You have been logged out.');
        window.location.href = 'login.php';
    </script>
</head>
<body>
</body>
</html>