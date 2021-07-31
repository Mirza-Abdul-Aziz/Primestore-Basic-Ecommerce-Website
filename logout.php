<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Out</title>
</head>
<body>
    <?php
    session_start();
    session_destroy();
    header(
        'Location: http://localhost/webtech/Ecommerce/index.php', true, 302
    );
    ?>
</body>
</html>