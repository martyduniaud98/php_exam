<?php

session_start();
include('db.php');
error_reporting(E_ERROR | E_PARSE);

if(isset($_GET['approuve'])){
    $check_id = $bdd->prepare('SELECT * FROM article WHERE item_id = ?');
    $check_id->execute(array($_GET['approuve']));
    $user = $check_id->fetch();
    $id_exist = $check_id->rowCount();

    if($id_exist === 1) {
        $update_id = $bdd->prepare('UPDATE article SET approuve = 1 WHERE item_id = ?');
        $update_id->execute(array($_GET['approuve']));
        header("Location: list_to_approve.php");

    } else {
        echo"non-existent item";
    }
}

if(isset($_GET['decline'])){
    $check_id = $bdd->prepare('SELECT * FROM article WHERE item_id = ?');
    $check_id->execute(array($_GET['decline']));
    $item = $check_id->fetch();
    $id_exist = $check_id->rowCount();

    if($id_exist === 1) {
        $image_name = $item['id_image'];
        $extension_image = $item['extension_image'];
        $file = "img/{$image_name}{$extension_image}";
        unlink($file);
        $delete_item = $bdd->prepare('DELETE FROM article WHERE item_id = ?');
        $delete_item->execute(array($_GET['decline']));
        header("Location: list_to_approve.php");

    } else {
        echo"The product does not exist";
    }
}?>
<link rel="stylesheet" href="css/list_to_approve.css">
<title>List to approve</title>
<div>
    <p><a href="admin.php">Back ADMIN</a></p>
    <?php
    $select_items = $bdd->query('SELECT * FROM article WHERE approuve = 0');
    $nb_ask = $select_items->rowCount();

    if($nb_ask >= 1) {
        while($item = $select_items->fetch()) { ?>
            <div>
                <form method="GET">
                    <a style="text-decoration:none; color:black;" href="index.php?item_id=<?php echo $item['item_id']; ?>">
                    <img style="margin-top: 5%; width: 20%; postion: absolute; margin-left: 10%;" src="img/<?php echo $item['article_pic_link']; ?><?php echo $item['extension_image']; ?>" alt="">
                        <p><?php echo $item['name']; ?> &nbsp; <?php echo $item['price']; ?>€</p>
                    </a>
                    <a href="list_to_approve.php?approuve=<?php echo $item['item_id']; ?>">Approve</a>
                    <a href="list_to_approve.php?decline=<?php echo $item['item_id']; ?>">Decline</a>
                </form>
            </div>
            <br><br>
        <?php
        }
        
    } else {
        echo"No item to approve";
    }
    ?>
</div>