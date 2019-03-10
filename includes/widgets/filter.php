<?php 
	$cat_id = (isset($_REQUEST['cat']))?sanitize($_REQUEST['cat']):"";
	$price_sort = (isset($_REQUEST['price_sort']))?sanitize($_REQUEST['price_sort']):"";
	$min_price = (isset($_REQUEST['min_price']))?sanitize($_REQUEST['min_price']):"";
	$max_price = (isset($_REQUEST['max_price']))?sanitize($_REQUEST['max_price']):"";
	$brandQ = $db->query("SELECT * FROM brand");
	$b = (isset($_REQUEST['brand']))?sanitize($_REQUEST['brand']):"";
 ?>

<h3 class="text-center">Search By</h3>
<h4 class="text-center">Price</h4>
<form action="search.php" method="post">
	<input type="hidden" name="cat" value="<?php echo $cat_id; ?>">
	<!-- 如果沒有點擊low 或 high的話 -->
	<input type="hidden" name="price_sort" value="0">
	<input type="radio" name="price_sort" value="low" <?php echo (($price_sort == "low")?"checked":""); ?>>Low to High<br>
	<input type="radio" name="price_sort" value="high" <?php echo (($price_sort == "high")?"checked":""); ?>>High to Low<br><br>
	<!-- price range -->
	<input type="text" name="min_price" class="price-range" placeholder="Min $" value="<?php echo $min_price; ?>">To
	<input type="text" name="max_price" class="price-range" placeholder="Max $" value="<?php echo $max_price; ?>"><br><br>
	<!-- brand -->
	<h4 class="text-center">Brand</h4>
	<input type="radio" name="brand" value="" <?php echo (($b == "")?"checked":""); ?>>All<br>
	<?php while($brand = mysqli_fetch_assoc($brandQ)): ?>
		<input type="radio" name="brand" value="<?php echo $brand['id']; ?>" <?php echo ($b == $brand['brand'])?"checked":"" ?>><?php echo $brand['brand']; ?><br>
	<?php endwhile; ?>
	<input type="submit" value="Search" class="btn btn-xs btn-primary">

</form>