<?php 
	require_once $_SERVER['DOCUMENT_ROOT']."/tutorial/core/init.php";
	include "includes/head.php";
	include "includes/navigation.php";
	include "includes/headerpartial.php";


	if($cart_id != ""){
		$cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
		$cart = mysqli_fetch_assoc($cartQ);
		$items = json_decode($cart['items'],true);
		$i = 1;
		$sub_total = 0;
		$item_count = 0;
	}
 ?>

<div class="col-md-12">
	<div class="row">
		<h2 class="text-center">My Shopping Cart</h2><hr>
		<?php if($cart_id == ""): ?>
			<div class="bg-danger">
				<p class="text-center text-danger">
					Your shopping cart is empty.
				</p>
			</div>
		<?php else: ?>
			<table class="table table-bordered table-condensed table-striped">
				<thead>
					<th>#</th>
					<th>Item</th>
					<th>Price</th>
					<th>Quantity</th>
					<th>Size</th>
					<th>Sub Total</th>
				</thead>
				<tbody>
					<?php 
						foreach($items as $item){
							$product_id = $item['id'];
							$productQ = $db->query("SELECT * FROM product WHERE id = '{$product_id}'");
							$product = mysqli_fetch_assoc($productQ);
							$sArray = explode(",", $product['sizes']);
							foreach($sArray as $sizeString){
								$s = explode(":", $sizeString);
								if($s[0] == $item['size']){
									$available = $s[1];
								}
							} ?>

						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $product['title']; ?></td>
							<td><?php echo $product['price']; ?></td>
							<td>
								<button class="btn btn-xs btn-default" onclick="update_cart('removeone','<?php echo $product['id']; ?>','<?php echo $item['size']; ?>')">-</button>
								
								<?php echo $item['quantity']; ?>	
								
								<?php if($item['quantity'] < $available): ?>
									<button class="btn btn-xs btn-default" onclick="update_cart('addone','<?php echo $product['id']; ?>','<?php echo $item['size']; ?>')">+</button>
								<?php else: ?>
									<span class="text-danger">Max</span>	
								<?php endif; ?>
							</td>
							<td><?php echo $item['size']; ?></td>
							<td><?php echo money($product['price'] * $item['quantity']); ?></td>
						</tr>
					<?php 
						$i++;
						$item_count += $item['quantity'];
						$sub_total += ($product['price'] * $item['quantity']);
					 } 
					 $tax = TAXRATE * $sub_total;
					 $grand_total =  $tax + $sub_total;
					 ?>
				</tbody>
			</table>
			<table class="table table-bordered table-condensed table-striped">
				<legend>Totals</legend>
				<thead class="totals-table-header">
					<th>Item Count</th>
					<th>Sub Total</th>
					<th>Tax</th>
					<th>Grand Total</th>
				</thead>
				<tbody>
					<td><?php echo $item_count ;?></td>
					<td><?php echo money($sub_total);?></td>
					<td><?php echo money($tax);?></td>
					<td class="bg-success"><?php echo money($grand_total);?></td>
				</tbody>
			</table>
<!-- Check Out Button -->
			<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#checkoutModal">
			 <span class="glyphicon glyphicon-shopping-cart"></span> Check Out >>
			</button>

