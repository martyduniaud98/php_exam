<?php

session_start();
include('db.php');
error_reporting(E_ERROR | E_PARSE);

if (!(isset($_SESSION['id']))) {
    header('Location: index.php');
}

$select_nb_item = $bdd->prepare('SELECT id_from_user FROM cart WHERE id_from_user = ?');
$select_nb_item->execute(array($_SESSION['id']));
$nb_item_user = $select_nb_item->rowCount();
    
$verif_username = $bdd->prepare('SELECT * FROM user WHERE user_id = ?');
$verif_username->execute(array($_SESSION['id']));
$user = $verif_username->fetch();
$user_exist = $verif_username->rowCount();

$temporary_name = $_FILES["profile_pic"]["tmp_name"];
$file_name = basename($_FILES["profile_pic"]["name"]);
$download_folder = "/";
$path_file = $download_folder . $file_name;
move_uploaded_file($temporary_name, $path_file);

if(isset($_POST['validate'])) {
    if($_POST['username'] != $user['username']) {
        $update_name = $bdd->prepare('UPDATE user SET username = ? WHERE user_id = ?');
        $update_name->execute(array($_POST['username'], $_SESSION['id']));
    }
    
    if($file_name != "") {
        if($_POST['profile_pic'] != $user['profile_pic']) {
            $update_profil_profil_pic = $bdd->prepare('UPDATE user SET profile_pic = ? WHERE user_id = ?');
            $update_profil_profil_pic->execute(array($path_file, $_SESSION['id']));
        }
    }

    if($_POST['mail'] != $user['mail']) {
        $update_mail = $bdd->prepare('UPDATE user SET mail = ? WHERE user_id = ?');
        $update_mail->execute(array($_POST['mail'], $_SESSION['id']));
    }

    if($_POST['password'] != $user['password']) {
        $update_password = $bdd->prepare('UPDATE user SET password = ? WHERE user_id = ?');
        $update_password->execute(array(password_hash($_POST['password'], PASSWORD_BCRYPT), $_SESSION['id']));
    }

    if($_POST['wallet'] != $user['wallet']) {
        $update_wallet = $bdd->prepare('UPDATE user SET wallet = ? WHERE user_id = ?');
        $update_wallet->execute(array($_POST['wallet'], $_SESSION['id']));
    }
} ?>

