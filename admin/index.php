<?php 
	require_once '../core/init.php';
	//確認是否登入成功
	if(!isLoggedIn()){
		//登入失敗時執行
		//loginErrorRedirect();
		header("Location: login.php");
	}
	// //確認權限是否足夠
	// if(!hasPermission()){
	// 	//不足時
	// 	permissionErrorRedirect("brands.php");
	// }

	include 'includes/head.php';
	include 'includes/navigation.php';
	

 ?>

Administrator Home

 <?php 
 	include 'includes/footer.php';
 ?>