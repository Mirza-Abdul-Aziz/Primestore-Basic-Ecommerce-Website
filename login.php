<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="functions/stylesheet.css">
    <title>Log in</title>
</head>
<body id="login-body">
<?php
session_start();
if(isset($_SESSION['email'])){
    header("Location: index.php", true, 301);
}else{
    if (isset($_REQUEST['action'])) {
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
        include 'create-connection.php';
        global $con;
        $result_set = $con->query('SELECT id, name, email, password, type from users order by id');
        while ($row = $result_set->fetch_array()){
            if ($email == $row['email'] and $password == $row['password'] and $row['type']=='buyer'){
                $_SESSION['email'] = $email;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['type'] = $row['buyer'];
                header('Location: index.php', true, 301);
            }else if($email == $row['email'] and $password == $row['password'] and $row['type']=='seller'){
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['type'] = "seller";
                header('Location: dashboard-seller.php?username='.$row["name"]."&id=".$row['id'], true, 301);
            }else if($email == $row['email'] and $password == $row['password'] and $row['type']=='admin'){
                $_SESSION['email'] = $email;
                $_SESSION['type'] = "admin";
                header('Location: dashboard-admin.php', true, 301);
            }else{
                header("Location: login.php?message=Username or password is incorrect", true, 302);
            }
        }
    }
    ?>
    <div class="main" style="">
        <div style="text-align: center">
            <img src="https://cdn.iconscout.com/icon/free/png-256/p-9-675837.png" width="50px">
        </div>
        <div class="title">Login</div>
        <form action="login.php">
            <div class="credentials">
                <div class="username">
                    <span class="glyphicon glyphicon-user"></span>
                    <input type="text" name="email" placeholder="Email" required>
                </div>
                <div class="password">
                    <span class="glyphicon glyphicon-lock"></span>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div style="text-align: center">
                    <?php
                    if (isset($_REQUEST['message'])) {
                        $message = $_REQUEST['message'];
                        echo "<h5 style='color: red;'>  $message  </h5>";
                    }
                    ?>
                </div>
                <button class="submit" type="submit" name="action">Log In</button>
            </div>
        </form>
        <div class = "link">
            <a href="#" >Forget Password? </a> &nbsp <a href="#" > Sign up </a>
        </div>
    </div>
<?php }
?>
</body>
</html>