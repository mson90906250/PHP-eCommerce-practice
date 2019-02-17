<?php 
	require_once "core/init.php";
	include "includes/head.php";
	include "includes/navigation.php";
	include "includes/headerfull.php";
	include "includes/leftbar.php";

?>


		<!-- Main Content-->
		<div class="col-md-8">
			<div class="row">
				<h2 class="text-center">Feature Products</h2>
				<div class="col-md-3">
					<h4>Levis Jeans</h4>
					<img src="images/products/men4.png" alt="Levis Jeans" class="img-thumb">
					<p class="list-price text-danger">List Price: <s>$54.99</s></p>
					<p class="price">Our Price: $39.99</p>		
					<button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#details-1">
						Details
					</button>
				</div>

				<div class="col-md-3">
					<h4>Woman's Shirt</h4>
					<img src="images/products/women7.png" alt="Woman's Shirt" class="img-thumb">
					<p class="list-price text-danger">List Price: <s>$74.99</s></p>
					<p class="price">Our Price: $49.99</p>		
					<button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#details-1">
						Details
					</button>
				</div>

				<div class="col-md-3">
					<h4>Hollister Shirt</h4>
					<img src="images/products/men1.png" alt="Hollister Shirt" class="img-thumb">
					<p class="list-price text-danger">List Price: <s>$24.99</s></p>
					<p class="price">Our Price: $19.99</p>		
					<button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#details-1">
						Details
					</button>
				</div>

				<div class="col-md-3">
					<h4>Fancy Shoes</h4>
					<img src="images/products/women6.png" alt="Fancy Shoes" class="img-thumb ">
					<p class="list-price text-danger">List Price: <s>$64.99</s></p>
					<p class="price">Our Price: $49.99</p>		
					<button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#details-1">
						Details
					</button>
				</div>

				<div class="col-md-3">
					<h4>Boys Hoodie</h4>
					<img src="images/products/boys1.png" alt="Boys Hoodie" class="img-thumb">
					<p class="list-price text-danger">List Price: <s>$24.99</s></p>
					<p class="price">Our Price: $18.99</p>		
					<button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#details-1">
						Details
					</button>
				</div>

				<div class="col-md-3">
					<h4>Girls Dress</h4>
					<img src="images/products/girls3.png" alt="Girls Dress" class="img-thumb">
					<p class="list-price text-danger">List Price: <s>$34.99</s></p>
					<p class="price">Our Price: $19.99</p>		
					<button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#details-1">
						Details
					</button>
				</div>


				<div class="col-md-3">
					<h4>Woman's Skirt</h4>
					<img src="images/products/women3.png" alt="Woman's Skirt" class="img-thumb">
					<p class="list-price text-danger">List Price: <s>$84.99</s></p>
					<p class="price">Our Price: $69.99</p>		
					<button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#details-1">
						Details
					</button>
				</div>

				<div class="col-md-3">
					<h4>Purse</h4>
					<img src="images/products/women5.png" alt="Purse" class="img-thumb">
					<p class="list-price text-danger">List Price: <s>$544.99</s></p>
					<p class="price">Our Price: $399.99</p>		
					<button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#details-1">
						Details
					</button>
				</div>
			</div>
		</div>

<?php 
	include "includes/detailsmodal.php";
	include "includes/rightbar.php";
	include "includes/footer.php";
 ?>
