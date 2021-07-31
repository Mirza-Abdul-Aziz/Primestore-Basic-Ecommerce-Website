<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https:/code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="functions/custom.css">
    <title>Document</title>
</head>
<body>
    <div class="jumbotron" style="margin-bottom: 0px">
        <h1>Prime Store</h1>
        <p>Purchase the world!</p>
    </div>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
                <button type="button" class="navbar-toggler mr-auto" data-toggle="collapse" data-target="#collapse_target">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a href="index.php" class="navbar-brand"><img src="https://cdn.iconscout.com/icon/free/png-256/p-9-675837.png" width="50px"></img></a>
                <span class="navbar-text">Prime Store</span>
            <div class="collapse navbar-collapse" id="collapse_target">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" data-target="dropdown_target">
                        Categories
                        <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown_target">
                            <?php
                                include("create-connection.php");
                                $result_set = $con->query('SELECT * from categories order by id');
                                while ($row = $result_set->fetch_array()) {
                                    $c_name = $row['name'];
                                    $c_id = $row['id'];
                                    echo "<a class='nav-link bg-dark' style='color: lightgray;' href='index.php?c_id=$c_id'>$c_name</a>";
                                }
                            ?>
                        </div>
                    </li>
                    <li class="nav-item"><a href="../Ecommerce/pages/Contact%20Us.html" class="nav-link">Contact</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">About Us</a></li>
                    <li class="nav-item"><a href="../Ecommerce/login.php" class="nav-link">Log In</a></li>
                    <li class="nav-item"><a href="../Ecommerce/create-new-customer.php" class="nav-link">Sign up</a></li>
                    <li class="nav-item"><a href="../Ecommerce/cart.php?view=1" class="nav-link"><i class="fa fa-shopping-cart" style="color:white; padding-right: 5px"></i>View Cart</a></li>
                </ul>
            </div>
    </nav>
</body>
</html>
