<?php 
	date_default_timezone_set('America/Sao_Paulo');

	$autoload = function($class){
		include('../classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);
	include('../config.php');

	$data['sucesso'] = true;
	$data['mensagem'] = "";
	$isjson = true;

	if(isset($_POST['acao']) && $_POST['acao'] == 'editar-imagem-empresa'){
		$imagem_atual = $_POST['imagem_atual'];
		$imagem_nova = $_FILES['imagem_nova'];
		$empresa_id = $_POST['empresa_id'];
		$nome_tabela = $_POST['nome_tabela'];
		$data['slug'] = $_POST['slug'];
		$site = $_POST['site'];

		if(isset($imagem_nova['name']) && $imagem_nova['name'] != ''){
			if(padrao::imagemValida($imagem) == false){
				$data['sucesso'] = false;
				if($data['mensagem'] == ""){
					$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* Formato da Imagem incorreto';
				}
				else{
					$data['mensagem'] .= '<br>*  Formato da Imagem incorreto';
				}
			}
		}

		if($data['sucesso'] == true){
			$imagem_nova = padrao::uploadFile($imagem_nova);
			$sql = MySql::conectar()->prepare("UPDATE `tb_empresa` SET `imagem` = ? WHERE id = ?");
			$sql->execute(array($imagem_nova,$empresa_id));
			padrao::deleteFile($imagem_atual);
		}
	}
	else if(isset($_POST['acao']) && $_POST['acao'] == 'editar-descricao-empresa'){
		$descricao = $_POST['descricao'];
		$endereco = $_POST['endereco'];
		$telefone = $_POST['telefone'];
		$site = $_POST['site'];
		$nome_tabela = $_POST['nome_tabela'];
		$empresa_id = $_POST['empresa_id'];
		$data['slug'] = $_POST['slug'];
		$sql = MySql::conectar()->prepare("UPDATE `tb_empresa` SET `descricao` = ?,`endereco` = ?,`telefone` = ?,`site` = ? WHERE id = ?");
		$sql->execute(array($descricao,$endereco,$telefone,$site,$empresa_id));
	}
	else if(isset($_POST['acao']) && $_POST['acao'] == 'editar-categoria-empresa'){
		$categoria_1 = $_POST['categoria_1'];
		$categoria_1_id = $_POST['categoria_1_id'];
		$categoria_2 = $_POST['categoria_2'];
		$categoria_2_id = $_POST['categoria_2_id'];
		$categoria_3 = $_POST['categoria_3'];
		$categoria_3_id = $_POST['categoria_3_id'];

		$empresa_id = $_POST['empresa_id'];

		if(($categoria_1 == '' || $categoria_1 == 'limpar' || $categoria_1 == 'Selecione uma categoria')
		&& ($categoria_2 == '' || $categoria_2 == 'limpar' || $categoria_2 == 'Selecione uma categoria')
		&& ($categoria_3 == '' || $categoria_3 == 'limpar' || $categoria_3 == 'Selecione uma categoria')){
			$data['sucesso'] = false;
			$data['mensagem'] = ' Ao menos uma das categorias precisa estar preenchida.';
		}

		if($data['sucesso'] == true){

			$data['slug'] = $_POST['slug'];

			if($categoria_1 == '' || $categoria_1 == 'limpar' || $categoria_1 == 'Selecione uma categoria'){

				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.categoria` WHERE `id_categoria` = ? AND `id_empresa` = ?");
				$sql->execute(array($categoria_1_id,$empresa_id));

				if($sql->rowCount() > 0){
					$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.categoria` WHERE `id_categoria` = ? AND `id_empresa` = ?");
					$sql->execute(array($categoria_1_id,$empresa_id));					
				}
			}
			else{

				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.categoria` WHERE `id_categoria` = ? AND `id_empresa` = ?");
				$sql->execute(array($categoria_1_id,$empresa_id));

				if($sql->rowCount() > 0){				
					$sql = MySql::conectar()->prepare("UPDATE `tb_empresa.categoria` SET `id_categoria` = ? WHERE `id_categoria` = ? AND `id_empresa` = ?");
					$sql->execute(array($categoria_1,$categoria_1_id,$empresa_id));	
				}
				else{			
					$sql = MySql::conectar()->prepare("INSERT INTO `tb_empresa.categoria` VALUES (null,?,?)");
					$sql->execute(array($empresa_id,$categoria_1));					
				}
			}
			
			if($categoria_2 == '' || $categoria_2 == 'limpar' || $categoria_2 == 'Selecione uma categoria'){

				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.categoria` WHERE `id_categoria` = ? AND `id_empresa` = ?");
				$sql->execute(array($categoria_2_id,$empresa_id));

				if($sql->rowCount() > 0){
					$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.categoria` WHERE `id_categoria` = ? AND `id_empresa` = ?");
					$sql->execute(array($categoria_2_id,$empresa_id));					
				}
			}
			else{

				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.categoria` WHERE `id_categoria` = ? AND `id_empresa` = ?");
				$sql->execute(array($categoria_2_id,$empresa_id));

				if($sql->rowCount() > 0){				
					$sql = MySql::conectar()->prepare("UPDATE `tb_empresa.categoria` SET `id_categoria` = ? WHERE `id_categoria` = ? AND `id_empresa` = ?");
					$sql->execute(array($categoria_2,$categoria_2_id,$empresa_id));	
				}
				else{			
					$sql = MySql::conectar()->prepare("INSERT INTO `tb_empresa.categoria` VALUES (null,?,?)");
					$sql->execute(array($empresa_id,$categoria_2));					
				}			
			}
			
			if($categoria_3 == '' || $categoria_3 == 'limpar' || $categoria_3 == 'Selecione uma categoria'){

				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.categoria` WHERE `id_categoria` = ? AND `id_empresa` = ?");
				$sql->execute(array($categoria_3_id,$empresa_id));

				if($sql->rowCount() > 0){
					$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.categoria` WHERE `id_categoria` = ? AND `id_empresa` = ?");
					$sql->execute(array($categoria_3_id,$empresa_id));					
				}
			}
			else{

				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.categoria` WHERE `id_categoria` = ? AND `id_empresa` = ?");
				$sql->execute(array($categoria_2_id,$empresa_id));

				if($sql->rowCount() > 0){				
					$sql = MySql::conectar()->prepare("UPDATE `tb_empresa.categoria` SET `id_categoria` = ? WHERE `id_categoria` = ? AND `id_empresa` = ?");
					$sql->execute(array($categoria_2,$categoria_2_id,$empresa_id));	
				}
				else{			
					$sql = MySql::conectar()->prepare("INSERT INTO `tb_empresa.categoria` VALUES (null,?,?)");
					$sql->execute(array($empresa_id,$categoria_3));				
				}
			}
		}
	}
	else if(isset($_POST['acao']) && $_POST['acao'] == 'editar-permissao-empresa'){
		$user_id = $_POST['user_id'];
		$empresa_id = $_POST['empresa_id'];
		$slug = $_POST['slug'];
		$permissao_id = $_POST['permissao_id'];

		$sql = MySql::conectar()->prepare("UPDATE `tb_empresa.user` SET `permissao_id` = ? WHERE `empresa_id` = ? AND `user_id` = ?");
		$sql->execute(array($permissao_id,$empresa_id,$user_id));

		$data['mensagem'] = 'Permissão alterada com sucesso';

	}

	if($isjson){
		die(json_encode($data));
	}
 ?>