<?php

session_start();
include('db.php');
error_reporting(E_ERROR | E_PARSE);

if (isset($_POST['submit'])) {
    if (!empty($_POST['username']) and !empty($_POST['password'])) {
        $check_username = $bdd->prepare('SELECT * FROM user WHERE username = ?');
        $check_username->execute(array($_POST['username']));
        $user = $check_username->fetch();
        $username_exist = $check_username->rowCount();

        if ($username_exist === 1) {
            if (password_verify($_POST['password'], $user['password'])) {
                $_SESSION['id'] = $user['user_id'];
                var_dump($user['user_id']);
            
            } else {
                echo "Wrong password";
            }
        }

    } else {
        echo "Please enter credentials";
    }
}

if (isset($_SESSION['id'])) {
    header('Location: index.php');

} else { ?>
    <link rel="stylesheet" href="css/login.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Login - Silkroad</title>
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
                <li><a href="account.php">Account</a></li>
            <?php 
            }
                
            if(isset($_SESSION['id'])) { 
                $select_username = $bdd->prepare('SELECT * FROM user WHERE user_id = ?');
                $select_username->execute(array($_SESSION['id']));
                $user = $select_username->fetch();?>
                <li><a href="index.php?deco">Logout</a></li>
            
            <?php
            } ?>
        </ul>
    </div>

    <div>
        <form method="POST">
            <input type="text" placeholder="Username" name="username">
            <input type="password" placeholder="Password" name="password">
            <input type="submit" value="Submit" name="submit">
        </form>
        <p><em>You do not have an account ? <a href="register.php">Register</a> </em></p>
    </div>
<?php
}
?>