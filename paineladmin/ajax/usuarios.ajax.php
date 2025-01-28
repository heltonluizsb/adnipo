<?php 
	date_default_timezone_set('America/Sao_Paulo');

	$autoload = function($class){
		include('../../classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);
	include('../../config.php');

	$data['sucesso'] = true;
	$data['mensagem'] = "";
	if(isset($_POST['acao']) && $_POST['acao'] == 'user_alterar'){
		$user_id = $_POST['user_id'];
		$nova_senha = $_POST['nova_senha'];
		$confirma_senha = $_POST['confirma_senha'];

		$data['class'] = 'user_'.$user_id;

		if($nova_senha != $confirma_senha){
			$data['sucesso'] = false;
			$data['mensagem'] = "Senhas não conferem";
		}


		if($data['sucesso']){
			$sql = MySql::conectar()->prepare("UPDATE `tb_user` SET `senha` = ? WHERE id = ?");
			$sql->execute(array($nova_senha,$user_id));
			$data['mensagem'] = 'Senha do usuário alterarda com sucesso';
		}
	} else if(isset($_POST['acao']) && $_POST['acao'] == 'user_excluir'){
		$user_id = $_POST['user_id'];

		$sql = MySql::conectar()->prepare("DELETE FROM `tb_post` WHERE `origem_tipo` = ? AND `origem_id` = ?");
		$sql->execute(array(0,$user_id));
		$sql = MySql::conectar()->prepare("DELETE FROM `tb_mensagem` WHERE `remetente_id` = ? OR `destinatario_id` = ?");
		$sql->execute(array($user_id,$user_id));
		$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.solicitacao` WHERE `user_id` = ?");
		$sql->execute(array($user_id));
		$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.user` WHERE `user_id` = ?");
		$sql->execute(array($user_id));
		$sql = MySql::conectar()->prepare("DELETE FROM `tb_user` WHERE id = ?");
		$sql->execute(array($user_id));
		$data['mensagem'] = "Usuário excluido com sucesso";

	}

	die(json_encode($data));
	?>