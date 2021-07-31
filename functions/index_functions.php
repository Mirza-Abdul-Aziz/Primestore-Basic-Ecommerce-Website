<?php
include "../Ecommerce/create-connection.php";

// ================================================= Function for showing page numbers at bottom =============================================================

function pagination($page_no, $number_of_page){?>
    <div class="row">
        <div class="col-sm-12">
            <hr class="bg-dark font-weight-bolder">
            <nav>
                <ul class="pagination justify-content-center">
                    <?php
                    for($page = 1; $page<= $number_of_page; $page++) {
                        if($page==$page_no){
                            echo '<li class="page-item active"><a class="page-link" href = "index.php?page=' . $page . '">' . $page . ' </a></li>';
                        }else{
                            echo '<li class="page-item"><a class="page-link" href = "index.php?page=' . $page . '">' . $page . ' </a></li>';
                        }
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>
    <?php
}

function get_cat(){
    global $con;
    $result_set = $con->query('SELECT * from categories order by id');
    while ($row = $result_set->fetch_array()) {
        $c_name = $row['name'];
        $c_id = $row['id'];
        echo "<a class='nav-link bg-dark' style='color: lightgray;' href='index.php?c_id=$c_id&c_name=$c_name'>$c_name</a>";
    }
}

// ================================================= Function for showing page numbers when category is set at bottom =============================================================

function pagination_with_category($page_no, $number_of_page, $c_id, $c_name){?>
    <div class="row">
        <div class="col-sm-12">
            <hr class="bg-dark font-weight-bolder">
            <nav>
                <ul class="pagination justify-content-center">
                    <?php
                    for($page = 1; $page<= $number_of_page; $page++) {
                        if($page==$page_no){
                            echo '<li class="page-item active"><a class="page-link" href = "index.php?page=' . $page . '">' . $page . ' </a></li>';
                        }else{
                            echo '<li class="page-item"><a class="page-link" href = "index.php?page='.$page.'&c_id='.$c_id.'&c_name='.$c_name.'">' . $page . ' </a></li>';
                        }
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>
    <?php
}

// ================================================= Function for showing all the products added =============================================================

function show_all_products(){ global $con;?>
    <div id="main-container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    <h3>Catagories</h3>
                    <?php
                    get_cat();
                    ?>
                </div>
                <div class="col-md-10">
                    <h3>Featured Products</h3>
                    <div class="row">
                        <?php
                        $page_size = 16;
                        $result_set = $con->query("SELECT * FROM products where status=1 ORDER BY id");
                        $number_of_result = mysqli_num_rows($result_set);
                        $number_of_page = ceil ($number_of_result / $page_size);
                        if (!isset ($_GET['page']) ) {
                            $page_no = 1;
                        } else {
                            $page_no = $_GET['page'];
                        }
                        $page_first_result = ($page_no-1) * $page_size;
                        $result_set = $con->query("SELECT * FROM products where status=1 ORDER BY id DESC limit $page_first_result, $page_size", MYSQLI_STORE_RESULT);
                        while ($row = $result_set->fetch_array()) {
                            ?>
                            <div class="col-xl-3" >
                                <div class="card" id="card-style">
                                    <?php
                                    $filename = "Products%20Images/".$row['main_picture_file_name'];
                                    $prod_id = $row['id'];
                                    echo "<a href='product.php?product_id=$prod_id'><img class=\"card-img-top\" src=$filename height='200px' width='200px'></a>";
                                    ?>
                                    <div class="card-body">
                                        <h5 class="card-title"><a href="product.php?product_id=<?= $prod_id ?>" style="color: lightgray"><?= $row['title'] ?></a></h5>
                                        <?php
                                        $user_id = $row['user_id'];
                                        $statement = $con->prepare("SELECT * from users where id=?");
                                        if (!$statement)
                                            echo "Statement could not be created";
                                        $statement->bind_param("i", $user_id);
                                        $statement->execute() or die("Could not execute DB statement.");
                                        $result = $statement->get_result();
                                        $rs = $result->fetch_array();
                                        $shop_title = $rs['shop_title'];
                                        ?>
                                        Seller: <a href="index.php?shop_title=<?=$shop_title?>&user_id=<?=$user_id?>" class="font-weight-bolder" style="color: lightgray"><?= $shop_title ?></a>
                                        <p class="price">
                                            <?= $row['price'] ?> PKR
                                        </p>
                                        <div style="text-align: center">
                                            <form action="cart.php" method="get">
                                                <input type="hidden" name="id" value="<?= $prod_id ?>">
                                                <input type="hidden" name="title" value="<?= $row['title'] ?>">
                                                <input type="hidden" name="picture" value="<?= $row['main_picture_file_name'] ?>">
                                                <input type="hidden" name="price" value="<?= $row['price'] ?>">
                                                <button type="submit" id="button"><i class="fa fa-shopping-cart" style="color:black; padding-right: 5px"></i>ADD TO CART</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php }
                        ?>
                    </div>
                </div>
            </div>
            <?php
            pagination($page_no, $number_of_page);
            ?>
        </div>
    </div>
    <?php
}

function filter_by_cat_shop($cname, $cid, $shop_title, $user_id){

}

function filter_by_shop($shop_title, $user_id){global $con?>
    <div id="main-container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    <h3>Catagories</h3>
                    <?php
                    get_cat();
                    ?>
                </div>
                <div class="col-md-10">
                    <h3><?= "All products listed by ".$shop_title  ?></h3>
                    <div class="row">
                        <?php
                        $page_size = 16;
                        $result_set = $con->query("SELECT * FROM products where user_id=$user_id and status=1 ORDER BY id");
                        $number_of_result = mysqli_num_rows($result_set);
                        $number_of_page = ceil ($number_of_result / $page_size);
                        if (!isset ($_GET['page']) ) {
                            $page_no = 1;
                        } else {
                            $page_no = $_GET['page'];
                            $_SESSION['page_no'] = $page_no;
                        }
                        $page_first_result = ($page_no-1) * $page_size;
                        $result_set = $con->query("SELECT * FROM products where user_id=$user_id and status=1 ORDER BY id DESC limit $page_first_result, $page_size", MYSQLI_STORE_RESULT);
                        while ($row = $result_set->fetch_array()) {
                            ?>
                            <div class="col-xl-3" >
                                <div class="card" id="card-style">
                                    <?php
                                    $filename = "Products%20Images/".$row['main_picture_file_name'];
                                    $prod_id = $row['id'];
                                    echo "<a href='product.php?product_id=$prod_id'><img class=\"card-img-top\" src=$filename height='200px' width='200px'></a>";
                                    ?>
                                    <div class="card-body">
                                        <h5 class="card-title"><a href="product.php?product_id=<?=$prod_id?>"><?= $row['title'] ?></a></h5>
                                        <?php
                                        $user_id = $row['user_id'];
                                        $statement = $con->prepare("SELECT * from users where id=?");
                                        if (!$statement)
                                            echo "Statement could not be created";
                                        $statement->bind_param("i", $user_id);
                                        $statement->execute() or die("Could not execute DB statement.");
                                        $result = $statement->get_result();
                                        $rs = $result->fetch_array();
                                        $shop_title = $rs['shop_title'];
                                        ?>
                                        Seller: <a href="index.php?shop_title=<?=$shop_title?>&user_id=<?= $user_id ?>" class="font-weight-bolder" style="color: lightgray"><?= $shop_title ?></a>
                                        <p class="price">
                                            <?= $row['price'] ?> PKR
                                        </p>
                                        <div style="text-align: center">
                                            <a href="#" class="btn" id="button">Add to cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <hr class="bg-dark font-weight-bolder">
                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php
                            for($page = 1; $page<= $number_of_page; $page++) {
                                if($page==$page_no){
                                    echo '<li class="page-item active"><a class="page-link" href = "index.php?page=' . $page . '">' . $page . ' </a></li>';
                                }else{
                                    echo '<li class="page-item"><a class="page-link" href = "index.php?page='.$page.'&user_id='.$user_id.'&shop_title='.$shop_title.'">' . $page . ' </a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function filter_by_category($cname, $cid){global $con?>
    <div id="main-container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    <h3>Catagories</h3>
                    <?php
                    get_cat();
                    ?>
                </div>
                <div class="col-md-10">
                    <h3><?= $cname ?></h3>
                    <div class="row">
                        <?php
                        $page_size = 16;
                        $result_set = $con->query("SELECT * FROM products where category_id=$cid and status=1 ORDER BY id");
                        $number_of_result = mysqli_num_rows($result_set);
                        $number_of_page = ceil ($number_of_result / $page_size);
                        if (!isset ($_GET['page']) ) {
                            $page_no = 1;
                        } else {
                            $page_no = $_GET['page'];
                            $_SESSION['page_no'] = $page_no;
                        }
                        $page_first_result = ($page_no-1) * $page_size;
                        $result_set = $con->query("SELECT * FROM products where category_id=$cid and status=1 ORDER BY id DESC limit $page_first_result, $page_size", MYSQLI_STORE_RESULT);
                        while ($row = $result_set->fetch_array()) {
                            ?>
                            <div class="col-xl-3" >
                                <div class="card" id="card-style">
                                    <?php
                                    $filename = "Products%20Images/".$row['main_picture_file_name'];
                                    $prod_id = $row['id'];
                                    echo "<a href='product.php?product_id=$prod_id'><img class=\"card-img-top\" src=$filename height='200px' width='200px'></a>";
                                    ?>
                                    <div class="card-body">
                                        <h5 class="card-title"><a href="product.php?product_id=<?= $prod_id ?>"><?= $row['title'] ?></a></h5>
                                        <?php
                                        $user_id = $row['user_id'];
                                        $statement = $con->prepare("SELECT * from users where id=?");
                                        if (!$statement)
                                            echo "Statement could not be created";
                                        $statement->bind_param("i", $user_id);
                                        $statement->execute() or die("Could not execute DB statement.");
                                        $result = $statement->get_result();
                                        $rs = $result->fetch_array();
                                        $shop_title = $rs['shop_title'];
                                        ?>
                                        Seller: <a href="index.php?shop_title=<?=$shop_title?>&user_id=<?= $user_id ?>" class="font-weight-bolder" style="color: lightgray"><?= $shop_title ?></a>
                                        <p class="price">
                                            <?= $row['price'] ?> PKR
                                        </p>
                                        <div style="text-align: center">
                                            <a href="#" class="btn" id="button">Add to cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        ?>
                    </div>
                </div>
            </div>
            <?php
            pagination_with_category($page_no, $number_of_page, $cid, $cname);
            ?>
        </div>
    </div>
    <?php
}

// ================================================= Function for showing all products when user is not logged in =============================================================

function session_ns_category_ns_user_ns(){
    include("navbar.php");
    show_all_products();
    include("footer.html");
}

// ================================================= Function for showing filtered products =============================================================

function session_ns_category_s_user_ns(){
    $cid = $_REQUEST['c_id'];
    $cname = $_REQUEST['c_name'];
    //$_SESSION['cid'] = $cid;
    include("navbar.php");
    filter_by_category($cname, $cid);
    include("footer.html");
}

// ================================================= Function for showing all products when user is  logged in =============================================================

function session_s_category_ns_user_ns(){
    include("logged-in-navbar.php");
    show_all_products();
    include("logged-in-footer.html");
}
function session_s_category_s_user_ns(){
    $cid = $_REQUEST['c_id'];
    $cname = $_REQUEST['c_name'];
    include("logged-in-navbar.php");
    filter_by_category($cname, $cid);
    include("logged-in-footer.html");
}

function session_ns_category_ns_user_s(){
    $user_id = $_REQUEST['user_id'];
    $shop_title = $_REQUEST['shop_title'];
    include ("navbar.php");
    filter_by_shop($shop_title, $user_id);
    include ("footer.html");
}

function session_s_category_s_user_s(){
    include ("logged-in-navbar.php");
    filter_by_cat_shop($cname, $cid, $shop_title, $user_id);
    include ("logged-in-footer.html");
}

function session_ns_category_s_user_s(){
    include ("navbar.php");
    filter_by_cat_shop($cname, $cid, $shop_title, $user_id);
    include ("footer.html");
}

function session_s_category_ns_user_s(){
    $user_id = $_REQUEST['user_id'];
    $shop_title = $_REQUEST['shop_title'];
    include ("logged-in-navbar.php");
    filter_by_shop($shop_title, $user_id);
    include ("logged-in-footer.html");
}
?>


