<?php
    include "functions/index_functions.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <title>Prime Store</title>
</head>
<body>
    <?php
    session_start();
    if(isset($_REQUEST['message'])){
        $message = $_REQUEST['message'];
        echo "<script>alert('$message')</script>";
    }
    if(isset($_REQUEST["user_id"]) and !isset($_REQUEST['c_id']) and !isset($_SESSION['email'])){
        session_ns_category_ns_user_s();
    }else if(!isset($_REQUEST["user_id"]) and isset($_REQUEST['c_id']) and !isset($_SESSION['email'])){
        session_ns_category_s_user_ns();
    }else if(!isset($_REQUEST["user_id"]) and !isset($_REQUEST['c_id']) and isset($_SESSION['email'])){
        if($_SESSION['type'] == "seller"){
            header('Location: http://localhost/webtech/Ecommerce/dashboard-seller.php?username='.$_SESSION["name"]."&id=".$_SESSION['id'], true, 301);
        }
        if($_SESSION['type'] == "admin"){
            header('Location: http://localhost/webtech/Ecommerce/dashboard-admin.php', true, 302);
        }
        session_s_category_ns_user_ns();
    }else if(!isset($_REQUEST["user_id"]) and !isset($_REQUEST['c_id']) and !isset($_SESSION['email'])){
        session_ns_category_ns_user_ns();
    }else if(isset($_REQUEST["user_id"]) and isset($_REQUEST['c_id']) and isset($_SESSION['email'])){
        session_s_category_s_user_s();
    }else if(isset($_REQUEST["user_id"]) and isset($_REQUEST['c_id']) and !isset($_SESSION['email'])){
        session_ns_category_s_user_s();
    }else if(isset($_REQUEST["user_id"]) and !isset($_REQUEST['c_id']) and isset($_SESSION['email'])){
        session_s_category_ns_user_s();
    }else if(!isset($_REQUEST["user_id"]) and isset($_REQUEST['c_id']) and isset($_SESSION['email'])){
        session_s_category_s_user_ns();
    }
    ?>
</body>

</html>