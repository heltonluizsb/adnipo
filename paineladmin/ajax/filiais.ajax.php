<?php 
	date_default_timezone_set('America/Sao_Paulo');

	$autoload = function($class){
		include('../../classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);
	include('../../config.php');

	$data['sucesso'] = true;
	$data['mensagem'] = "";
	if(isset($_POST['acao']) && $_POST['acao'] == 'filial_alterar'){
		$filial_id = $_POST['filial_id'];
		$nome_novo = $_POST['nome_novo'];
		$slug = padrao::generateSlug($nome_novo);

		if($nome_novo == ''){
			$data['sucesso'] = false;
			$data['mensagem'] = "O nome não pode estar em branco";
		}
		else{
			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_filial` WHERE slug = ?");
			$sql->execute(array($slug));
			if($sql->rowCount() > 0){
				$data['sucesso'] = false;
				$data['mensagem'] .= "<br>Esta filial já existe.";
			}
		}

		if($data['sucesso']){
			$sql = MySql::conectar()->prepare("UPDATE `tb_filial` SET nome = ?, slug = ? WHERE id = ?");
			$sql->execute(array($nome_novo,$slug,$filial_id));
			$data['mensagem'] = 'Nome da Categoria Alterado com sucesso';
		}
	}
	else if(isset($_POST['acao']) && $_POST['acao'] == 'filial_excluir'){
		$filial_id = $_POST['filial_id'];

		if($data['sucesso']){
			$sql = MySql::conectar()->prepare("DELETE FROM `tb_filial` WHERE id = ?");
			$sql->execute(array($filial_id));
			$data['mensagem'] = 'Filial Excluida com Sucesso';
		}
	}
	else if(isset($_POST['acao']) && $_POST['acao'] == 'filial_cadastrar'){
		$nome_novo = $_POST['nome_novo'];
		$slug = padrao::generateSlug($nome_novo);

		if($nome_novo == ''){
			$data['sucesso'] = false;
			$data['mensagem'] = "O nome não pode estar em branco";
		}
		else{
			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_filial` WHERE slug = ?");
			$sql->execute(array($slug));
			if($sql->rowCount() > 0){
				$data['sucesso'] = false;
				$data['mensagem'] .= "<br>Esta filial já existe.";
			}
		}

		if($data['sucesso']){
			$sql = MySql::conectar()->prepare("INSERT INTO `tb_filial` VALUES(null,?,?)");
			$sql->execute(array($nome_novo,$slug));
			$data['mensagem'] = 'Categoria Cadastrada com sucesso';
		}
	}

	die(json_encode($data));
	?>