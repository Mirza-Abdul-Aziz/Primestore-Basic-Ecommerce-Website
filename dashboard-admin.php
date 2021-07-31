<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
<?php
session_start();
if(isset($_REQUEST['message'])){
    $message = $_REQUEST['message'];
    echo "<script>alert('$message')</script>";
}
if(!isset($_SESSION["email"])){
    header("Location: index.php", true, 302);
}else{
include ("functions/logged-in-navbar.php");
?>
<h1 style="margin-top: 20px; margin-bottom: 20px">Dashboard</h1>
<table class="table">
    <tr class="table-dark">
        <th>ID</th>
        <th>Title</th>
        <th>Image</th>
        <th>Price</th>
        <th>Description</th>
        <th>Other Images</th>
        <th>Actions</th>
    </tr><?php
    $result_set = $con->query("SELECT * from products where status=0 order by id");
    while ($row = $result_set->fetch_array()) {
        $id = $row['id'];
        $title = $row['title'];
        $price = $row['price'];
        $description = $row['description'];
        $filename = $row['main_picture_file_name'];
        ?>
        <tr>
            <td><?= $id ?></td>
            <td><?= $title ?></td>
            <td><?php echo "<img src='Products%20Images/$filename' height='70px' width='70px'>"?></td>
            <td><?= $price ?></td>
            <td><?= $description ?></td>
            <td>
                <?php
                $sql = "SELECT * FROM pictures where product_id=$id";  //fetching the emost recent products
                $statement = $con->prepare($sql);
                $statement->execute() or die("could not execute");
                $result = $statement->get_result();
                $images = $result->fetch_all(MYSQLI_ASSOC);
                foreach ($images as $image){
                    echo $image['picture_file_name'];
                    ?>
                    <img src="Products%20Images/<?= $image['picture_file_name']?>" width="70px" height="70px" alt="image"><?php
                } ?>
            </td>
            <td>
                <div>
                    <a class="text-success font-weight-bold" href="approve.php?product_id=<?= $id ?>&status=1">Approve</a> |
                    <a class="text-danger font-weight-bold" href="reject.php?product_id=<?= $id ?>&status=0">Reject</a>
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