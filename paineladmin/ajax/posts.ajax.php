<?php 
	date_default_timezone_set('America/Sao_Paulo');

	$autoload = function($class){
		include('../../classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);
	include('../../config.php');

	$data['sucesso'] = true;
	$data['mensagem'] = "";

	if(isset($_POST['acao']) && $_POST['acao'] == 'post_excluir'){
		$filial_id = $_POST['post_id'];

		if($data['sucesso']){
			$sql = MySql::conectar()->prepare("DELETE FROM `tb_post` WHERE id = ?");
			$sql->execute(array($filial_id));
			$data['mensagem'] = 'Post excluído com Sucesso';
		}
	}

	die(json_encode($data));
	?>