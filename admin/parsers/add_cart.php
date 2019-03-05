<?php 
	require_once $_SERVER['DOCUMENT_ROOT']."/tutorial/core/init.php";
	$product_id = sanitize($_POST['product_id']);
	$size = sanitize($_POST['size']);
	$quantity = sanitize($_POST['quantity']);
	$available = sanitize($_POST['available']);
	$item = array();
	$item[] = array(
		'id' => $product_id,
		'size' => $size,
		'quantity' => $quantity
	);
	$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
	$query = $db -> query("SELECT * FROM product WHERE id = '{$product_id}'");
	$product = mysqli_fetch_assoc($query);
	$_SESSION['success_flash'] = $product['title']." was added to your cart.";
	//check to see if the cart exists
	if($cart_id != ""){
		$_SESSION['success_flash'] = "asdf= ".$cart_id;
		$cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
		$cart = mysqli_fetch_assoc($cartQ);
		$previous_item = json_decode($cart['items'],true);
		$item_match = 0;
		$new_item = array();
		//比對是否為同一商品且size相同
		foreach($previous_item as $pitem){
			if($item[0]['id'] == $pitem['id'] && $item[0]['size'] == $pitem['size']){
				$pitem['quantity'] += $item[0]['quantity'];
				if($pitem['quantity'] > $available){
					$pitem['quantity'] = $available;
				} 
				$item_match = 1;
			}
			$new_item[] = $pitem;
		}

		//欲加入的商品若與之前的不同時
		if($item_match != 1){
			$new_item = array_merge($item,$previous_item);
		}
		$items_json = json_encode($new_item);
		$cart_expire = date("Y-m-d H:i:s",strtotime("+30days"));
		$db->query("UPDATE cart SET items = '{$items_json}',expire_date = '{$cart_expire}' WHERE id = '{$cart_id}'");
		//重設cookie
		//先將之前的清掉,再設一個新的

		setcookie(CART_COOKIE,'',1,'/',$domain,false);
		setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
		
		

	}else{
		//add the cart to database and set cookie
		$items_json = json_encode($item);
		$cart_expire = date("Y-m-d H:i:s",strtotime("+30days"));
		$db->query("INSERT INTO cart (items,expire_date) VALUES ('{$items_json}','{$cart_expire}')" );
		//$mysqli->insert_id : Returns the auto generated id used in the latest query
		$cart_id = $db->insert_id;
		$_SESSION['success_flash'] = $cart_id;
		setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
		
	}

 ?>