<!-- Top Nav Bar -->
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        </button>
			<a href="index.php" class="navbar-brand">Shaunta's Boutique Admin</a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ui class="nav navbar-nav">			
				<!-- Menu Items -->
				<li><a href="index.php">My Dashboard</a></li>
				<li><a href="brands.php">Brand</a></li>
				<li><a href="categories.php">Categories</a></li>
				<li><a href="products.php">Products</a></li>
				<li><a href="archived.php">Archived</a></li>
				<?php if(hasPermission("admin")): ?>
					<li><a href="users.php">Users</a></li>
				<?php endif; ?>
				<li class="dropdown'">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?= $user_data['first_name'] ?> ! <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="change_password.php">Change Password</a></li>
						<li><a href="logout.php">Log Out</a></li>
					</ul>
				</li>

				<!-- <li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category']; ?><span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						
							<li><a href="#"><?php echo $child['category'] ?></a></li>
						
					</ul>
				</li>
			 -->
			</ui>
		</div>
	</div>
</nav>