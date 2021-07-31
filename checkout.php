<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Place Order</title>
</head>
<body>
<?php
session_start();
include "create-connection.php";
if (isset($_REQUEST['checkout'])){
    if (!isset($_SESSION['email'])){
        include ('functions/navbar.php');
        ?>
        <div class="container">
            <h2 style="padding-top: 16px; padding-bottom: 16px;">Checkout</h2>
            <form action="checkout.php">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter your name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="Business Address">Address:</label>
                    <input type="text" class="form-control" id="address" placeholder="Enter address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="postal_address">Postal Address:</label>
                    <input type="text" class="form-control" id="postal_address" placeholder="Enter Postal Address" name="postal_address" required>
                </div>
                <div>
                    <button type="submit" name="submit" class="btn btn-success"><i class="fa fa-shopping-cart" style="color:white; padding-right: 5px"></i>Checkout</button>
                </div>
            </form>
        </div>

        <?php
        include "functions/footer.html";
    }else{
        include "functions/logged-in-navbar.php";
        $user_id = $_SESSION['user_id'];
        $query = "select * from users where id=$user_id";
        $result_set = $con->query($query) or die("Last error: {$con->error}\n");
        while($row = $result_set->fetch_array()){
        ?>
        <div class="container">
            <h2 style="padding-top: 16px; padding-bottom: 16px;">Checkout</h2>
            <form action="checkout.php">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter your name" name="name" value="<?= $row['name'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="<?= $row['email'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="Business Address">Address:</label>
                    <input type="text" class="form-control" id="address" placeholder="Enter address" name="address" value="<?= $row['address'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="postal_address">Postal Address:</label>
                    <input type="text" class="form-control" id="postal_address" placeholder="Enter Postal Address" name="postal_address" value="<?= $row['postal_address'] ?>" required>
                </div>
                <div>
                    <button type="submit" name="submit" class="btn btn-success"><i class="fa fa-shopping-cart" style="color:white; padding-right: 5px"></i>Checkout</button>
                </div>
            </form>
        </div>
        <?php
        include "functions/logged-in-footer.html";
    }
    }
    }
    if(isset($_REQUEST['submit'])) {
        if (!isset($_SESSION['email'])) {
            session_destroy();
            $name = $_REQUEST['name'];
            $email = $_REQUEST['email'];
            $address = $_REQUEST['address'];
            $postal_address = $_REQUEST['postal_address'];
            global $con;
            $statement = $con->prepare("INSERT INTO users (name, email, address, type, postal_address) VALUES (?,?,?, 'buyer', ?)");
            if (!$statement)
                echo "Statement could not be created";
            $statement->bind_param("ssss", $name, $email, $address, $postal_address);
            $statement->execute() or die();

            $statement = $con->prepare("SELECT max(id) from users");
            if (!$statement)
                echo "Statement could not be created";
            $statement->execute() or die("Could not execute DB statement.");
            $id = $statement->get_result();
            $rs = $id->fetch_array();
            $customer_id = $rs['max(id)'];

            $sql = "SELECT id from orders order by id desc limit 1";
            $statement = $con->prepare($sql);
            $statement->execute() or die("could not execute");
            $result = $statement->get_result();
            $orders = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($orders as $order){
                if($order["id"] != 0){
                    $id = $order['id'] + 1;
                }else{
                    $id = 1;
                }
            }

            $statement = $con->prepare("INSERT INTO orders (id, date_added, customer_id) VALUES (?,NOW(),?)");
            if (!$statement)
                echo "Statement could not be created";

            $statement->bind_param("ii", $id, $customer_id);
            $statement->execute() or die();
            for ($key=0; $key < sizeof($_SESSION['ids']) ; $key++) {
                $product_id = $_SESSION['ids'][$key];
                $quantity = $_SESSION['quantity'][$key];
                $statement = $con->prepare("INSERT INTO orders_detail ( order_id, product_id, quantity) VALUES (?,?,?)");
                if (!$statement)
                    echo "Statement could not be created";
                $statement->bind_param("iii", $id,$product_id, $quantity);
                $statement->execute() or die();
            }
            header("Location:http://localhost/webtech/Ecommerce/index.php?message=Your order has been placed successfully.", true, 302);
        }else{
            $user_id = $_SESSION['user_id'];
            $statement = $con->prepare("SELECT id from users where id=$user_id");
            if (!$statement)
                echo "Statement could not be created";
            $statement->execute() or die("Could not execute DB statement.");
            $id = $statement->get_result();
            $rs = $id->fetch_array();
            $customer_id = $rs['id'];

            $sql = "SELECT id from orders order by id desc limit 1";
            $statement = $con->prepare($sql);
            $statement->execute() or die("could not execute");
            $result = $statement->get_result();
            $orders = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($orders as $order){
                if($order["id"] != 0){
                    $id = $order['id'] + 1;
                }else{
                    $id = 1;
                }
            }

            $statement = $con->prepare("INSERT INTO orders (id, date_added, customer_id) VALUES (?,NOW(),?)");
            if (!$statement)
                echo "Statement could not be created";

            $statement->bind_param("ii", $id, $customer_id);
            $statement->execute() or die();
            for ($key=0; $key < sizeof($_SESSION['ids']) ; $key++) {
                $product_id = $_SESSION['ids'][$key];
                $quantity = $_SESSION['quantity'][$key];
                echo $product_id."<br>";

                $statement = $con->prepare("INSERT INTO orders_detail ( order_id, product_id, quantity) VALUES (?,?,?)");
                if (!$statement)
                    echo "Statement could not be created";
                $statement->bind_param("iii", $id,$product_id, $quantity);
                $statement->execute() or die();

                $statement = $con->prepare("SELECT title from products where id=$product_id");
                if (!$statement)
                    echo "Statement could not be created";
                $statement->execute() or die("Could not execute DB statement.");
                $id = $statement->get_result();
                $rs = $id->fetch_array();
                $product_name = $rs['title'];
                unset($_SESSION[$product_name]);
                unset($_SESSION[$product_id]);
                unset($_SESSION[$quantity]);
            }
            header("Location:http://localhost/webtech/Ecommerce/index.php?message=Your order has been placed successfully.", true, 302);
        }
    }

?>
</body>
</html>