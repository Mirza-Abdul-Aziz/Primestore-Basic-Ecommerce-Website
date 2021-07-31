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
    global $con;
    $sql = "SELECT * FROM pictures where product_id=$prod_id";
    $statement = $con->prepare($sql);
    $statement->execute() or die("could not execute");
    $result = $statement->get_result();
    $images = $result->fetch_all(MYSQLI_ASSOC);

    $statement = $con->prepare("DELETE from pictures where product_id = ?");
    if (!$statement)
        echo "Statement could not be created";
    $statement->bind_param("i", $prod_id);
    $statement->execute() or die("Could not execute DB statement.");

    $statement = $con->prepare("DELETE from orders_detail where product_id = ?");
    if (!$statement)
        echo "Statement could not be created";
    $statement->bind_param("i", $prod_id);
    $statement->execute() or die("Could not execute DB statement.");

    foreach ($images as $image){
        unlink("Products Images/".$image['picture_file_name']);
    }
    $statement = $con->prepare("DELETE from products where id = ?");
    if (!$statement)
        echo "Statement could not be created";
// See parameters binding .. before initialization ... binding is done/allowed
    $statement->bind_param("i", $prod_id);
    $statement->execute() or die("Could not execute DB statement.");
    header("Location:http://localhost/webtech/Ecommerce/dashboard-seller.php?message=Product deleted&username=".$_REQUEST['name']."&id=".$_REQUEST['id'], true, 302);
}
?>
</body>
</html>