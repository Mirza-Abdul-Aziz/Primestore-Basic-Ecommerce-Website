<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cart</title>
</head>
<body>
<?php
session_start();
if (isset($_REQUEST['id'])) {
    $prod_id = $_REQUEST['id'];
    $product_name = $_REQUEST['title'];
    $picture = $_REQUEST["picture"];
    $quantity = 0;
    $grand_total = 0;
    if (isset($_SESSION[$product_name])) {
        foreach ($_SESSION[$product_name] as $key=>$value) {
            if ($key == 3){
                if($value>=1){
                    $quantity = $value + 1;
                }
            }
        }
    }else{
        $quantity = 1;
    }
//    if (isset($_SESSION)) {
//        foreach ($_SESSION as $product) {
//            foreach ($product as $key => $value) {
//                if ($key == 3) {
//                    if ($value >= 1) {
//                        $quantity = $value + 1;
//                    }
//                }
//            }
//        }
//    }else{
//        $quantity = 1;
//    }
    $unit_price = $_REQUEST['price'];
    $subtotal = $quantity * $unit_price;

    $product = array($product_name, $picture, $unit_price, $quantity, $subtotal, $prod_id);
    $_SESSION[$product_name] = $product;
    header("Location:http://localhost/webtech/Ecommerce/index.php", true, 302);
}
if (isset($_REQUEST['view'])){
    if(isset($_SESSION['email'])){
        include "functions/logged-in-navbar.php";
    }else{
        include "functions/navbar.php";
    }
    ?>
    <table class="table table-striped">
        <thead class="thead">
        <tr>
            <th>Name</th>
            <th>Image</th>
            <th>Unit Price</th>
            <th>Quantity</th>
            <th>Sub Total</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody class="tbody">
        <?php
        $_SESSION['ids'] = array();
        $_SESSION['quantity'] = array();
        foreach ($_SESSION as $product) {
            echo "<form action='delete-from-cart.php' method='get'>";
            echo "<tr>";
            if (is_array($product) or is_object($product)){
                foreach ($product as $key=>$value){
                    if($key == 0){
                        echo "<input type='hidden' name='title' value='".$value."'>";
                        echo "<td>".$value."</td>";
                    }
                    if($key == 1){
                        echo "<td><img src='Products%20Images/$value' alt='product image' width='70px' height='70px'></td>";
                    }
                    if($key == 2){
                        echo "<td>".$value."</td>";
                    }
                    if($key == 3){
                        $_SESSION['quantity'][] = $value;
                        echo "<td>".$value."</td>";
                    }
                    if($key == 4){
                        echo "<td>".$value."</td>";
                    }
                    if($key == 5){
                        $_SESSION['ids'] = $value;
                        echo "<td><button type='submit' name='submit' class='btn btn-danger'>Remove</button></td>";
                    }
                }
            }
            echo "</tr>";
            echo "</form>";
        }
        ?>
        </tbody>
    </table>
    <?php


    ?>

    <form action="checkout.php">
        <button type='submit' name='checkout' class='btn btn-success'>Place Order</button>
    </form>
    <?php
    if (isset($_SESSION['email'])){
        include "functions/logged-in-footer.html";
    }else{
        include "functions/footer.html";
    }
} ?>
</body>
</html>