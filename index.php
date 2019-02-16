<!DOCTYPE html>
<html lang="en">
<head>
	<title>Mark's Shop</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/main.css">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<!-- Top Nav Bar -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<a href="index.php" class="navbar-brand">Mark's Shop</a>
			<ui class="nav navbar-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Men<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#">Shirts</a></li>
						<li><a href="#">Pants</a></li>
						<li><a href="#">Shoes</a></li>
						<li><a href="#">Accessories</a></li>
					</ul>
				</li>
			</ui>
		</div>
	</nav>

	<!-- Header -->
	<div id="headerWrapper">
		<div id="back-flower"></div>
		<div id="logotext"></div>
		<div id="fore-flower"></div>
	</div>

	<script>
		// $(window).scroll(function(){
		// 	var vscroll = $(this).scrollTop();
		// 	console.log(vscroll);
		// });

		//為避免在使用$使跟其他js產生衝突 所以使用jQuery()
		//選擇window,並做滾動卷軸的監聽
		jQuery(window).scroll(function(){
			//將滾軸的相對於頂部的位置回傳給vscroll
			var vscroll = jQuery(this).scrollTop();
			//console.log(vscroll);
			jQuery("#logotext").css({
				// tranform有多種用法 像translate(平移)是其中一種,translate(x,y),x為正向右平移 y為正則向下
				"transform" : "translate(0px,"+vscroll/2+"px)"
			});

			jQuery("#back-flower").css({
				"transform" : "translate("+vscroll/5+"px,-"+vscroll/12+"px)"
			});

			jQuery("#fore-flower").css({
				"transform" : "translate(0px,-"+vscroll/2+"px)"
			});
		})
	</script>
</body>
</html>