<title>Account - Silkroad</title>
<link rel="stylesheet" href="css/account.css" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div>
    <ul class="topnav">
        <a href="index.php">
            <img src="img/silkroad.png" class="logo_silkroad">
            <h3 class="marque">Silkroad</h3>
        </a> <?php
        
        if(isset($_SESSION['id'])) { ?>
            <li class="Tosell"><a href="sell.php">+ Add item</a></li>
            <li class="Tosell2"><a href="edit.php">My items</a></li>
            <li><a href="cart.php">Cart - <?php echo $nb_item_user; ?></a></li>
            <li><a href="chat_list.php">Chat</a></li>
            <?php
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
<?php
$select_article = $bdd->prepare('SELECT * FROM article WHERE author_id = ?');
$select_article->execute(array($_SESSION['id']));
$nbr_article = $select_article->rowCount();


if(isset($_GET['user_id'])) { 
    //afficher les infos de l'utilisateur
    $select_author = $bdd->prepare('SELECT * FROM user WHERE user_id = ?');
    $select_author->execute(array($_GET['user_id']));
    $author = $select_author->fetch();
    ?>
    
    <div style="display:flex; flex-wrap: wrap; margin-top: 20px">
        <div style="flex: 1; margin: 0 10px;">
            <form method="POST" enctype="multipart/form-data">
                <img src="img_profile/<?php echo $author['profile_pic']; ?>" style="width: 20%;">
                <p>Username : <?php echo $author['username']?></p>              
                <p>Mail : <?php echo $author['mail']?></p>
            </form> 
        </div>

        <?php
        //afficher les articles de l'utilisateur
        $select_item = $bdd->prepare('SELECT * FROM article WHERE author_id = ?');
        $select_item->execute(array($_GET['user_id']));
        $nb_item = $select_item->rowCount(); ?>

        <div style="flex: 1; margin: 0 10px;">
            <label style="font-size: 200%">Item List</label>
            <?php
            if($nb_item >= 1) {
                while($item = $select_item->fetch()) { ?>
                <form method="GET">
                    <p name="item_id" value=<?=$item['item_id']?>>
                        <img style=" width: 20%; postion: absolute;" src="img/<?php echo $item['article_pic_link']; ?><?php echo $item['extension_image']; ?>" alt="">
                        <p><?php echo $item['name']; ?> &nbsp; <?php echo $item['price']; ?>€</p>
                    </p>
                </form>
                <?php 
                }
            } ?>
        </div>
    </div>
<?php 
} else { ?>
    <div style="display:flex; flex-wrap: wrap; margin-top: 20px">
        <div style="flex: 1; margin: 0 10px;">
            <form method="POST" enctype="multipart/form-data">
                <img src="img_profile/<?php echo $user['profile_pic']; ?>" style="width: 20%;">
                <p>Change profil_pic : <input type="file" name="profile_pic" id="profile_pic"></input></p>
                <p>Change Username : <input name="username" value=<?=$user['username']?>></input></p>              
                <p>Change Mail : <input name ="mail" value=<?=$user['mail']?>></input></p>
                <p>Change Password : <input name ="password" value=<?=$user['password']?>></input></p>
                <p>Change Wallet : <input name ="wallet" value=<?=$user['wallet']?>></input></p>
                <input type="submit" value="Validate" name="validate">
            </form> 
        </div>

        <?php
        $select_item = $bdd->prepare('SELECT * FROM article WHERE author_id = ?');
        $select_item->execute(array($_SESSION['id']));
        $nb_item = $select_item->rowCount(); ?>

        <div style="flex: 1; margin: 0 10px;">
            <label style="font-size: 200%">Item List</label>
            <?php
            if($nb_item >= 1) {
                while($item = $select_item->fetch()) { ?>
                <form method="GET">
                    <p name="item_id" value=<?=$item['item_id']?>>
                        <img style=" width: 20%; postion: absolute;" src="img/<?php echo $item['article_pic_link']; ?><?php echo $item['extension_image']; ?>" alt="">
                        <p><?php echo $item['name']; ?> &nbsp; <?php echo $item['price']; ?>€</p>
                    </p>
                </form>
                <?php 
                }
            } else {
                echo"You have no item";
            } ?>
        </div>

        <?php 
        $select_history = $bdd->prepare('SELECT * FROM invoice WHERE id_from_user = ?');
        $select_history->execute(array($_SESSION['id']));
        $nb_history = $select_history->rowCount();
        ?>

        <div style="flex: 1; margin: 0 10px;">
            <label style="font-size: 200%">Invoices</label>
            <?php
            if($nb_history >= 1) {
                while($history = $select_history->fetch()) { 
                $select_item = $bdd->prepare('SELECT * FROM article WHERE item_id = ?');
                $select_item->execute(array($history['id_from_item']));
                $item = $select_item->fetch();?>
                <form style="background-color: #cdcdcd;" method="GET">
                    <p name="id_from_item" value=<?=$history['id_from_item']?>>
                        <p><?php echo $item['name'] ?></p>
                        <p><?php echo $history['date']; ?> </p>
                        <p><?php echo $history['address'];?> - <?php echo $history['city'] ?> - <?php echo $history['zipcode'] ?> </p>
                        <p>Quantity : <?php echo $history['number_in_cart'] ?></p>
                        <p>Total price : <?php echo $history['price'] * $history['number_in_cart']; ?>€</p>
                    </p>
                </form>
                <?php 
                } 
            } else {
                echo"\nYou have no history";
            } ?>
        </div>
    </div>
<?php 
} ?>