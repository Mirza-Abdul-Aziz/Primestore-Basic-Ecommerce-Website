<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Review Added</title>
</head>
<body>
    <?php
    include "create-connection.php";
    global $con;
    if (isset($_REQUEST['submit'])){
        $user_id = $_REQUEST['user_id'];
        $product_id = $_REQUEST['product_id'];
        $rating = $_REQUEST['rating'];
        $comments = $_REQUEST['comments'];
        $sql = "SELECT id from reviews order by id desc limit 1";
        $statement = $con->prepare($sql);
        $statement->execute() or die("could not execute");
        $result = $statement->get_result();
        $reviews = $result->fetch_all(MYSQLI_ASSOC);
    foreach ($reviews as $review){
        if($review["id"] != 0){
            $id = $review['id'] + 1;
        }
        else{
            $id = 1;
        }
    }
        $statement = $con->prepare("INSERT INTO reviews (id, user_id, product_id, rating, comments, date_added) VALUES (?,?,?,?,?,NOW())");
        if (!$statement)
            echo "Statement could not be created";
        $statement->bind_param("iiiis", $id,$user_id, $product_id, $rating, $comments);
        $statement->execute() or die();
        header("Location:http://localhost/webtech/Ecommerce/product.php?product_id=".$product_id."&message=Review added successfully.", true, 302);
    }
    ?>
</body>
</html>