<?php 

	function display_errors($errors){
		$display = '<ul class="bg-danger">';
		foreach ($errors as $error) {
			$display .= '<li class="text-danger">'.$error.'</li>';
		}
		$display .= '</ul>';
		return $display;
	}

	function sanitize($dirty){
		//防止他人輸入html語法破壞該頁面
		return htmlentities($dirty,ENT_QUOTES,"UTF-8");
	}
	

	//number_format(number,decimals,decimalpoint,separator
	//number	必需。要格式化的数字。如果未设置其他参数，则数字会被格式化为不带小数点且以逗号（,）作为千位分隔符。
	//decimals	可选。规定多少个小数。如果设置了该参数，则使用点号（.）作为小数点来格式化数字。
	//decimalpoint	可选。规定用作小数点的字符串。
	//separator	可选。规定用作千位分隔符的字符串。仅使用该参数的第一个字符。比如 "xxx" 仅输出 "x"。
	//注释：如果设置了该参数，那么所有其他参数都是必需的。
	function money($money){
		return "$".number_format($money,2);
	}

	function login($user_id){
		$_SESSION['SBUser'] = $user_id;
		//更新last_login
		//因為$db為global變數所以不能直接被function使用 必須在前面加上global才能用
		global $db;
		$date = gmdate('Y-m-d H:i:s',time() + 8*3600);
		$db->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");
		$_SESSION['success_flash'] = "You are now logged in.";
		header("Location: index.php");
	}

	function isLoggedIn(){
		if(isset($_SESSION['SBUser']) && $_SESSION['SBUser'] > 0){
			return true;
		}
		return false;
	}

	function loginErrorRedirect($url = "login.php"){
		$_SESSION['error_flash'] = "You must be logged in to access that page.";
		header("Location: ".$url);
	}

	//in_array() 函数搜索数组中是否存在指定的值。
	//in_array(search,array,type)
	//注释：如果 search 参数是字符串且 type 参数被设置为 TRUE，则搜索区分大小写。
	function hasPermission($permission = "admin"){
		global $user_data;
		$permissionArray = explode(",",$user_data['permissions']);
		if(in_array($permission, $permissionArray,true)){
			return true;
		}
		return false;
	}

	function permissionErrorRedirect($url = "login.php"){
		$_SESSION['error_flash'] = "You do not have permission to access that page.";
		header("Location: ".$url);
	}

	//轉換時間格式
	function prettyDate($date){
		return date("M d,Y h:i A",strtotime($date));
	}




 ?>