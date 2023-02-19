<?php

session_start();
include('db.php');
error_reporting(E_ERROR | E_PARSE);

if(isset($_GET['accueil'])) { 
    $select_nombre_item = $bdd->prepare('SELECT id_from_user FROM cart WHERE id_from_user = ?');
	$select_nombre_item->execute(array($_SESSION['id']));
	$nombre_item_user = $select_nombre_item->rowCount();
    $verif_id = $bdd->prepare('SELECT * FROM user WHERE user_id = ?');
    $verif_id->execute(array($_GET['accueil']));
    $user = $verif_id->fetch();
    $id_exist = $verif_id->rowCount();

    if($id_exist === 1) {
        if($user['role'] == 1) {
        
            if(isset($_POST['deco'])) {
                session_destroy();
                header('Location: index.php'); 
            }?>

            <link rel="stylesheet" href="css/admin.css" type="text/css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <title>ADMIN - silkroad</title>
            
            <div>
                <ul class="topnav">
                    <a href="index.php">
                        <img src="img/silkroad.png" class="logo_silkroad">
                        <h3 class="marque">Silkroad</h3>
                    </a><?php
                    
                    if(isset($_SESSION['id'])) { ?>
                        <li class="Tosell"><a href="sell.php">+ Add item</a></li>
                        <li class="Tosell2"><a href="edit.php">My items</a></li>
                        <li><a href="cart.php">Cart - <?php echo $nombre_item_user; ?></a></li>
                        <li><a href="chat_list.php">Chat</a></li>
                        <li><a href="account.php">Account</a></li>
                    <?php 
                    }
                    if(isset($_SESSION['id'])) { 
                        $select_username = $bdd->prepare('SELECT * FROM user WHERE user_id = ?');
                        $select_username->execute(array($_SESSION['id']));
                        $user = $select_username->fetch();?>
                    
                    <?php
                    } else { ?>
                        <li><a href="login.php">Log in</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php
                    } ?>
                </ul>
            </div>
            <form method="POST">
                <?php
                if($_SESSION['id']) { 
                    $select_username = $bdd->prepare('SELECT * FROM user WHERE user_id = ?');
                    $select_username->execute(array($_SESSION['id']));
                    $user = $select_username->fetch();?>
                    <?php
                
                } else {?>
                    <input type="submit" name="Login" value="Login">
                    <input type="submit" name="register" value="register">
                <?php
                }?>
            </form>
            <?php
            $select_ask_approve = $bdd->query('SELECT * FROM article WHERE approuve = 0');
            $ask_approve = $select_ask_approve->rowCount(); ?>
            <div>
                <a href="list_to_approve.php">Request(s) pending: <?php echo $ask_approve; ?></a>
            </div>
            <?php
            
            if(isset($_POST['update'])) {
                $item_id = $_POST['item_id'];
                $name = $_POST['name'];
                $description = $_POST['describe'];
                $price = $_POST['price'];
                $stmt = $bdd->prepare("UPDATE article SET name = :name, description = :description, price = :price WHERE item_id = :item_id");
                $stmt->execute(['name' => $name, 'description' => $description, 'price' => $price, 'item_id' => $item_id]);
                

            } elseif(isset($_POST['del'])) {
                $my_item = $_POST['item_id'];
                $delete_query = $bdd->prepare("DELETE FROM article WHERE item_id = :item_id");
                $delete_query->bindParam(":item_id", $my_item);
                $delete_query->execute();
                header("Location: admin.php");
            }

            if(isset($_POST['update_user'])) {
                $user_id = $_POST['user_id'];
                $username = $_POST['username'];
                $mail = $_POST['mail'];
                $password_crypted = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $stmt = $bdd->prepare("UPDATE user SET username = :username, mail = :mail, password = :password WHERE user_id = :user_id");
                $stmt->execute(['username' => $username, 'mail' => $mail, 'password' => $password_crypted, 'user_id' => $user_id]);

            } elseif(isset($_POST['del_user'])) {
                $my_user = $_POST['user_id'];
                $delete_query = $bdd->prepare("DELETE FROM user WHERE user_id = :user_id");
                $delete_query->bindParam(":user_id", $my_user);
                $delete_query->execute();
                header("Location: admin.php");
            }

            $select_items = $bdd->query('SELECT * FROM article WHERE item_id > 0');
            $select_users = $bdd->query('SELECT * FROM user WHERE user_id > 0'); ?>

            <div style="display:flex; flex-wrap: wrap; margin-top: 20px">
                <div style="flex: 1; margin: 0 10px;">
                    <label style="font-size: 200%">Item List</label>
                    <?php
                    while($item = $select_items->fetch()) { 
                        ?>
                        <div>
                            <form method="POST">
                                <img src="img/<?php echo $item['article_pic_link']; ?><?php echo $item['extension_image']; ?>" alt="">
                                <p>Name : <input name="name" value=<?=$item['name']?>></input></p>
                                <p>Describe : <input type="text" name="describe" value=<?=$item['description']?>></input></p>
                                <p>Price : <input name="price" value=<?=$item['price']?>></input>€</p>
                                <p>Id : <?php echo $item['item_id']; ?></p>
                                <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                <input style="background-color: #98de98" type="submit" value="update" name="update">
                                <input style="background-color: #ff8c8c;" type="submit" value="delete" name="del">
                                </a>
                            </form>
                        </div>
                    <?php
                    } ?>
                </div>
                <br><br>
                <div style="flex: 1; margin: 0 10px;">
                    <label style="font-size: 200%">User list</label>
                    <?php
                    while($user = $select_users->fetch()) { ?>
                        <div>
                            <form method="POST">
                                    <img src="img_profile/<?php echo $user['profile_pic']; ?>" style="width: 20%;">
                                    <p>Username : <input name="username" value=<?=$user['username']?>></input></p>
                                    <p>Mail : <input name="mail" value=<?=$user['mail']?>></input></p>
                                    <p>Password : <input name="password" value=<?=$user['password']?>></input></p>
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <input style="background-color: #98de98" type="submit" value="update_user" name="update_user">
                                    <input style="background-color: #ff8c8c;" type="submit" value="delete" name="del_user">
                            </form>
                        </div>
                        <br><br>
                    <?php
                    } ?>
                </div>
            </div>
        <?php
        } else {
            echo "You are not an admin";
        }

    } else {
        echo "Invalid credentials";
    }

} else {
    if(isset($_POST['submit'])) {
        if(!empty($_POST['username']) AND !empty($_POST['password'])) {
            $verif_username = $bdd->prepare('SELECT * FROM user WHERE username = ?');
            $verif_username->execute(array($_POST['username']));
            $user = $verif_username->fetch();
            $username_exist = $verif_username->rowCount();

            if($username_exist === 1) {
                if($user['role'] == 1) {
                    if(password_verify($_POST['password'], $user['password'])) {
                        $_SESSION['id'] = $user['id'];
                        header('Location: index.php');

                    } else {
                        echo "wrong password";
                    }

                } else {
                    echo "not an administator";
                }
            }
        }
    }

    if(isset($_SESSION['id'])) {
        header("Location: admin.php?accueil=" .$_SESSION['id']);

    } else { ?>
        <title>Login</title>
        <div>
            <form method="POST">
                <input type="text" placeholder="Username" name="username">
                <input type="password" placeholder="Password" name="password">
                <input type="submit" value="submit" name="submit">
            </form>
        </div>
    <?php
    }
}