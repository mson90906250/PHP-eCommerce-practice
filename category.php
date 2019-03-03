<?php 
	require_once "core/init.php";
	include "includes/head.php";
	include "includes/navigation.php";
	include "includes/headerpartial.php";
	include "includes/leftbar.php";

	if(isset($_GET['cat'])){
		$cat_id = sanitize($_GET['cat']); 
	}else{
		$cat_id = "";
	}

	$sql = "SELECT * FROM product WHERE categories = '$cat_id' AND deleted = 0 ";
	//來自init.php的$db
	$productQ = $db->query($sql);
	$category = getCategory($cat_id);

?>


		<!-- Main Content-->
		<div class="col-md-8">
			<div class="row">
				<h2 class="text-center"><?= $category['parent'].' '.$category['child'] ?></h2>
				<?php while($product = $productQ->fetch_assoc()) : ?>
					<div class="col-md-3" style="overflow: hidden;">
						<!-- <?= a ?> 等同 <?php echo a ?> -->
						<h4 class="text-center"><?= $product['title'] ?></h4>
						<img src="<?= $product['image'] ?>" alt="<?= $product['title'] ?>" class="img-thumb ">
						<p class="list-price text-danger">List Price: <s>$<?= $product['list_price'] ?></s></p>
						<p class="price">Our Price: $<?= $product['price'] ?></p>		
						<button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?= $product['id']; ?>)">
							Details
						</button>
					</div>
				<?php endwhile; ?>
			</div>
		</div>

<?php 
	//include "includes/detailsmodal.php";
	include "includes/rightbar.php";
	include "includes/footer.php";
 ?>
