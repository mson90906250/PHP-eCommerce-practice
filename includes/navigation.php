<?php 
	$sql = "SELECT * FROM categories WHERE parent = 0";
	//取得parent為0的資料 , 用於做navigation的主選項
	$pquery = $db -> query($sql);//parentquery
 ?>

<!-- Top Nav Bar -->
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<a href="index.php" class="navbar-brand">Shaunta's Boutique</a>
		<ui class="nav navbar-nav">
			<!-- 製作loop -->
			<?php while($parent = $pquery -> fetch_assoc()) : ?>
				<?php 
					$parent_id = $parent['id']; 
					$sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
					$cquery = $db -> query($sql2);//childquery
				?>
				<!-- Menu Items -->
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category']; ?><span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<?php while($child = $cquery -> fetch_assoc()) : ?>
							<li><a href="category.php?cat=<?= $child['id'] ?>"><?php echo $child['category'] ?></a></li>
						<?php endwhile; ?>
					</ul>
				</li>
			
			<?php endwhile; ?>
		</ui>
	</div>
</nav>