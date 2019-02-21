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



 ?>