<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <title>Update Product</title>
</head>

<body>
<?php
session_start();
include "create-connection.php";
if(isset($_REQUEST['submit'])) {
    $id = $_REQUEST['product_id'];
    $name = $_REQUEST['name'];
    $title = $_REQUEST['title'];
    $price = $_REQUEST['price'];
    $description = $_REQUEST['description'];
    $status = $_REQUEST['status'];
    $user_id = $_REQUEST['user_id'];
    $category_id = $_REQUEST['category_id'];
    global $con;
    $statement = $con->prepare("UPDATE products SET title = ?, price = ?, description = ?, status = 0, category_id = ?, user_id = ? where id = ?");
    if (!$statement)
        echo "Statement could not be created";
    $statement->bind_param("sisiii", $title, $price, $description, $category_id, $user_id, $id);
    $statement->execute() or die("Could not execute statement");
    header("Location:http://localhost/webtech/Ecommerce/dashboard-seller.php?username=".$name."&id=".$user_id, true, 302);
}
if(isset($_REQUEST['product_id'])) {
    $prod_id=$_REQUEST['product_id'];
    $name = $_REQUEST['name'];
    $result_set = $con->query("SELECT * from products where id=$prod_id");
    while($row = $result_set->fetch_array()){
        $id = $row['id'];
    $title = $row['title'];
    $price = $row['price'];
    $description = $row['description'];
    $status = $row['status'];
    $user_id = $row['user_id'];
    $category_id = $row['category_id'];
    $main_picture_file_name = $row['main_picture_file_name'];
?>
<div class="container">
    <h2 style="padding-top: 16px; padding-bottom: 16px;">Add New Product</h2>
    <form action="product-update.php" method="GET" enctype="multipart/form-data">
        <div class="form-group">
            <label for="seller">Seller:</label>
            <input type="text" id="seller" class="form-control" name="name" value=<?= $_REQUEST['name'] ?> readonly>
        </div>
        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category_id" name="category_id" class="form-control">
                <?php
                include ("create-connection.php");
                $result_set = $con->query('SELECT * from categories order by id');
                while ($row = $result_set->fetch_array()) {
                    $c_name = $row['name'];
                    $c_id = $row['id'];
                    echo "<option value=$c_id>$c_name</option></a>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="title">Product Title:</label>
            <input type="text" class="form-control" id="title" placeholder="Enter product title" name="title" value="<?= $title ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Product Description:</label>
            <textarea name="description" id="description" cols="30" rows="5" class="form-control"> <?= $description ?> </textarea>
        </div>
        <div class="form-group">
            <label for="price">Product price:</label>
            <input type="number" class="form-control" id="price" placeholder="Enter price" name="price" min="10" step="any" value="<?= $price ?>" required>
        </div>
        <div class="form-group">
            <input type="hidden" name="status" value=0>
            <input type="hidden" name="user_id" value="<?= $_REQUEST['id'] ?>">
            <input type="hidden" name="product_id" value="<?= $id ?>">
        </div>
        <div>
            <input type="submit" name="submit">
        </div>
</div>
</form>
</body>
<?php
}
}
?>
</html>