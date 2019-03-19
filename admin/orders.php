<?php 
	require_once "../core/init.php";

	//判斷有無登入
	if(!isLoggedIn()){
		header("Location:login.php");
	}
	include "includes/head.php";
	include "includes/navigation.php";	

	//complete order
	if(isset($_GET['complete']) && $_GET['complete'] == 1){
		$cart_id = sanitize((int)$_GET['cart_id']);
		$db->query("UPDATE cart SET shipped = 1 WHERE id ='{$cart_id}'");
		$_SESSION['success_flash'] = "The order has been completed.";
		header("Location:index.php");
	}

	$txn_id = sanitize((int)$_GET['txn_id']); 
	//根據$txn_id來抓取transaction及cart table的資料
	$txnQ = $db->query("SELECT * FROM transaction WHERE id = '{$txn_id}'") ;
	$txn = mysqli_fetch_assoc($txnQ);
	$cartQ = $db->query("SELECT * FROM cart WHERE id = '{$txn['cart_id']}'") ;
	$cart = mysqli_fetch_assoc($cartQ);
	$items = json_decode($cart['items'],true);
	$idArray = array();
	$products = array();
	
	foreach($items as $item){
		$idArray[] = $item['id'];
	}
	//將$idArray轉成一條string用於Mysql語法
	$ids = implode(",",$idArray);
	$productQ = $db->query("SELECT i.id AS 'id',i.title AS 'title',c.id AS 'cid',c.category AS 'child',p.category AS 'parent' FROM product i LEFT JOIN categories c ON i.categories = c.id LEFT JOIN categories p ON c.parent = p.id WHERE i.id IN ({$ids})");

	while($p = mysqli_fetch_assoc($productQ)){
		foreach($items as $item){
			if($p['id'] == $item['id']){
				$x = $item;
				continue;
			}
		}
		$products[] = array_merge($x,$p); 
	}
 ?>

 <h2 class="text-center">Items Orders</h2>
 <table class="table table-striped table-bordered table-condensed">
 	<thead>
 		<th>Quantity</th>
 		<th>Title</th>
 		<th>Category</th>
 		<th>SIze</th>
 	</thead>
 	<tbody>
 		<?php foreach($products as $product): ?>
	 		<tr>
	 			<td><?php echo $product['quantity'] ?></td>
	 			<td><?php echo $product['title'] ?></td>
	 			<td><?php echo $product['parent']."~".$product['child'] ?></td>
	 			<td><?php echo $product['size'] ?></td>
	 		</tr>
	 	<?php endforeach; ?>
 	</tbody>
 </table>

 <div class="row">
 	<!-- Order Details -->
 	<div class="col-md-6">
 		<h3 class="text-center">Order Details</h3>
 		<table class="table table-bordered table-condensed table-striped">
 			<tbody>
 				<tr>
 					<td>Sub_total</td>
 					<td><?php echo $txn['sub_total'] ?></td>
 				</tr>
 				<tr>
 					<td>Tax</td>
 					<td><?php echo $txn['tax'] ?></td>
 				</tr>
 				<tr>
 					<td>Grand_total</td>
 					<td><?php echo $txn['grand_total'] ?></td>
 				</tr>
 				<tr>
 					<td>Order Date</td>
 					<td><?php echo $txn['txn_date'] ?></td>
 				</tr>
 			</tbody>
 		</table>
 	</div>
 	<!-- Shipping Address -->
 	<div class="col-md-6">
 		<h3 class="text-center">Shipping Address</h3>
 		<address>
 			<?php echo $txn['full_name'] ?><br>
 			<?php echo $txn['street'] ?><br>
 			<?php echo ($txn['street2'] != "")?$txn['street2']."<br>":"" ?>
 			<?php echo $txn['city']." ".$txn['state']." ".$txn['zip_code'] ?><br>
 			<?php echo $txn['country'] ?><br>
 		</address>
 	</div>
 </div>
 <div class="pull-right">
 	<a href="index.php" class="btn btn-default btn-lg">Cancel</a>
 	<a href="orders.php?complete=1&cart_id=<?php echo $txn['cart_id'] ?>" class="btn btn-primary btn-lg">Complete Order</a>
 </div>


 <?php 
 	include "includes/footer.php";
  ?>