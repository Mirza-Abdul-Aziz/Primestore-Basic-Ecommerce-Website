<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete from cart</title>
</head>
<body>
<?php
session_start();
if(isset($_REQUEST['submit'])){
    $product_name = $_REQUEST['title'];
    unset($_SESSION[$product_name]);
    header("Location:http://localhost/webtech/Ecommerce/cart.php?view=1", true, 302);
}
?>
</body>
</html>