<?php 
//define(A,B); 將B值設給A;
//$_SERVER['DOCUMENT_ROOT'] 當前運行腳本所在的文檔根目錄(ex: C:/xampp/htdocs/)。在服務器配置文件中定義。
define('BASEURL',$_SERVER['DOCUMENT_ROOT'].'/tutorial/');
define('CART_COOKIE','af23dD3FHOI98dhkKJD8EJH3023');
define('CART_COOKIE_EXPIRE',time()+(86400 * 30));
define('TAXRATE',0.087);

 ?>