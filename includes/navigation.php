<?php 
	$sql = "SELECT * FROM categories WHERE parent = 0";
	//取得parent為0的資料 , 用於做navigation的主選項
	$pquery = $db -> query($sql);//parentquery
 ?>

<!-- Top Nav Bar -->

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="index.php" class="navbar-brand">Shaunta's Boutique</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
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
				<li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart </a></li>
			</ui>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>