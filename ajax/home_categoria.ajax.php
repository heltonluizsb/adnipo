<?php 
	session_start();
	date_default_timezone_set('America/Sao_Paulo');

	$autoload = function($class){
		include('../classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);
	include('../config.php');

	$_SESSION['home_categoria'] = $_POST['name'];

	echo $_SESSION['home_categoria'];
 ?>