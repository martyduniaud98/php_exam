<?php

session_start();
include('db.php');
error_reporting(E_ERROR | E_PARSE);

$select_nb_item = $bdd->prepare('SELECT id_from_user FROM cart WHERE id_from_user = ?');
$select_nb_item->execute(array($_SESSION['id']));
$nb_item_user = $select_nb_item->rowCount();

if ($_SESSION['id']) { ?>
    <link rel="stylesheet" href="css/chat_list.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Chat - Silkroad</title>
    
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
                <li><a href="account.php">Account</a></li>
            <?php 
            }

            if(isset($_SESSION['id'])) { 
                $select_username = $bdd->prepare('SELECT * FROM user WHERE user_id = ?');
                $select_username->execute(array($_SESSION['id']));
                $user = $select_username->fetch();?>
                <li><a href="index.php?deco">Logout</a> </li>
            
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
        $select_chat = $bdd->prepare('SELECT * FROM discussions WHERE user_rec = ?');
        $select_chat->execute(array($_SESSION['id']));

        while ($chat = $select_chat->fetch()) {
            $select_user = $bdd->prepare('SELECT * FROM user WHERE user_id = ?');
            $select_user->execute(array($chat['user_env']));
            $user = $select_user->fetch(); ?>

            <div>
                <a href="chat_with_owner.php?user_id=<?php echo $user['user_id']; ?>"><?php echo $user['username']; ?></a>
            </div>
            <br><br>

        <?php
        }
        ?>
    </div>
<?php
}
?>