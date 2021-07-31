<?php
include "functions/index_functions.php";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <title>Seller Dashboard</title>
</head>
<body>
<?php
    session_start();
    if(isset($_REQUEST['submit'])){
        header("Location:http://localhost/webtech/Ecommerce/new-product.php?id=".$_SESSION['id']."&name=".$_SESSION['name'], true, 302);
    }
    if(isset($_REQUEST['message'])){
        $message = $_REQUEST['message'];
        echo "<script>alert('$message')</script>";
    }
    if(!isset($_SESSION["email"])){
        session_ns_category_ns_user_ns();
    }else{
        include ("functions/logged-in-navbar.php");
        ?>
        <h1 style="margin-top: 20px; margin-bottom: 20px">Dashboard Seller</h1>
        <form action="dashboard-seller.php" method="post" style="text-align: center; margin-bottom: 20px">
            <input type="submit" class="btn-primary" name="submit" value="+ ADD NEW PRODUCT">
        </form>
        <table class="table">
        <tr class="table-dark">
            <th>Image</th>
            <th>Title</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr><?php
        $result_set = $con->query("SELECT * from products order by id");
        while ($row = $result_set->fetch_array()) {
            $id = $row['id'];
            $title = $row['title'];
            $price = $row['price'];
            $status = $row['status'];
            $description = $row['description'];
            $filename = $row['main_picture_file_name'];
            ?>
            <tr>
                <td><?php echo "<img src='Products%20Images/$filename' height='70px' width='70px'>"?></td>
                <td><?= $title ?></td>
                <td><?= $price ?></td>
                <td><?= $status ?></td>
                <td>
                    <div>
                        <a class="text-success font-weight-bold" href="product-update.php?product_id=<?= $id ?>&name=<?= $_SESSION['name'] ?>&id=<?= $_SESSION['id'] ?>">Update</a> |
                        <a class="text-danger font-weight-bold" href="product-delete.php?product_id=<?= $id ?>&name=<?= $_SESSION['name'] ?>&id=<?= $_SESSION['id'] ?>">Delete</a>
                    </div>
                </td>
            </tr>
            <?php
        }
        ?>
        </table><?php
    }
include "functions/logged-in-footer.html";
?>
</body>
</html>