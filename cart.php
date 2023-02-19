<?php

session_start();
include('db.php');
error_reporting(E_ERROR | E_PARSE);

if ($_SESSION['id']) {    
    if (isset($_POST['id_delete'])) {
        $delete_produc_cart = $bdd->prepare('DELETE FROM cart WHERE id_from_item = ?');
        $delete_produc_cart->execute(array($_POST['id_delete']));
        header('Location: cart.php');
    }

    $select_nombre_item = $bdd->prepare('SELECT id_from_user FROM cart WHERE id_from_user = ?');
	$select_nombre_item->execute(array($_SESSION['id']));
	$nombre_item_user = $select_nombre_item->rowCount();    
    
?>
    <link rel="stylesheet" href="css/cart.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Cart - Silkroad</title>
    <div>
        <ul class="topnav">
            <a href="index.php">
                <img src="img/silkroad.png" class="logo_silkroad">
                <h3 class="marque">Silkroad</h3>
            </a> 
                
            <?php
            if ($_SESSION['id']) { ?>
                <li class="Tosell"><a href="sell.php">+ Add item</a></li>
                <li class="Tosell2"><a href="edit.php">My items</a></li>
                <li><a href="chat_list.php">Chat</a></li>
                <li><a href="account.php">Account</a></li>

            <?php
            }

            if ($_SESSION['id']) {
                $select_username = $bdd->prepare('SELECT * FROM user WHERE user_id = ?');
                $select_username->execute(array($_SESSION['id']));
                $user = $select_username->fetch(); ?>
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
        $select_cart = $bdd->prepare('SELECT * FROM cart WHERE id_from_user = ?');
        $select_cart->execute(array($_SESSION['id']));
        $cart_exist = $select_cart->rowCount();
        
        if ($cart_exist === 1) {
            $total = 0.0;
            

            while ($cart = $select_cart->fetch()) {
                $select_item = $bdd->prepare('SELECT * FROM article WHERE item_id = ?');
                $select_item->execute(array($cart['id_from_item']));
                $item = $select_item->fetch();
                $select_stock = $bdd->prepare('SELECT * FROM stock WHERE id_from_item_stock = ?');
                $select_stock->execute(array($cart['id_from_item']));
                $stock = $select_stock->fetch();
                $total += $item['price'] * floatval($cart['number_in_cart']);
                

                if(isset($_POST['change_quantity'])) {
                    if($_POST['change_quantity_nb'] > $stock["number_in_stock"]) {
                        $_POST['change_quantity_nb'] = $stock['number_in_stock'];
                        echo "You don't have enough items in stock";
                    }
                    
                    $insert_item_cart = $bdd->prepare("UPDATE cart SET number_in_cart = ? WHERE id_from_user = ?");
                    $insert_item_cart->execute(array($_POST['change_quantity_nb'], $_SESSION['id']));
                    header('Location: cart.php');
                } 

                if ($total > $user['wallet']) {
                    echo "You don't have enough money";    
                }
                ?>

                <div>
                    <form method="GET">
                        <a style="text-decoration:none; color:black;" href="index.php?item_id=<?php echo $item['item_id']; ?>">
                            <img style="width:20%; height:20%; position:relative;" src="img/<?php echo $item['article_pic_link']; ?><?php echo $item['extension_image']; ?>" alt="">
                            <p><?php echo $item['name']; ?> &nbsp; <?php echo "Price : "; echo $item['price']; ?>€ <?php echo "Quantity : "; echo $cart['number_in_cart']; ?></p>
                        </a>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="id_delete" value="<?= $item["item_id"] ?>" />
                        <input type="submit" value="Delete" name="delete">    
                        <input style="width:50px" type="number" min="1" value="1" name="change_quantity_nb">
                        <input type="submit" value="change_quantity" name="change_quantity">
                    </form>
                </div>
            <?php
            }
            
            echo "Total : " . $total . "€";   
            ?>
    </div> 
    
    <div>
        <form method="POST">    
            <input type="text" placeholder="address" name="address" required><br>
            <input type="text" placeholder="city" name="city" required><br>
            <input type="text" placeholder="zipcode" name="zipcode" required><br>
            <input type="submit" value="buy" name="buy">
        </form>
    </div>
    <?php

    if (isset($_POST['buy'])) {
        $select_cart = $bdd->prepare('SELECT * FROM cart WHERE id_from_user = ?');
        $select_cart->execute(array($_SESSION['id']));
        $cart_exist = $select_cart->rowCount();
      
        if ($cart_exist === 1) {
            if ($total > $user['wallet']) {
                header('Location: cart.php');
                
            } else {
                while ($cart = $select_cart->fetch()) {
                    $date = date("j-n-Y"); 
                    $select_stock = $bdd->prepare('SELECT * FROM stock WHERE id_from_item_stock = ?');
                    $select_stock->execute(array($cart['id_from_item']));
                    $stock = $select_stock->fetch();
                    $select_item = $bdd->prepare('SELECT * FROM article WHERE item_id = ?');
                    $select_item->execute(array($cart['id_from_item']));
                    $item = $select_item->fetch();
                    $delete_produc_cart = $bdd->prepare('DELETE FROM cart WHERE id_from_item = ?');
                    $delete_produc_cart->execute(array($cart['id_from_item']));
                    $insert_into_invoice = $bdd->prepare('INSERT INTO invoice(id_from_user, id_from_item, number_in_cart, price, date, address, city, zipcode) VALUES(?, ?, ?, ?,?,?,?,?)');
                    $insert_into_invoice->execute(array($_SESSION['id'], $cart['id_from_item'], $cart['number_in_cart'], $item['price'], $date, $_POST['address'], $_POST['city'], $_POST['zipcode']));
                    $new_stock = $stock['number_in_stock'] - $cart['number_in_cart'];
                    $update_stock = $bdd->prepare('UPDATE stock SET number_in_stock = ? WHERE id_from_item_stock = ?');
                    $update_stock->execute(array($new_stock, $cart['id_from_item']));
                    $update_money = $bdd->prepare('UPDATE user SET wallet = ? WHERE user_id = ?');
                    $update_money->execute(array($user['wallet'] - $total, $_SESSION['id']));
                }
                
            }
            header('Location: cart.php');
        }   
        
        
    }
    ?>

    <?php
    } else {
        echo "You have nothing in the cart for now";
    } 
    ?>
<?php
}
