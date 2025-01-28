<?php	
	date_default_timezone_set('America/Sao_Paulo');

	$autoload = function($class){
		include('../classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);
	include('../config.php');

	$data = "";

	if(isset($_POST['acao']) && $_POST['acao'] == 'listar-clientes-por-filial'){
		$query = '';
		$filiais = [];
		$clientes_filial = [];		
		foreach ($_POST as $key => $value) {
			if($key != 'acao'){
				if($query == '')
					$query = ' WHERE `filial_id` = ?';
				else
					$query .= ' OR `filial_id` = ?';
				$filiais[] = $key;
			}
		}

		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` $query");
		$sql->execute($filiais);
		$user_filial = $sql->fetchAll();

		foreach ($user_filial as $key => $value) {
			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_filial` WHERE `id` = ?");
			$sql->execute(array($value['filial_id']));
			if($sql->rowCount() > 0){
				$filial_dados_user = $sql->fetch();
			}
			else{
				$filial_dados_user['nome'] = 'NÃO PREENCHEU A FILIAL';
			}
			$data .= '<div class="empresa-single">
				<div class="box" href="'.INCLUDE_PATH.'perfil/'.$value['login'].'">';
			if($value['img_perfil'] == ''){
				$data .= '<img src="'.INCLUDE_PATH.'images/clientevazio.JPG">';
			}else{
				$data .= '<img src="'.INCLUDE_PATH.'uploads/'.$value['img_perfil'].'">';
			}
			$data .= '<h3>'.$value['nome'].'</h3>
					<div class="empresa-single-line"></div>
		 			<h2>'.$filial_dados_user['nome'].'</h2>
		 			<a href="'.INCLUDE_PATH.'perfil/'.$value['login'].'" style="display:block; margin-top:20px;">VER PERFIL</a>
				</div>
			</div>';
			}

		echo $data;
	}
	else if(isset($_POST['acao']) && $_POST['acao'] == 'listar-clientes-por-categoria'){
		$query = '';
		$categorias = [];
		$clientes_categoria = [];		
		foreach ($_POST as $key => $value) {
			if($key != 'acao' && $key != 'outro'){
				if($query == '')
					$query = ' WHERE `origem_tipo` = 0 AND (`categoria_id` = ?';
				else
					$query .= ' OR `categoria_id` = ?';
				$categorias[] = $key;
			}
			if($key == 'outro'){
				if($query == '')
					$query = ' WHERE `categoria_outra` != null';
				else
					$query .= ' OR `categoria_outra` != null';
				$categorias[] = $key;
			}
		}

		if($query != ''){
			$query .= ')';
		}

		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_post` $query GROUP BY `origem_id`");
		$sql->execute($categorias);

		if($query != '' && $sql->rowCount() > 0){
			$user_categoria = $sql->fetchAll();

			foreach ($user_categoria as $key => $value) {
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
				$sql->execute(array($value['origem_id']));
				$dados_user = $sql->fetch();
				$data .= '<div class="empresa-single">
					<div class="box" href="'.INCLUDE_PATH.'perfil/'.$dados_user['login'].'">';
				if($dados_user['img_perfil'] == ''){
					$data .= '<img src="'.INCLUDE_PATH.'images/clientevazio.JPG">';
				}else{
					$data .= '<img src="'.INCLUDE_PATH.'uploads/'.$dados_user['img_perfil'].'">';
				}
				$data .= '<h3>'.$dados_user['nome'].'</h3>
						<div class="empresa-single-line"></div>
			 			<h2>'.$dados_user['nome'].'</h2>
			 			<a href="'.INCLUDE_PATH.'perfil/'.$dados_user['login'].'" style="display:block; margin-top:20px;">VER PERFIL</a>
					</div>
				</div>';
			}
		} else if($query == ''){
			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user`");
			$sql->execute();
			$user_categoria = $sql->fetchAll();

			foreach ($user_categoria as $key => $value) {
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_filial` WHERE `id` = ?");
				$sql->execute(array($value['filial_id']));
				if($sql->rowCount() > 0){
					$filial_dados_user = $sql->fetch();
				}
				else{
					$filial_dados_user['nome'] = 'NÃO PREENCHEU A FILIAL';
				}
				$data .= '<div class="empresa-single">
					<div class="box" href="'.INCLUDE_PATH.'perfil/'.$value['login'].'">';
				if($value['img_perfil'] == ''){
					$data .= '<img src="'.INCLUDE_PATH.'images/clientevazio.JPG">';
				}else{
					$data .= '<img src="'.INCLUDE_PATH.'uploads/'.$value['img_perfil'].'">';
				}
				$data .= '<h3>'.$value['nome'].'</h3>
						<div class="empresa-single-line"></div>
			 			<h2>'.$filial_dados_user['nome'].'</h2>
			 			<a href="'.INCLUDE_PATH.'perfil/'.$value['login'].'" style="display:block; margin-top:20px;">VER PERFIL</a>
					</div>
				</div>';
			}
		}

		echo $data;
	}
 ?>