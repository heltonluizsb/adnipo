<?php
	session_start();

	$autoload = function($class){
		if($class == 'Email'){
			include('phpmailer/PHPMailerAutoload.php');
		}
		include('classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);

	define('INCLUDE_PATH','https://localhost/adnipo/');
	define('INCLUDE_PATH_PANEL',INCLUDE_PATH.'paineladmin/');

	//CONECTAR COM BANCO DE DADOS
	define('HOST','localhost');
	define('USER','root');
	define('PASSWORD','');
	define('DATABASE','adnipo');
	define('BASE_DIR',__DIR__);
	

	//FUNÇÕES DO MAIN
	function selecionadoMenu($par){
		$url = explode('/',@$_GET['url'])[0];
		if($url == $par){
			echo 'menu-active';
		}
	}
?>