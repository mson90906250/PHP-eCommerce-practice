<?php 
	//$_SERVER['DOCUMENT_ROOT'] = "C:/xampp/htdocs";
	require_once $_SERVER['DOCUMENT_ROOT']."/tutorial/core/init.php";
	include "includes/head.php";
	include "includes/navigation.php";
	$sql = "SELECT * FROM product WHERE deleted = 0";
	$presult = $db->query($sql);

	//按下featured的按鈕
	//empty(0)會回傳true,即被認定為empty,所以"if(isset($_GET['featured'])&&!empty($_GET['featured'])){}"
	//這句會發生問題
	if(isset($_GET['featured'])){
		$id = $_GET['id'];
		$featured = $_GET['featured'];
		$featured_sql="UPDATE product SET featured = '$featured' WHERE id = '$id'";
		$db->query($featured_sql);
		header("Location: products.php");
	}
 ?>
 <h2 class="text-center">Products</h2>
 <a href="products.php?add=1" class="btn btn-success pull-right" id="add_product_btn">Add A Product</a><div class="clearfix"></div>
 <hr>
 <table class="table table-bordered table-condensed table-striped">
 	<thead>
 		<th></th>
 		<th>Product</th>
 		<th>Price</th>
 		<th>Category</th>
 		<th>Featured</th>
 		<th>Sold</th>
 	</thead>
 	<tbody>
 		<?php while($product = mysqli_fetch_assoc($presult)): 
 			$childID = $product['categories'];
 			$category_sql = "SELECT * FROM categories WHERE id = '$childID'";
 			$child_result = $db->query($category_sql);
 			$child_category = mysqli_fetch_assoc($child_result);
 			$parentID = $child_category['parent'];
 			$category_sql = "SELECT * FROM categories WHERE id = '$parentID'";
 			$parent_result = $db->query($category_sql);
 			$parent_category = mysqli_fetch_assoc($parent_result);
 			$category = $parent_category['category']."~".$child_category['category'];
 			?>
 			<tr>
 				<td>
 					<a href="products.php?edit=<?= $product['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
 					<a href="products.php?delete=<?= $product['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
 				</td>
 				<td><?= $product['title'] ?></td>
 				<td><?= money($product['price']) ?></td>
 				<td><?= $category ?></td>
 				<td><a href="products.php?featured=<?= (($product['featured'] == 0)?'1':'0'); ?>&id=<?= $product['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-<?= (($product['featured'] == 0)?'plus':'minus'); ?> "></span></a>
 					<?= (($product['featured'] == 1)?'featured product':'') ?>
 				</td>
 				<td>0</td>
 			</tr>
 		<?php endwhile; ?>	
 	</tbody>
 </table>

 <?php 
 	include "includes/footer.php";
  ?>