	<?php
		require_once "../core/init.php";
		$id = $_POST['id'];
		//確保$id為int值
		$id = (int)$id;
		$sql = "SELECT * FROM product WHERE id = '$id'";
		$result = $db->query($sql);
		$product = $result->fetch_assoc();
		
		$brand_id = $product['brand'];
		$sql = "SELECT brand FROM brand WHERE id = '$brand_id'";
		$brand_result = $db->query($sql);
		$brand = $brand_result->fetch_assoc();

		$sizestring = $product['sizes'];
		//explode('用於區隔的符號',string);跟split()很像
		$size_array = explode(',', $sizestring);

	?>

	<!-- Details Modal -->

	<!-- 利用php來回傳以下程式碼 -->
	<!--  ob_start()打開輸出緩衝區 
		  函數格式：void ob_start(void)
			說明：當緩衝區激活時，所有來自PHP程序的非文件頭信息(header())均不會發送，而是保存在內部緩衝區。
			為了輸出緩衝區的內容，可以使用ob_end_flush()或flush()輸出緩衝區的內容。-->
	
	<?php  ob_start()?>
	<div class="modal fade details-1" id="details-modal" role="dialog" tabindex="-1" aria-labelledby="details-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" onclick="closeModal()" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title text-center"><?= $product['title']; ?></h4>
				</div>
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							<span id="modal_errors" class="bg-danger"></span>
							<div class="col-sm-6">
								<div class="center-block">
									<img src="<?= $product['image'] ?>" alt="<?= $product['title'] ?>" class="details img-responsive">
								</div>
							</div>
							<div class="col-sm-6">
								<h4>Details</h4>
								<p><?= $product['description']; ?></p>
								<hr>
								<p>Price: $<?= $product['price'] ?></p>
								<p>Brand: <?= $brand['brand']; ?></p>
								<form action="add_cart.php" method="post" id="add_product_form">
									<input type="hidden" name="product_id" value="<?= $id ?>">
									<input type="hidden" name="available" id="available" value="">
									<div class="form-group">
										<div class="row">
											<div class="col-xs-3">
												<label for="quantity">Quantity:</label>
												<input type="number" class="form-control" id="quantity" name="quantity" min="0">
											</div>
											<div class="col-xs-9"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="size">Size:</label>
										<select name="size" id="size">
											<option value=""></option>
											<?php foreach ($size_array as $str) {
												$str_array = explode(":", $str);
												$size = $str_array[0];
												$available = $str_array[1];
												//庫存大於0時才顯示
												if($available > 0){
													echo '<option value="'.$size.'" data-available="'.$available.'" >'.$size.'('.$available.' Available)</option>';
												}	
											} ?>
										</select>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" onclick="closeModal()">Close</button>
					<button class="btn btn-warning" onclick="add_to_cart();return false;"><span class="glyphicon glyphicon-shopping-cart"></span> Add To Cart</button>
				</div>
			</div>
		</div>
		
	</div>
	<script>
		
		jQuery("#size").change(function(){
			var available = jQuery("#size option:selected").data("available");
			jQuery("#available").val(available);
		});

		function closeModal(){
			jQuery("#details-modal").modal('hide');
			setTimeout(function(){
				jQuery("#details-modal").remove();
				//.modal-backdrop是modal出現時在後面的背景
				jQuery(".modal-backdrop").remove();
			},500);
		}
	</script>

	<?php echo ob_get_clean(); ?>
	<!-- string ob_get_clean ( void )
			得到当前缓冲区的内容并删除当前输出缓冲区。 -->