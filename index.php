<?php 
	require_once "core/init.php";
	include "includes/head.php";
	include "includes/navigation.php";
	include "includes/headerfull.php";
	include "includes/leftbar.php";

	$sql = "SELECT * FROM product WHERE featured = 1 ";
	//來自init.php的$db
	$featured = $db->query($sql);

?>


		<!-- Main Content-->
		<div class="col-md-8">
			<div class="row">
				<h2 class="text-center">Feature Products</h2>
				<?php while($product = $featured->fetch_assoc()) : ?>
					<div class="col-md-3">
						<!-- <?= a ?> 等同 <?php echo a ?> -->
						<h4><?= $product['title'] ?></h4>
						<img src="<?= $product['image'] ?>" alt="<?= $product['title'] ?>" class="img-thumb">
						<p class="list-price text-danger">List Price: <s>$<?= $product['list_price'] ?></s></p>
						<p class="price">Our Price: $<?= $product['price'] ?></p>		
						<button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#details-1">
							Details
						</button>
					</div>
				<?php endwhile; ?>
			</div>
		</div>

<?php 
	include "includes/detailsmodal.php";
	include "includes/rightbar.php";
	include "includes/footer.php";
 ?>
