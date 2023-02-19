<?php

session_start();
include('db.php');
error_reporting(E_ERROR | E_PARSE);

$select_nb_item = $bdd->prepare('SELECT id_from_user FROM cart WHERE id_from_user = ?');
$select_nb_item->execute(array($_SESSION['id']));
$nb_item_user = $select_nb_item->rowCount();

$select_stock = $bdd->prepare('SELECT * FROM stock WHERE id_from_item_stock = ?');
$select_stock->execute(array($_GET['item_id']));
$stock = $select_stock->fetch();

if(isset($_GET['item_id'])) { 
    $select_item = $bdd->prepare('SELECT * FROM article WHERE author_id = ? AND item_id = ?');
    $select_item->execute(array($_SESSION['id'], $_GET['item_id']));
    $item = $select_item->fetch();

    if(isset($_POST['submit'])) {
        if($_POST['name'] != $item['name']) {
            $update_name = $bdd->prepare('UPDATE article SET name = ? WHERE author_id = ? AND item_id = ?');
            $update_name->execute(array($_POST['name'], $_SESSION['id'], $_GET['item_id']));
        }

        if($_POST['description'] != $item['description']) {
            $update_desc_item = $bdd->prepare('UPDATE article SET description = ? WHERE author_id = ? AND item_id = ?');
            $update_desc_item->execute(array($_POST['description'], $_SESSION['id'], $_GET['item_id']));
        }

        if($_POST['price'] != $item['price']) {
            $update_price = $bdd->prepare('UPDATE article SET price = ? WHERE author_id = ? AND item_id = ?');
            $update_price->execute(array($_POST['price'], $_SESSION['id'], $_GET['item_id']));
        }

        if($_POST['in_stock'] != $stock['number_in_stock']) {
            $update_stock = $bdd->prepare('UPDATE stock SET number_in_stock = ? WHERE id_from_item_stock = ?');
            $update_stock->execute(array($_POST['in_stock'], $_GET['item_id']));
        }

        header('Location: edit.php');
    }

    if(isset($_POST['del'])) {
        $image_name = $item['article_pic_link'];
        $extension_image = $item['extension_image'];
        $file = "images/{$image_name}{$extension_image}";
        unlink($file);
        $delete_item = $bdd->prepare('DELETE FROM article WHERE item_id = ?');
        $delete_item->execute(array($item['item_id']));
        header("Location: edit.php");
    }?>

<title>Edit item</title>
<div>
    <form method="POST">
        <img style=" width: 20%;postion: absolute;" src="img/<?php echo $item['article_pic_link']; ?><?php echo $item['extension_image']; ?>" alt="">
        <p>Name : <input name="name" value=<?=$item['name']?>></input></p>
        <p>Describe : <input type="text" name="description" value=<?=$item['description']?>></input></p>
        <p>Price : <input name="price" value=<?=$item['price']?>></input>€</p>
        <p>Quantity : <input name="in_stock" value=<?=$stock['number_in_stock']?>></input></p>
        <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">

        <input style="background-color: #98de98" type="submit" value="submit" name="submit">
        <input style="background-color: #ff8c8c;" type="submit" value="delete" name="del">
    </form>
</div>

<?php
} else { ?>
    <link rel="stylesheet" href="css/edit.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>My items - Silkroad</title>
    <div>
        <ul class="topnav">
            <a href="index.php"><img src="img/silkroad.png" class="logo_silkroad">
                <h3 class="marque">Silkroad</h3>
            </a> 
            <?php
            if(isset($_SESSION['id'])) { ?>
                <li class="Tosell"><a href="sell.php">+ Add item</a></li>
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
        <?php
        $select_item = $bdd->prepare('SELECT * FROM article WHERE author_id = ?');
        $select_item->execute(array($_SESSION['id']));
        $nb_item = $select_item->rowCount();

        if($nb_item >= 1) {
            while($item = $select_item->fetch()) { ?>
                <div>
                    <form method="GET">
                        <a name="item_id" style="text-decoration: none;" href="edit.php?item_id=<?php echo $item['item_id']; ?>">
                            <img style=" width: 20%; postion: absolute; " src="img/<?php echo $item['article_pic_link']; ?><?php echo $item['extension_image']; ?>" alt="">
                            <p><?php echo $item['name']; ?> &nbsp; <?php echo $item['price']; ?>€</p>
                        </a>
                    </form>
                </div>
                <br><br>
            <?php
            }

        } else {
            echo"You have no item";
        } ?>
    </div>
<?php
} ?>