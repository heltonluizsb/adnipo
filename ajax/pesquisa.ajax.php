<?php 
	date_default_timezone_set('America/Sao_Paulo');

	$autoload = function($class){
		include('../classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);
	include('../config.php');

	$data['sucesso'] = true;
	$data['mensagem'] = "";
	$data['tb_user'] = [];
	$data['tb_empresa'] = [];
	$data['tb_categoria'] = [];
	$data['tb_filial'] = [];
	$isjson = true;

	if(isset($_POST['acao']) && $_POST['acao'] == 'pesquisar'){
		$dado_pesquisa = $_POST['dado_pesquisa'];



		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `login` LIKE '%$dado_pesquisa%' ORDER BY `nome`");
		$sql->execute();

		if($sql->rowCount() > 0){
			$tb_user_login = $sql->fetchAll();
			$data['tb_user']['login'] = "";

			foreach ($tb_user_login as $key => $value) {
				if($value['img_perfil'] == ''){
					$data['tb_user']['login'] .= '<div class="pesquisa-janela-img"><a href="'.INCLUDE_PATH.'perfil/'.$value['login'].'"><img src="'.INCLUDE_PATH.'images/clientevazio.JPG"></a></div>';
				} else{
					$data['tb_user']['login'] .= '<div class="pesquisa-janela-img"><a href="'.INCLUDE_PATH.'perfil/'.$value['login'].'"><img src="'.INCLUDE_PATH.'uploads/'.$value['img_perfil'].'"></a></div>';
				}
				$data['tb_user']['login'] .= '<div class="pesquisa-janela-nome"><a href="'.INCLUDE_PATH.'perfil/'.$value['login'].'">'.$value['nome'].'</a></div>';
			}
		}



		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `nome` LIKE '%$dado_pesquisa%' ORDER BY `nome`");
		$sql->execute();

		if($sql->rowCount() > 0){
			$tb_user_nome = $sql->fetchAll();
			$data['tb_user']['nome'] = "";

			foreach ($tb_user_nome as $key => $value) {
				if($value['img_perfil'] == ''){
					$data['tb_user']['nome'] .= '<div class="pesquisa-janela-img"><a href="'.INCLUDE_PATH.'perfil/'.$value['login'].'"><img src="'.INCLUDE_PATH.'images/clientevazio.JPG"></a></div>';
				} else{
					$data['tb_user']['nome'] .= '<div class="pesquisa-janela-img"><a href="'.INCLUDE_PATH.'perfil/'.$value['login'].'"><img src="'.INCLUDE_PATH.'uploads/'.$value['img_perfil'].'"></a></div>';
				}
				$data['tb_user']['nome'] .= '<div class="pesquisa-janela-nome"><a href="'.INCLUDE_PATH.'perfil/'.$value['login'].'">'.$value['nome'].'</a></div>';
			}
		}



		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa` WHERE `nome` LIKE '%$dado_pesquisa%' ORDER BY `nome`");
		$sql->execute();

		if($sql->rowCount() > 0){
			$tb_empresa_nome = $sql->fetchAll();
			$data['tb_empresa']['nome'] = "";

			foreach ($tb_empresa_nome as $key => $value) {
				if($value['imagem'] == ''){
					$data['tb_empresa']['nome'] .= '<div class="pesquisa-janela-img"><a href="'.INCLUDE_PATH.'perfil_empresa/'.$value['slug'].'"><img src="'.INCLUDE_PATH.'images/clientevazio.JPG"></a></div>';
				} else{
					$data['tb_empresa']['nome'] .= '<div class="pesquisa-janela-img"><a href="'.INCLUDE_PATH.'perfil_empresa/'.$value['slug'].'"><img src="'.INCLUDE_PATH.'uploads/'.$value['imagem'].'"></a></div>';
				}
				$data['tb_empresa']['nome'] .= '<div class="pesquisa-janela-nome"><a href="'.INCLUDE_PATH.'perfil_empresa/'.$value['slug'].'">'.$value['nome'].'</a></div>';
			}
		}



		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` WHERE `nome` LIKE '%$dado_pesquisa%' ORDER BY `nome`");
		$sql->execute();

		if($sql->rowCount() > 0){
			$tb_categoria_nome = $sql->fetchAll();
			$data['tb_categoria']['nome'] = "";

			foreach ($tb_categoria_nome as $key => $value) {
				$data['tb_categoria']['nome'] .= '<div class="pesquisa-janela-nome pesquisa-janela-categoria"><a href="'.INCLUDE_PATH.'empresas?pesquisa_categoria='.$value['id'].'" name="'.$value['id'].'">'.strtoupper($value['nome']).'</a></div>';
			}
		}



		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_filial` WHERE `nome` LIKE '%$dado_pesquisa%' ORDER BY `nome`");
		$sql->execute();

		if($sql->rowCount() > 0){
			$tb_filial_nome = $sql->fetchAll();
			$data['tb_filial']['nome'] = "";

			foreach ($tb_filial_nome as $key => $value) {
				$data['tb_filial']['nome'] .= '<div class="pesquisa-janela-nome pesquisa-janela-categoria"><a href="'.INCLUDE_PATH.'clientes?pesquisa_filial='.$value['id'].'" name="'.$value['id'].'">'.strtoupper($value['nome']).'</a></div>';
			}
		}
	}

	if($isjson){
		die(json_encode($data));
	}
 ?>