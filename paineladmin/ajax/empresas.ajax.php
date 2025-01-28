<?php 
	date_default_timezone_set('America/Sao_Paulo');

	$autoload = function($class){
		include('../../classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);
	include('../../config.php');

	$data['sucesso'] = true;
	$data['mensagem'] = "";
	if(isset($_POST['acao']) && $_POST['acao'] == 'empresa_alterar'){
		$empresa_id = $_POST['empresa_id'];
		$proprietario_novo = $_POST['proprietario_novo'];

		$sql = MySql::conectar()->prepare("UPDATE `tb_empresa.user` SET `permissao_id` = ? WHERE empresa_id = ? AND user_id = ?");
		$sql->execute(array(1,$empresa_id,$proprietario_novo));
		$sql = MySql::conectar()->prepare("UPDATE `tb_empresa` SET `id_criador` = ? WHERE id = ?");
		$sql->execute(array($proprietario_novo,$empresa_id));
		$data['mensagem'] = 'Proprietário da Empresa Alterado com sucesso';

	}
	else if(isset($_POST['acao']) && $_POST['acao'] == 'empresa_excluir'){
		$empresa_id = $_POST['empresa_id'];

		$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.categoria` WHERE `id_empresa` = ?");
		$sql->execute(array($empresa_id));
		$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.solicitacao` WHERE `empresa_id` = ?");
		$sql->execute(array($empresa_id));
		$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.user` WHERE `empresa_id` = ?");
		$sql->execute(array($empresa_id));
		$sql = MySql::conectar()->prepare("DELETE FROM `tb_post` WHERE `origem_tipo` = ? AND `origem_id` = ?");
		$sql->execute(array(1,$empresa_id));
		$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa` WHERE `id` = ?");
		$sql->execute(array($empresa_id));
		$data['mensagem'] = 'Empresa Excluida com Sucesso';
	}

	die(json_encode($data));
	?>