<?php

session_start();
include('db.php');
error_reporting(E_ERROR | E_PARSE);

if(isset($_POST['submit'])) {
    if(!empty($_POST['username']) AND !empty($_POST['password']) AND !empty($_POST['password_conf']) AND !empty($_POST['mail']) AND !empty($_FILES['picture']) AND $_FILES["picture"]["error"] == UPLOAD_ERR_OK){
        $check = $bdd->prepare("SELECT COUNT(*) FROM user WHERE username = ? OR mail = ?");
        $check->execute([$_POST['username'], $_POST['mail']]);
        $count = $check->fetchColumn();
        
        $temporary_name = $_FILES["picture"]["tmp_name"];
        $file_name = basename($_FILES["picture"]["name"]);
        $download_folder = "img_profile/";
        $path_file = $download_folder . $file_name;
        move_uploaded_file($temporary_name, $path_file);

        if($count == 0) {
            if($_POST['password'] == $_POST['password_conf']) {
                $password_crypted = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $insert_user = $bdd->prepare("INSERT INTO user (username, password, role, mail, wallet, profile_pic) VALUES (?,?,?,?,?,?)");
                $insert_user->execute(array($_POST['username'], $password_crypted, '0',$_POST['mail'], '0', $file_name));
                $select_user = $bdd->prepare('SELECT * FROM user WHERE username = ?');
                $select_user->execute(array($_POST['username']));
                $user = $select_user->fetch();
                $_SESSION['id'] = $user['user_id'];
                header("Location: index.php");

            } else {
                $message = "Passwords don't match";
            }

        } else {
            $message = "Username or email already used";
        }

    } else {
        $message = "Please fill all the fields";
    }
} ?>

<link rel="stylesheet" href="css/register.css" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Register - Silkroad</title>
<div>
    <ul class="topnav">
        <a href="index.php">
            <img src="img/silkroad.png" class="logo_silkroad">
            <h3 class="marque">Silkroad</h3>
        </a> 

        <?php
        if(isset($_SESSION['id'])) { ?>
            <li class="Tosell"><a href="sell.php">+ Add item</a></li>
            <li class="Tosell2"><a href="edit.php">My items</a></li>
            <li><a href="cart.php">Cart - <?php echo $nb_item_user; ?></a></li>
            <li><a href="chat_list.php">Chat</a></li>
        <?php 
        }

        if(isset($_SESSION['id'])) { 
            $select_username = $bdd->prepare('SELECT * FROM user WHERE user_id = ?');
            $select_username->execute(array($_SESSION['id']));
            $user = $select_username->fetch();?>
            <li><a href="index.php?deco"><?php echo $user['username']; ?></a></li>
        
        <?php
        } ?>
    </ul>
</div>

<div>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" placeholder="Username" name="username">
        <input type="password" placeholder="Password" name="password">
        <input type="password" placeholder="Confirmation password" name="password_conf">
        <input type="email" placeholder="Mail" name="mail">
        <input type="file" name="picture" id="picture">        
        <input type="submit" value="Submit" name="submit">
    </form>
    <p><em>Already have an account ? <a href="login.php">Log in</a></em></p>
    
    <?php
    if(isset($message)) { ?>
        <p><?php echo $message;?></p>
    <?php
    } ?>
</div>