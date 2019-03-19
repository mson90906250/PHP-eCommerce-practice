<?php 
	require_once "core/init.php";
	require_once 'vendor/stripe/stripe-php/init.php'; 

	if($_COOKIE[CART_COOKIE] == ''){
		header("Location: index.php");
	}

	//set your secret key:remember to change this to your live secret key in production
	//see your keys here https://dashboard.stripe.com/account/apikeys
	\Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

	//get the credit card details submitted by the form
	$token = $_POST['stripeToken'];
	//get the rest of the post data
	$full_name = sanitize($_POST['full_name']);
	$email = sanitize($_POST['email']);
	$street = sanitize($_POST['street']);
	$street2 = sanitize($_POST['street2']);
	$city = sanitize($_POST['city']);
	$state = sanitize($_POST['state']);
	$zip_code = sanitize($_POST['zip_code']);
	$country = sanitize($_POST['country']);
	$tax = sanitize($_POST['tax']);
	$sub_total = sanitize($_POST['sub_total']);
	$grand_total = sanitize($_POST['grand_total']);
	$cart_id = sanitize($_POST['cart_id']);
	$description = sanitize($_POST['description']);

	//stripe 使用cent來做單位所以要再*100

	$charge_amount = round($grand_total,2)*100;
	//到達千位數以上的數字時,ex: number_format(1001.45,2);此函式會將值變成"1,001.45"字串而不再是float
	//這樣的情況下php會將其看作1而不是1001.45,所以輸出才會變成1.00而不是1001.45,
	//所以用round(1001.45,2)作為代替會比較好;

	$metadata = array(
		"cart_id" => $cart_id,
		"tax" => $tax,
		"sub_total" => $sub_total,
	);

	//create the charge on Stripe's servers - this will charge the user's card
	try{
		$charge = \Stripe\Charge::create(array(
			"amount" => $charge_amount,//amount in cents,again
			"currency" => CURRENCY,
			"source"  => $token,
			"description" => $description,
			"receipt_email" => $email,
			"metadata" => $metadata,
		));

		//adjust inventory 完成結賬後更新庫存
		$itemQ = $db->query("SELECT * FROM cart WHERE id ='{$cart_id}'");
		$iresult = mysqli_fetch_assoc($itemQ);
		$items = json_decode($iresult['items'],true);
		foreach($items as $item){
			$newSizes = array();
			$item_id = $item['id'];
			$productQ = $db->query("SELECT * FROM product WHERE id = '{$item_id}'");
			$product = mysqli_fetch_assoc($productQ);
			$sizes = sizesToArray($product['sizes']);
			foreach($sizes as $size){
				if($size['size'] == $item['size']){
					//若兩邊size吻合即更新數量 
					$q = $size['quantity'] - $item['quantity'];
					$newSizes[] = array("size" => $size['size'],"quantity" => $q,"threshold" => $size['threshold']);
				}else{
					//即使不吻合仍要更新
					$newSizes[] = array("size" => $size['size'],"quantity" => $size['quantity'],"threshold" => $size['threshold']);
				}
			}
			//更新database前將$newSizes轉成string
			$sizesString = sizesToString($newSizes);
			$db->query("UPDATE product SET sizes = '{$sizesString}' WHERE id = '{$item_id}'");
		} 

		//update cart
		$db->query("UPDATE cart SET paid = 1 WHERE id = '{$cart_id}'");
		$db->query("INSERT INTO transaction 
			(charge_id,cart_id,full_name,email,street,street2,city,state,zip_code,country,sub_total,tax,grand_total,description,txn_type) VALUES
			('$charge->id','$cart_id','$full_name','$email','$street','$street2','$city','$state','$zip_code','$country','$sub_total','$tax','$grand_total','$description','$charge->object')");

		//成功之後清掉cookie
		$domain = ($_SERVER['HTTP_HOST'] != "localhost")?'.'.$_SERVER['HTTP_HOST']:false;
		setcookie(CART_COOKIE,'',1,'/',$domain,false);

		//顯示結賬成功的訊息
		include "includes/head.php";
		include "includes/navigation.php";
		include "includes/headerpartial.php";
		?>
		
			<h1 class="text-center">Thank You!</h1>
			<p>Your card has been successfully charged <?php echo money($grand_total); ?> . You have been emailed a receipt.Please check your spam folder if it is not in your inbox. Additionally, you can print this page as a receipt. </p>
			<p>Your receipt number is <strong><?php echo $cart_id ?> .</strong></p>
			<p>Your order will be shipped to the address below.</p>
			<address>
				<?php echo $full_name; ?><br>
				<?php echo $street ;?><br>
				<!-- 並不是所有人都有$street2 -->
				<?php echo (($street2 != "")?$street2."<br>":"")?>
				<?php echo $city.", ".$state." ".$zip_code; ?><br>
				<?php echo $country ?><br>
			</address>
		<?php
		include "includes/footer.php";
	}catch(\Stripe\Error\Card $e){
		//the card has been declined
		echo $e;
	}

 ?>