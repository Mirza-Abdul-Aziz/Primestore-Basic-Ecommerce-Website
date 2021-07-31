<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rejected</title>
</head>
<body>
<?php
include "create-connection.php";
if(isset($_REQUEST['product_id'])){
$prod_id = $_REQUEST['product_id'];
$status = $_REQUEST['status'];
global $con;

    $statement = $con->prepare("DELETE from pictures where product_id = ?");
    if (!$statement)
        echo "Statement could not be created";
// See parameters binding .. before initialization ... binding is done/allowed
    $statement->bind_param("i", $prod_id);
    $statement->execute() or die("Could not execute DB statement.");

$statement = $con->prepare("DELETE from products where id = ?");
if (!$statement)
echo "Statement could not be created";
// See parameters binding .. before initialization ... binding is done/allowed
$statement->bind_param("i", $prod_id);
$statement->execute() or die("Could not execute DB statement.");
 header("Location:http://localhost/webtech/Ecommerce/dashboard-admin.php?message=Product rejected", true, 302);
}
?>
</body>
</html>