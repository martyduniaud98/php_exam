<?php

session_start();
include('db.php');
error_reporting(E_ERROR | E_PARSE);

if($_SESSION['id']) {
    $key = "tp";

    if(isset($_GET['user_id'])) {
        $verif_username = $bdd->prepare('SELECT * FROM user WHERE user_id = ?');
        $verif_username->execute(array($_GET['user_id']));
        $user = $verif_username->fetch();
        $user_exist = $verif_username->rowCount();

        if($user_exist == 1) {
            if($_SESSION['id'] != $_GET['user_id']) {
                if(isset($_POST['submit'])) {
                    if(!empty($_POST['message'])) {
                        $date = date("d.m.y");
                        $messge = openssl_encrypt($_POST['message'], "AES-128-ECB", $key); 
                        $insert_message = $bdd->prepare("INSERT INTO discussions (user_env, user_rec, date, message) VALUES (?,?,?,?)");
                        $insert_message->execute(array($_SESSION['id'], $user['user_id'], $date, $messge));
                        header("Location: chat_with_owner.php?user_id=" .$_GET['user_id']);
                    }
                }

                if(isset($_POST['block'])) {
                    $date = date("d.m.y");
                    $insert_block = $bdd->prepare("INSERT INTO users_blocked (user_asking_block, user_blocked, date) VALUES (?,?,?)");
                    $insert_block->execute(array($_SESSION['id'], $user['user_id'], $date));
                    header("Location: chat_with_owner.php?user_id=" .$_GET['user_id']);
                }

                if(isset($_POST['unblock'])) {
                    $date = date("d.m.y");
                    $insert_block = $bdd->prepare('DELETE FROM users_blocked where user_blocked = ? AND user_asking_block = ?');
                    $insert_block->execute(array($user['user_id'], $_SESSION['id']));
                    header("Location: chat_with_owner.php?user_id=" .$_GET['user_id']);
                } ?>

                <title>Chat with <?php echo $user['username'];?></title>
                <body>
                    <div>
                        <div>
                            <p><a href="index.php"><em>Back Home</em></a> </p>
                            <?php
                            $verif_username_block = $bdd->prepare('SELECT * FROM users_blocked WHERE user_blocked = ? AND user_asking_block = ?');
                            $verif_username_block->execute(array($_GET['user_id'], $_SESSION['id']));
                            $user_is_block = $verif_username_block->rowCount();
                            
                            if($user_is_block === 0) { ?>
                                <form method="post">
                                    <input type="submit" name="block" value="block">
                                </form><?php

                            } else { ?>
                                <form method="post">
                                    <input type="submit" name="unblock" value="unblock">
                                </form><?php
                            } ?>
                        </div>

                        <div style="height=30%;"> 
                            <?php
                            $select_messages = $bdd->prepare('SELECT * FROM discussions WHERE user_env = ? AND user_rec = ? OR user_env = ? AND user_rec = ?');
                            $select_messages->execute(array($_SESSION['id'], $user['user_id'], $user['user_id'], $_SESSION['id']));
                            $nbr_message = $select_messages->rowCount();

                            if($nbr_message > 0) {
                                while($message = $select_messages->fetch()) { 
                                    $verif_username = $bdd->prepare('SELECT * FROM user WHERE user_id = ?');
                                    $verif_username->execute(array($message['user_env']));
                                    $user = $verif_username->fetch(); 
                                    $decrypted_chaine = openssl_decrypt($message['message'], "AES-128-ECB", $key);?>
                                    <p></p>
                                    <p><?php echo $user['username']; ?> : &nbsp; <?php echo $decrypted_chaine; ?></p>
                                <?php
                                }

                            } else { ?>
                                <p>Start chat with <strong><?php echo $user['username'];?></strong></p> <?php
                            } ?>
                        </div>

                        <?php  
                        $verif_username_block = $bdd->prepare('SELECT * FROM users_blocked WHERE user_blocked = ? AND user_asking_block = ?');
                        $verif_username_block->execute(array($_SESSION['id'], $_GET['user_id']));
                        $user_is_block = $verif_username_block->rowCount();
                        $verif_username_block = $bdd->prepare('SELECT * FROM users_blocked WHERE user_blocked = ? AND user_asking_block = ?');
                        $verif_username_block->execute(array($_GET['user_id'], $_SESSION['id']));
                        $user_block = $verif_username_block->rowCount();

                        if($user_is_block == 0 AND $user_block === 0) { ?>
                            <div>
                                <form method="post" style="position:absolute;">
                                    <textarea name="message" cols="195" rows="2" placeholder="Enter your message"></textarea>
                                    <input type="submit" name="submit">
                                </form>
                            </div> <?php

                        } else { ?>
                            <form method="post" style="position:absolute; ">
                                <p>You blocked this person or this person blocked you !</p>
                            </form> 
                            <?php
                        } ?>
                    </div>
                </body>
                <?php
                } else {
                    echo "You are not allowed to send message to yourself !";
                }
                
            } else {
                echo "This person does not exist !";
            }

        } else {
            echo "User was not found !";
        }

    } else {
        header('Location: index.php');
    }
    ?>
