<!-- 用於跟database連結 -->
<?php 
	//mysqli_connect('localhost','user','password','dbname')
	$db = mysqli_connect('localhost','root','','tutorial');

	//判斷連線是否成功
	//mysqli_connect_errno()如果連線過程中有錯誤時,會回傳true
	if(mysqli_connect_errno()){
		echo "Database connection failed following errors ".mysqli_connect_error();
		//kill the page 即接在後面的内容無法呈現出來
		die();
	}

	require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/config.php';
	require_once BASEURL.'helpers/helpers.php';

 ?>