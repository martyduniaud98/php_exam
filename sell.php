<?php

session_start();
include('db.php');
error_reporting(E_ERROR | E_PARSE);

$select_nb_item = $bdd->prepare('SELECT id_from_user FROM cart WHERE id_from_user = ?');
$select_nb_item->execute(array($_SESSION['id']));
$nb_item_user = $select_nb_item->rowCount();

if(!empty($_SESSION['id'])) {
    if(isset($_POST['submit'])) {     
        if(!empty($_POST['name']) AND !empty($_POST['description']) AND !empty($_POST['price'])) {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                if(isset($_FILES['image']) AND !empty($_FILES['image']['name'])) {
                    $item_id = uniqid();
                    
                    foreach ($_FILES['image']['name'] as $key => $name) {
                        $chaine = uniqid();
                        $extension_upload = strrchr($_FILES['image']['name'][$key], '.');
                        $name = "img/{$chaine}{$extension_upload}";
                        $result = move_uploaded_file($_FILES['image']['tmp_name'][$key],$name);
                        $date = date("j-n-Y"); 
                        $insert_item = $bdd->prepare("INSERT INTO article (name, description, author_id, date_publication, price, id_image, article_pic_link, extension_image, approuve) VALUES (?,?,?,?,?,?,?,?,?)");
                        $insert_item->execute(array($_POST['name'], $_POST['description'] , $_SESSION['id'] , $date, $_POST['price'], $item_id, $chaine, $extension_upload, '0', ));
                        $id_item = $bdd->lastInsertId();
                        $insert_stock = $bdd->prepare("INSERT INTO stock (number_in_stock, id_from_item_stock) VALUES (?,?)");
                        $insert_stock->execute(array($_POST['quantity'], $id_item));

                        if ($result){
                            echo "Image uploaded. ";
                        } else {
                            echo "Image not uploaded";
                        }
                    }

                } else {
                    echo "No image";
                }
            }
            echo"Your product has been added";

        } else {
            echo "Please fill all the fields";
        }
    } ?>

    <link rel="stylesheet" href="css/sell.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Sell - Silkroad</title>
    <div>
        <ul class="topnav">
            <a href="index.php">
                <img src="img/silkroad.png" class="logo_silkroad">
                <h3 class="marque">Silkroad</h3>
            </a> 
            
            <?php
            if(isset($_SESSION['id'])) { ?>
                <li class="Tosell2"><a href="edit.php">My items</a></li>
                <li><a href="cart.php">Cart - <?php echo $nb_item_user; ?></a></li>
                <li><a href="chat_list.php">Chat</a></li>
                <li><a href="account.php">Account</a></li>
            <?php 
            }

            if(isset($_SESSION['id'])) {     
                $select_username = $bdd->prepare('SELECT * FROM user WHERE user_id = ?');
                $select_username->execute(array($_SESSION['id']));
                $user = $select_username->fetch();?>
                <li><a href="index.php?deco">Logout</a></li>
            
            <?php
            } else { ?>
                <li><a href="login.php">Log in</a></li>
                <li><a href="register.php">Register</a></li>
            <?php
            } ?>
        </ul>
    </div>

    <div>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" placeholder="Name of product" name="name">
            <input type="text" placeholder="Description" name="description">
            <input type="text" placeholder="Quantity" name="quantity">
            <input type="text" placeholder="Price" name="price">
            <input type="file" name="image[]" />
            <input type="submit" value="Send my add to admins" name="submit">
        </form>
    </div>

<?php
} else {
    header('Location: index.php');
} ?>