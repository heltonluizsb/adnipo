<?php	
	date_default_timezone_set('America/Sao_Paulo');

	$autoload = function($class){
		include('../classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);
	include('../config.php');

	$data = "";

	if(isset($_POST['acao']) && $_POST['acao'] == 'listar-empresas-por-categoria'){
		$query = '';
		$categorias = [];
		$empresa_categoria = [];		
		foreach ($_POST as $key => $value) {
			if($key != 'acao'){
				if($query == '')
					$query = ' WHERE `id_categoria` = ?';
				else
					$query .= ' OR `id_categoria` = ?';
				$categorias[] = $key;
			}
		}

		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.categoria` $query");
		$sql->execute($categorias);
		$empresa_categoria = $sql->fetchAll();

		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa`");
		$sql->execute();
		$todas_as_empresas = $sql->fetchAll();

		foreach ($todas_as_empresas as $key => $value) {
			$empresa_na_lista = false;
			foreach ($empresa_categoria as $key2 => $value2) {
				if($value2['id_empresa'] == $value['id']){
					$empresa_na_lista = true;
				}
			}

			if($empresa_na_lista){

				$data .= '<div class="empresa-single">
					<div class="box" href="'.INCLUDE_PATH.'perfil_empresa/'.$value['slug'].'">
						<img src="'.INCLUDE_PATH.'uploads/'.$value['imagem'].'">
						<h3>'.$value['nome'].'</h3>
						<div class="empresa-single-line"></div>
			 			<h2>Categorias</h2>
			 			<ul>';
				$empresa_id = $value['id'];
		 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.categoria` WHERE `id_empresa` = $empresa_id");
		 		$sql->execute();
		 		$categorias_empresa = $sql->fetchAll();
		 		foreach ($categorias_empresa as $key2 => $value2) {
		 			$categoria_id = $value2['id_categoria'];	 					
			 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` WHERE `id` = $categoria_id");
			 		$sql->execute();
			 		$categoria_dados = $sql->fetch();
			 	$data .= '<li>'.strtoupper($categoria_dados['nome']).'</li>';
			 	}
			 	$data .= '</ul> <a href="'.INCLUDE_PATH.'perfil_empresa/'.$value['slug'].'" style="display:block; margin-top:20px;">VER PERFIL</a>
					</div>
				</div>';
			}
		}

		echo $data;
	}
 ?>