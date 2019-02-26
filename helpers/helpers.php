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



 ?>