<?php 
//用來跟database做連結
	//mysqli_connect('localhost','user','password','dbname')
	$db = mysqli_connect('localhost','root','','tutorial');

	//判斷連線是否成功
	//mysqli_connect_errno()如果連線過程中有錯誤時,會回傳true
	if(mysqli_connect_errno()){
		echo "Database connection failed following errors ".mysqli_connect_error();
		//kill the page 即接在後面的内容無法呈現出來
		die();
	}

	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/config.php';
	require_once BASEURL.'helpers/helpers.php';
	require BASEURL.'vendor/autoload.php';

	$cart_id = "";
	if(isset($_COOKIE[CART_COOKIE])){
		$cart_id = $_COOKIE[CART_COOKIE];
	}
	


	if(isset($_SESSION['SBUser'])){
		$user_id = $_SESSION['SBUser'];
		$query = $db->query("SELECT * FROM users WHERE id = '$user_id'");
		$user_data = mysqli_fetch_assoc($query);
		$fullName = explode(" ", $user_data['full_name']);
		$user_data['first_name'] = $fullName[0];
		$user_data['last_name'] = $fullName[1];

	}

	if(isset($_SESSION['success_flash'])){
		echo "<div id='success_flash' class='bg-success'><p class='text-success text-center'>".$_SESSION['success_flash']."</p></div>";
		unset($_SESSION['success_flash']);
	}

	if(isset($_SESSION['error_flash'])){
		echo "<div id='error_flash' class='bg-danger'><p class='text-danger  text-center'>".$_SESSION['error_flash']."</p></div>";
		unset($_SESSION['error_flash']);
	}

 ?>


 