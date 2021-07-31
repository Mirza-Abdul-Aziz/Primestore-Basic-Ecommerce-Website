<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <title>Register as customer</title>
</head>
<body>
<?php
include "create-connection.php";
if(isset($_REQUEST['submit'])) {
    $name = $_REQUEST['name'];
    $email = $_REQUEST['email'];
    $password = $_REQUEST['pwd'];
    $address = $_REQUEST['address'];
    $postal_address = $_REQUEST['postal_address'];
    $statement = $con->prepare("INSERT INTO users (name, email, password, address, type, postal_address) VALUES (?,?,?,?, 'buyer', ?)");
    if (!$statement)
        echo "Statement could not be created";
    $statement->bind_param("sssss", $name, $email, $password, $address, $postal_address);
    $statement->execute() or die("Looks like this email is  already registered.");
    header("Location:http://localhost/webtech/Ecommerce/login.php?message=You have registered successfully please log in to see the buyer dashboard.", true, 302);
}
?>
<div class="container">
    <h2 style="padding-top: 16px; padding-bottom: 16px;">Sign Up</h2>
    <form action="create-new-customer.php">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" placeholder="Enter your name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
        </div>
        <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd" required>
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
            <input type="submit" name="submit">
        </div>
    </form>
</div>
</body>
</html>