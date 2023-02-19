<?php

session_start();
include('db.php');
error_reporting(E_ERROR | E_PARSE);

$verif_username = $bdd->prepare('SELECT * FROM user WHERE user_id = ?');
$verif_username->execute(array($_SESSION['id']));
$user = $verif_username->fetch();
$select_stock = $bdd->prepare('SELECT * FROM stock WHERE id_from_item_stock = ?');
$select_stock->execute(array($_GET['item_id']));
$stock = $select_stock->fetch();
		
if (isset($_GET['deco'])) {
	session_destroy();
	header('Location: index.php');
}

if (isset($_POST['my_items'])) {
	header('Location: edit.php');
}

$select_nb_item = $bdd->prepare('SELECT id_from_user FROM cart WHERE id_from_user = ?');
$select_nb_item->execute(array($_SESSION['id']));
$nb_item_user = $select_nb_item->rowCount();


if (empty($_GET['item_id'])) { ?>
	<link rel="stylesheet" href="css/index.css" type="text/css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<title>Home - Silkroad</title>
	<div>
		<ul class="topnav">
			<a href="index.php">
				<img src="img/silkroad.png" class="logo_silkroad">
				<h3 class="marque">Silkroad</h3>
            </a><?php
            
			if(isset($_SESSION['id'])) { ?>
                <li class="Tosell"><a href="sell.php">+ Add item </a></li>
                <li class="Tosell2"><a href="edit.php">My items</a></li>
                <li><a href="cart.php">Cart - <?php echo $nb_item_user; ?></a></li>
                <li><a href="chat_list.php">Chat</a></li>
                <li><a href="account.php">Account</a></li>
				<?php 
				if($user['role'] == 1) { ?>
					<li><a href="admin.php">ADMIN</a></li>
				<?php 
				}
				?>
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

	<?php
	$select_ids = $bdd->query('SELECT * FROM article WHERE item_id > 0 ORDER BY item_id DESC');

	while ($item = $select_ids->fetch()) { ?>
		<div class="produits">
				<a class="bloc" style="text-decoration:none; color:black;" href="index.php?item_id=<?php echo $item['item_id']; ?>">
					<img style="margin-top: 5%; width: 80%; height: 80%; postion: absolute; margin-left: 10%;" src="img/<?php echo $item['article_pic_link']; ?><?php echo $item['extension_image']; ?>" alt="">
					<p class="p"><?php echo $item['name']; ?> &nbsp; <?php echo $item['price']; ?>€</p>
				</a>
			</form>
		</div>
	<?php
	}

} else {
	$select_id = $bdd->prepare('SELECT * FROM article WHERE item_id = ?');
	$select_id->execute(array($_GET['item_id']));
	$item = $select_id->fetch();
	$is_exist = $select_id->rowCount();

	if (isset($_SESSION['id'])) {
		$select_nb_item = $bdd->prepare('SELECT id_from_user FROM cart WHERE id_from_user = ?');
		$select_nb_item->execute(array($_SESSION['id']));
		$nb_item_user = $select_nb_item->rowCount();

		if (isset($_POST['cart'])) {
			header('Location: cart.php');
		}
	}

	if (isset($_POST['add_to_cart'])) {
		if($stock['number_in_stock'] === 0) {
			echo "No more item in stock.";
		}
		else {
			if ($_SESSION['id']) {
				$select_id_cart = $bdd->prepare('SELECT * FROM cart WHERE id_from_item = ? AND id_from_user = ?');
				$select_id_cart->execute(array($_GET['item_id'], $_SESSION['id']));
				$items = $select_id_cart->fetch();
				$is_exist_cart = $select_id_cart->rowCount();

				if ($is_exist_cart === 0) {
					if ($item['approuve']) {	
						$insert_item_cart = $bdd->prepare("INSERT INTO cart (id_from_user, name, id_from_item, number_in_cart) VALUES (?,?,?,?)");
						$insert_item_cart->execute(array($_SESSION['id'], $item['name'], $item['item_id'], $_POST['quantity_to_command']));
						header("Location: index.php?item_id=" . $item['item_id']);
			
					} else {
					echo "This item has not be approved by an admin yet.";
					}

				} else {
					echo "Already add to cart.";
				}
			} else {
				echo "Not connected. Please connect";
			}
		}
	}

	if (isset($_POST['cart'])) {
		if ($_SESSION['id']) {
			header('Location: cart.php');
		
		} else {
			echo "Not connected. Please connect";
		}
	}

	if ($is_exist === 1) { ?>
			<link rel="stylesheet" href="css/index.css" type="text/css">
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
					} else { ?>
						<li><a href="login.php">Log in</a></li>
						<li><a href="register.php">Register</a></li>
					<?php
					} ?>
				</ul>
			</div>

			<?php 
			// $select_id = $bdd->prepare('SELECT * FROM article WHERE author_id = ?');
            // $select_id->execute(array($user['username']));
            // $author = $select_id->fetch();
			
			$select_id = $bdd->prepare('SELECT * FROM article WHERE item_id = ?');
			$select_id->execute(array($_GET['item_id']));
			$item = $select_id->fetch();?>
			

			<div>
				<form method="GET">
					<a style="text-decoration:none; color:black;" href="index.php?item_id=<?php echo $item['item_id']; ?>">
						<img style="width:20%; height:20%; postion:relative;" src="img/<?php echo $item['article_pic_link']; ?><?php echo $item['extension_image']; ?>" alt="">
						<p style="font-size:25px"><?php echo "Name : ",$item['name']; ?></p> 
						<p style="font-size:20px"><?php echo "Price : ",$item['price']; ?> €</p>
						<p style="font-size:18px"><?php echo "description : ",$item['description']; ?></p>
						<p style="font-size:18px"><?php echo "Stock left : ",$stock['number_in_stock']; ?></p>
					</a>
				</form>
				<form method="POST">
					<input type="submit" value="Add to cart" name="add_to_cart">
					<input style="width:50px" type="number" min="1" value="1" name="quantity_to_command">
				</form>
				<div>
					<a href="chat_with_owner.php?user_id=<?php echo $item['author_id']; ?>">Discuss with the owner</a>
					<br>
					<a style="text-align: center;" href="account.php?user_id=<?php echo $item['author_id']; ?>">Owner profile</a>

				</div>
			</div> 
			<?php
	} else {
		echo "The product does not exist";
	}
} ?>