<!-- Modal -->
			<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
			  <div class="modal-dialog modal-lg" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="checkoutModalLabel">Shipping Address</h4>
			      </div>
			      <div class="modal-body">
			      	<div class="row">
				      	<form action="thankYou.php" method="post" id="payment-form">
				      		<span class="bg-danger" id="payment-errors"></span>
				      		<input type="hidden" name="tax" value="<?php echo $tax ?>">
				      		<input type="hidden" name="sub_total" value="<?php echo $sub_total ?>">
				      		<input type="hidden" name="grand_total" value="<?php echo $grand_total ?>">
				      		<input type="hidden" name="cart_id" value="<?php echo $cart_id ?>">
				      		<input type="hidden" name="description" value="<?php echo $item_count.' item'.(($item_count>1)?'s':'').'from Shauntas Boutique' ?>">
				      		<div id="step1">
				      			<div class="form-group col-md-6">
				      				<label for="full_name">Full Name</label>
				      				<input type="text" name="full_name" id="full_name" class="form-control">
				      			</div>
				      			<div class="form-group col-md-6">
				      				<label for="email">Email</label>
				      				<input type="email" name="email" id="email" class="form-control">
				      			</div>
				      			<div class="form-group col-md-6">
				      				<label for="street">Street Address</label>
				      				<input type="text" name="street" id="street" class="form-control" data-stripe="address_line1">
				      			</div>
				      			<div class="form-group col-md-6">
				      				<label for="street2">Street Address 2</label>
				      				<input type="text" name="street2" id="street2" class="form-control" data-stripe="address_line2">
				      			</div>
				      			<div class="form-group col-md-6">
				      				<label for="city">City</label>
				      				<input type="text" name="city" id="city" class="form-control" data-stripe="address_city">
				      			</div>
				      			<div class="form-group col-md-6">
				      				<label for="state">State</label>
				      				<input type="text" name="state" id="state" class="form-control" data-stripe="address_state">
				      			</div>
				      			<div class="form-group col-md-6">
				      				<label for="zip_code">Zip Code</label>
				      				<input type="text" name="zip_code" id="zip_code" class="form-control" data-stripe="address_zip">
				      			</div>
				      			<div class="form-group col-md-6">
				      				<label for="country">Country</label>
				      				<input type="text" name="country" id="country" class="form-control" data-stripe="address_country">
				      			</div>

				      		</div>
				      		<div id="step2">
				      			<!-- 諸如像信用卡這些重要資料,請不要在input上設置name屬性
				      				以避免不必要的資安問題
				      				因為如果設置name的話,等同將重要資料傳入server -->
				      			<div class="form-group col-md-3">				
				      				<label for="name">Name on Card</label>
				      				<input type="text" id="name" class="form-control" data-stripe="name">
				      			</div>
				      			<div class="form-group col-md-3">				
				      				<label for="number">Card Number</label>
				      				<input type="text" id="number" class="form-control" data-stripe="number">
				      			</div>
				      			<div class="form-group col-md-2">				
				      				<label for="cvc">CVC</label>
				      				<input type="text" id="cvc" class="form-control" data-stripe="cvc">
				      			</div>
				      			<div class="form-group col-md-2">				
				      				<label for="exp-month">Expire Month</label>
				      				<select id="exp-month" class="form-control" data-stripe="exp-month">
				      					<option value=""></option>
				      					<?php for($i=1;$i<13;$i++): ?>
				      						<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				      					<?php endfor; ?>
				      				</select>
				      			</div>
				      			<div class="form-group col-md-2">				
				      				<label for="exp-year">Expire Year</label>
				      				<select id="exp-year" class="form-control" data-stripe="exp-year">
				      					<option value=""></option>
				      					<?php $yr = date("Y"); ?>
				      					<?php for($i=0;$i<11;$i++): ?>
				      						<option value="<?php echo $yr+$i; ?>"><?php echo $yr+$i; ?></option>
				      					<?php endfor; ?>
				      				</select>
				      			</div>
				      		</div>
				      	
			      	</div>
			      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				        <button type="button" id="back_button" class="btn btn-primary" onclick="backAddress();"><< Back </button>
				        <button type="button" id="next_button" class="btn btn-primary" onclick="checkAddress();">Next >></button>
				        <button type="submit" id="chk_button" class="btn btn-primary">Check Out >></button>
				      </div>
			      </form> 
			    </div>
			  </div>
			</div>
		<?php endif; ?>
	</div>
</div>
<script>
	function backAddress(){
		jQuery("#payment-errors").html("");
		jQuery("#step1").css("display","block");
		jQuery("#step2").css("display","none");
		jQuery("#back_button").css("display","none");
		jQuery("#next_button").css("display","inline-block");
		jQuery("#chk_button").css("display","none");
		jQuery("#checkoutModalLabel").html("Shipping Address");
	}

	function checkAddress(){
		var data = {
			"full_name" : jQuery("#full_name").val(),
			"email" : jQuery("#email").val(),
			"street" : jQuery("#street").val(),
			"street2" : jQuery("#street2").val(),
			"city" : jQuery("#city").val(),
			"state" : jQuery("#state").val(),
			"zip_code" : jQuery("#zip_code").val(),
			"country" : jQuery("#country").val(),
		}
		jQuery.ajax({
			url:"/tutorial/admin/parsers/check_address.php",
			method:"POST",
			data:data,
			success:checkData,
			error:function(){alert("Something went wrong with /tutorial/admin/parsers/check_address.php");},
		});
	}

	function checkData(mData){
		var passed = "passed";
		if(mData.trim() == "passed"){
			jQuery("#payment-errors").html("");
			jQuery("#step1").css("display","none");
			jQuery("#step2").css("display","block");
			jQuery("#back_button").css("display","inline-block");
			jQuery("#next_button").css("display","none");
			jQuery("#chk_button").css("display","inline-block");
			jQuery("#checkoutModalLabel").html("Enter Your Card Details");

		}
		if(mData.trim() != passed){
			jQuery("#payment-errors").html(mData);
		}
	}

	Stripe.setPublishableKey('<?php echo STRIPE_PUBLIC; ?>');

    function stripeResponseHandler(status, response) {
        var $form = $('#payment-form');
        
        if (response.error) {
            // Show the errors on the form
            $form.find('#payment-errors').text(response.error.message);
            $form.find('button').prop('disabled', false);
        } else {
            // Response contains id and card, which contains additional card details
            var token = response.id;
            // Insert the token into the so it gets submitted to the server
            $form.append($('<input type="hidden" name="stripeToken" />').val(token));
            // And submit
            $form.get(0).submit();
        }
    };

	jQuery(function($){
		$("#payment-form").submit(function(event){
			var $form = $(this);
			//disable the submit button to prevent repeated clicks
			$form.find('button').prop('disabled',true);

			Stripe.card.createToken($form,stripeResponseHandler);

			//prevent the form from submitting with the default aciton
			return false;
		});
	});
</script>
 <?php 
 	include "includes/footer.php";
  ?>