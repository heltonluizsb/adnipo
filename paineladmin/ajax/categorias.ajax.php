<?php 
	date_default_timezone_set('America/Sao_Paulo');

	$autoload = function($class){
		include('../../classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);
	include('../../config.php');

	$data['sucesso'] = true;
	$data['mensagem'] = "";
	if(isset($_POST['acao']) && $_POST['acao'] == 'categoria_alterar'){
		$categoria_id = $_POST['categoria_id'];
		$nome_novo = $_POST['nome_novo'];
		$slug = padrao::generateSlug($nome_novo);

		if($nome_novo == ''){
			$data['sucesso'] = false;
			$data['mensagem'] = "O nome não pode estar em branco";
		}

		if($data['sucesso']){
			$sql = MySql::conectar()->prepare("UPDATE `tb_categoria.empresa` SET nome = ?,slug = ? WHERE id = ?");
			$sql->execute(array($nome_novo,$slug,$categoria_id));
			$data['mensagem'] = 'Nome da Categoria Alterado com sucesso';
		}
	}
	else if(isset($_POST['acao']) && $_POST['acao'] == 'categoria_excluir'){
		$categoria_id = $_POST['categoria_id'];

		if($data['sucesso']){
			$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.categoria` WHERE `id_categoria` = ?");
			$sql->execute(array($categoria_id));
			$sql = MySql::conectar()->prepare("DELETE FROM `tb_categoria.empresa` WHERE id = ?");
			$sql->execute(array($categoria_id));
			$data['mensagem'] = 'Categoria Excluida com Sucesso';
		}
	}
	else if(isset($_POST['acao']) && $_POST['acao'] == 'categoria_cadastrar'){
		$nome_novo = $_POST['nome_novo'];
		$slug = padrao::generateSlug($nome_novo);

		if($nome_novo == ''){
			$data['sucesso'] = false;
			$data['mensagem'] = "O nome não pode estar em branco";
		}else{
			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` WHERE slug = ?");
			$sql->execute(array($slug));
			if($sql->rowCount() > 0){
				$data['sucesso'] = false;
				$data['mensagem'] .= "<br>Esta categoria já existe.";
			}
		}

		if($data['sucesso']){
			$sql = MySql::conectar()->prepare("INSERT INTO `tb_categoria.empresa` VALUES(null,?,?)");
			$sql->execute(array($nome_novo,$slug));
			$data['mensagem'] = 'Categoria Cadastrada com sucesso';
		}
	}

	die(json_encode($data));
	?>