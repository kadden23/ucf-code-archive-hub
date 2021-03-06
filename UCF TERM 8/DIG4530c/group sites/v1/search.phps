<?php
include("include/session.php");
include("search_function.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />        
        <title>Sportopia Search</title>
        <style type="text/css">
            @import url("css/reset.css");
            @import url("css/960.css");
            @import url("css/styles.css");
        </style>
        <link rel="icon" href="img/favicon.png" />
    </head>
    <body>
        <div id="wrapper" class="container_16 clearfix">
            <div id="super-nav">
                <div class="container_16 clearfix">
                    <div class="grid_6 prefix_10">
                        <?php
                        if($session->logged_in){
                            echo "<p>Welcome $session->username, you are logged in.</p>"
                               ."<a class=\"button\" href=\"client.php?user=$session->username\">My Account</a>";
                            if($session->isAdmin()){
                                echo "<a class=\"button\" href=\"admin.php\">Admin Center</a>";
                            }
                            echo "<a class=\"button\" href=\"process.php\">Logout</a>";
                        
                        }else{
                            echo "<p>Welcome $session->username, you are not logged in.</p>";
                            echo "<a class=\"button\" href=\"login.php\">Login</a>";
                        } ?>
                        <a class="button" href="cart.php">Cart: <?php if (!isset($_SESSION["cart_array"])){ echo "0"; }else{echo count($_SESSION["cart_array"]);} ?></a>
                    </div>
                </div>
            </div>
            <div id="header">
                <div class="container_16 clearfix">
                        <div class="grid_4">
                            <div id="logo">
                                <h1>
                                    <a rel="home" title="Sportopia&copy;" href="home.php">Sportopia&copy;</a> 
                                </h1>
                            </div>
                        </div>
                        <div class="grid_12">
                            <div id="search">
                                 <form action="search.php" method="post">
                                       <input id="searchInput" type="text" name="searchText"/>
                                       <input id="searchSubmit" type="submit"  value="Search"/>
                                 </form>
                            </div>
                        </div>
                </div>
            </div>
            <div id="content">
                <div class="container_16 clearfix">
                    <div id="sidebar" class="grid_2">
                        <div id="navigation">
                            <ul>
                                    <li><a href="home.php">Home</a></li>
                                    <li><a href="catalog.php">Catalog</a></li>
                                    <li><a href="background.php">Background</a></li>
                                    <li><a href="policy.php">Policies</a></li>
                                <!--<li><a href="#">Basketball</a>
                                    <ul>
                                        <li><a href="#">Hats</a></li>
                                        <li><a href="#">Shirts</a></li>
                                        <li><a href="#">Basketballs</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Baseball</a>
                                    <ul>
                                        <li><a href="#">Hats</a></li>
                                        <li><a href="#">Shirts</a></li>
                                        <li><a href="#">Baseballs</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Contact Us</a></li>-->
                            </ul>
                        </div>
                    </div>
                    <div class="grid_14">
                        <?php
                        
                        if (isset($_POST['searchText'])) {
                           $suffix = "";
                           $searchText = mysql_real_escape_string(htmlentities(trim($_POST['searchText'])));
                        
                          $errors = array();
                        
                          if (empty($searchText)) {
                             $errors[] = 'Please enter a search term';
                        }    else if (strlen($searchText)<2) {
                             $errors[] = 'Your search term must be three or more characters';
                        }    else if (search_results($searchText) === false) {  
                             $errors[] = 'Your search for <span class="bold">\''.$searchText.'\'</span> returned no results';
                        }
                        
                        if (empty($errors)) {
                        
                             $results = search_results($searchText);
                             $results_num = count($results);
                        
                             $suffix = ($results_num !=1) ? 's' : '';
                        
                             echo '<p>Your search for
                                         <span class="bold">\''. $searchText, '\'</span>
                                   returned
                                         <span class="bold">\'', $results_num, '\'</span>
                                   result', $suffix, '</p>';
                        
                        
                        
                           foreach($results as $result){
                             echo '<br/><p>', '<a href=catalog.php?id=', $result['id'], '>', 'Product Name: ', $result['Product_Name'], '</a>', '<br/>', 'Description: ', $result['Description'], '<br/>', 'Brand: ',$result['Brand'], '<br/>', 'Category: ', $result['Category'], ' </p><br/>';
                        
                           }
                        
                        
                        
                           
                        } else {
                          foreach($errors as $error) {
                         echo $error, '</br>';
                        }
                        
                        }
                        }
                        
                        
                        ?>
                    </div>
                </div>
            </div>
            <div id="footer" class="container_16 clearfix">
                <div class="grid_8">
                        <p>&copy; Copyright 2011 <span class="bold">Sportopia</span>.  All Rights Reserved.</p>
                </div>
                <div class="grid_4">
                        <p>This site is not official and is an assignment for a UCF Digital Media Course</p>
                </div>
                <div class="grid_4">
                        <p>Designed by Group04</p>
                </div>
            </div>
        </div>
    </body>
</html>