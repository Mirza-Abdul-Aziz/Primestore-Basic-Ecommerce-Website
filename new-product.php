<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <title>Add new Product</title>
</head>

<body>
<?php
session_start();
include "create-connection.php";
if(isset($_REQUEST['submit'])) {
    $folder_name = "Products Images";
    $name = $_REQUEST['name'];
    $title = $_REQUEST['title'];
    $price = $_REQUEST['price'];
    $description = $_REQUEST['description'];
    $status = $_REQUEST['status'];
    $user_id = $_REQUEST['user_id'];
    $category_id = $_REQUEST['category_id'];
    $main_picture_file_name = $_FILES['picture']['name'];
    $dot_index = strrpos($main_picture_file_name, ".");
    $extension = substr($main_picture_file_name, $dot_index + 1, strlen($main_picture_file_name - 1 - $dot_index));
    $new_file_name = rand(). "." . $extension;
    $file_type = $_FILES['picture']['type'];
    $tmp_file_absolute_path = $_FILES['picture']['tmp_name'];
    if (file_exists($folder_name) && is_dir($folder_name)) {
    } else {
        mkdir($folder_name);
    }
    if (is_uploaded_file($tmp_file_absolute_path)) {
        move_uploaded_file($tmp_file_absolute_path, $folder_name."/".$new_file_name);
    }
    $more_pictures_name = $_FILES['more_pictures']['name'];
    global $con;
    $statement = $con->prepare("INSERT INTO products (title, price, description, date_added, status, user_id, category_id, main_picture_file_name) VALUES (?,?,?,NOW(),?,?,?,?)");
    if (!$statement)
        echo "Statement could not be created";
    $statement->bind_param("sdsiiis", $title, $price, $description, $status, $user_id, $category_id, $new_file_name);
    $statement->execute() or die("Looks like product is already added.");
    $statement = $con->prepare("SELECT max(id) from products");
    if (!$statement)
        echo "Statement could not be created";
    $statement->execute() or die("Could not execute DB statement.");
    $id = $statement->get_result();
    $rs = $id->fetch_array();
    $id = $rs['max(id)'];

    if(!empty($more_pictures_name)){
    foreach ($_FILES['more_pictures']['name'] as $key=>$value){
        $picture_name = $_FILES['more_pictures']['name'][$key];
        $dot_index = strrpos($picture_name, ".");
        $extension = substr($picture_name, $dot_index + 1, strlen($picture_name - 1 - $dot_index));
        $new_files_name = rand(). "." . $extension;
        if (is_uploaded_file($_FILES['more_pictures']['tmp_name'][$key])) {
            move_uploaded_file($_FILES['more_pictures']['tmp_name'][$key], $folder_name."/".$new_files_name);
        }
        $statement = $con->prepare("INSERT INTO pictures (product_id, picture_file_name, sort_order) VALUES (?,?,1)");
        if (!$statement)
            echo "Statement could not be created";
        $statement->bind_param("is", $id, $new_files_name);
        $statement->execute() or die();
    }
    }
    header("Location:http://localhost/webtech/Ecommerce/dashboard-seller.php?message=New product added!&username=".$name."&id=".$user_id, true, 302);
}
?>
<div class="container">
    <h2 style="padding-top: 16px; padding-bottom: 16px;">Add New Product</h2>
    <form action="new-product.php" method="POST" enctype="multipart/form-data">
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
            <input type="text" class="form-control" id="title" placeholder="Enter product title" name="title" required>
        </div>
        <div class="form-group">
            <label for="description">Product Description:</label>
            <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="price">Product price:</label>
            <input type="number" class="form-control" id="price" placeholder="Enter price" name="price" min="10" step="any" required>
        </div>
        <div class="form-group">
            <input type="hidden" name="status" value=0>
            <input type="hidden" name="user_id" value=<?= $_REQUEST['id'] ?>>
        </div>
        <div class="form-group">
            <label for="main_picture">Attach main picture:</label>
            <input type="file" class="form-control" id="picture" name="picture" required>
        </div>
        <div class="form-group">
            <label for="multiple-pictures">Add more pictures:</label>
            <input type="file" name="more_pictures[]" id="multiple-pictures" multiple>
        </div>
        <div>
            <input type="submit" name="submit">
        </div>
</div>
</form>
</body>

</html>