<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="functions/custom.css">
    <title>Product Details</title>
</head>
<body>
<?php
session_start();
include "create-connection.php";
if (isset($_SESSION['email'])){
include "functions/logged-in-navbar.php";}else{
    include "functions/navbar.php";
}
$prod_id = $_REQUEST['product_id'];
if(isset($_REQUEST['message'])){
    $message = $_REQUEST['message'];
    echo "<script>alert('$message')</script>";
}
global $con;
$result_set = $con->query("SELECT * from products where id=$prod_id order by id");
while ($row = $result_set->fetch_array()) {
$title = $row['title'];
$price = $row['price'];
$description = $row['description'];
$product_added_date=$row['date_added'];
$status = $row['status'];
$user_id = $row['user_id'];
$category_id = $row['category_id'];
$filename = $row['main_picture_file_name'];
$statement = $con->prepare("SELECT * from categories where id=$category_id");
if (!$statement)
    echo "Statement could not be created";
$statement->execute() or die("Could not execute DB statement.");
$result_set = $statement->get_result();
$rs = $result_set->fetch_array();
$c_name = $rs['name'];
$statement = $con->prepare("SELECT * from users where id=$user_id");
if (!$statement)
    echo "Statement could not be created";
$statement->execute() or die("Could not execute DB statement.");
$result_set = $statement->get_result();
$rs = $result_set->fetch_array();
$seller_shop = $rs['shop_title'];
?>
<div id="main-container">
<div class="container">
    <h5>Product Details</h5>
    <table class="table table-striped">
        <tr>
            <th>Name</th>
            <th>Value</th>
        </tr>
        <tr>
            <th>Category Name</th>
            <td><?=$c_name?></td>
        </tr>
        <tr>
            <th>Title</th>
            <td><?= $title ?></td>
        </tr>
        <tr>
            <th>Price</th>
            <td><?= $price ?></td>
        </tr>
        <tr>
            <th>Description</th>
            <td><?= $description ?></td>
        </tr>
        <tr>
            <th>Image</th>
            <td><img src="Products%20Images/<?=$filename?>" alt="prod_img.jpg" height="350px" width="350px"></td>
        </tr>
        <tr>
            <th>Product added date</th>
            <td><?= $product_added_date ?></td>
        </tr>
        <tr>
            <th>Seller Shop Title</th>
            <td><?= $seller_shop ?></td>
        </tr>
        <tr>
            <td>Purchase</td>
            <td>
                <form action="cart.php" method="get">
                    <input type="hidden" name="id" value="<?= $prod_id ?>">
                    <input type="hidden" name="title" value="<?= $title ?>">
                    <input type="hidden" name="picture" value="<?= $filename ?>">
                    <input type="hidden" name="price" value="<?= $price ?>">
                    <button type="submit" id="button"><i class="fa fa-shopping-cart" style="color:white; padding-right: 5px"></i>ADD TO CART</button>
                </form>
            </td>
        </tr>
    </table>
    <?php
    if(isset($_SESSION['email'])){
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT count(id) from reviews where user_id=$user_id and product_id=$prod_id order by id";
        $statement = $con->prepare($sql);
        $statement->execute() or die("could not execute");
        $result = $statement->get_result();
        $reviews = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($reviews as $review){
            if($review["count(id)"] == 0){?>
                <h3>Add your review</h3>
                <hr class="bg-dark">
                <form class="form" action="add-review.php" method="post">
                    <h5>Ratings:</h5>
                    <div class="form-group">
                        <input type="radio" name="rating" value="1"> POOR
                        <input type="radio" name="rating" value="2"> SATISFACTORY
                        <input type="radio" name="rating" value="3"> GOOD
                        <input type="radio" name="rating" value="4"> VERY GOOD
                        <input type="radio" name="rating" value="5"> EXCELLENT
                    </div>
                    <h5>Comments</h5>
                    <div class="form-group">
                        <textarea name="comments" cols="30" rows="10" placeholder="Provide Your Feedback"></textarea>
                    </div>
                    <input type="hidden" name="user_id" value="<?= $user_id ?>">
                    <input type="hidden" name="product_id" value="<?= $prod_id ?>">
                    <input type="submit" name="submit" class="bg-primary">
                </form>
                <?php
            }
        }
    }
    ?>
    <hr class="bg-dark">
    <h3>Reviews</h3>
    <table class="table table-striped">
        <tr>
            <th>Customer Details</th>
            <th>Comments</th>
        </tr>
        <?php
        $result_set=$con->query("SELECT * from reviews where product_id = $prod_id order by id");
        while($row = $result_set->fetch_array()){
            $r_user_id = $row['user_id'];
            if($row['user_id'] == Null){
                echo "<p>No reviews yet</p>";
            }else{
            }?>
            <tr>
                <td>
                    <div>
                        <h6>Customer name:</h6>
                        <p><?php
                            $sql = "SELECT * FROM users where id=$r_user_id order by id";
                            $statement = $con->prepare($sql);
                            $statement->execute() or die("could not execute");
                            $result = $statement->get_result();
                            $user_names = $result->fetch_all(MYSQLI_ASSOC);
                            foreach ($user_names as $user_name){
                                echo $user_name['name'];
                            }
                            ?></p>
                        <h6>Rating</h6>
                        <p><?= $row['rating'] ?></p>
                        <h6>Date</h6>
                        <p><?= $row['date_added'] ?></p>
                    </div>
                </td>
                <td><?= $row['comments'] ?></td>
            </tr>
        <?php }?>
    </table>
    <?php
    }?>
</div>
</div>
<?php
include "functions/logged-in-footer.html";
?>
</body>
</html>