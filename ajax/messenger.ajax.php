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

	if(isset($_POST['acao']) && $_POST['acao'] == 'insere_mensagem'){
		$user_id = $_POST['user_id'];
		$remetente_id = $_POST['remetente_id'];
		$mensagem = $_POST['mensagem'];

		$sql = MySql::conectar()->prepare("INSERT INTO `tb_mensagem` VALUES(null,?,?,?)");
		$sql->execute(array($user_id,$remetente_id,$mensagem));

		$_SESSION['lastIdChat'] = MySql::conectar()->lastInsertId();
	}
	else if(isset($_POST['acao']) && $_POST['acao'] == 'pega_mensagens'){
		$lastId = $_SESSION['lastIdChat'];
		$user_id = $_POST['user_id'];
		$remetente_id = $_POST['remetente_id'];

		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_mensagem` WHERE id > ? AND `remetente_id` = ? AND `destinatario_id` = ?");
		$sql->execute(array($lastId,$remetente_id,$user_id));
		$data['lastId'] = $lastId;
		$mensagens = $sql->fetchAll();
		$mensagens = array_reverse($mensagens);
		foreach ($mensagens as $key => $value) {
			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
			$sql->execute(array($remetente_id));
			$remetente_dados = $sql->fetch();

			$data['mensagem'] .= '<div class="chat-single-other">
				<div class="chat-single-img">';
			if($remetente_dados['img_perfil'] == ''){
			$data['mensagem'] .= '<img src="'.INCLUDE_PATH.'images/clientevazio.JPG">';
			}else{
			$data['mensagem'] .= '<img src="'.INCLUDE_PATH.'uploads/'.$remetente_dados['img_perfil'].'">';
			}
			$data['mensagem'] .= '</div>
				<div class="chat-single-text">
					<p>'.$value['mensagem'].'</p>
				</div>
			</div>';

			$_SESSION['lastIdChat'] = $value['id'];
		}
	}
	else if(isset($_POST['acao']) && $_POST['acao'] == 'pega_todas_mensagens_por_user'){
		$user_id = $_POST['user_id'];
		$remetente_id = $_POST['remetente_id'];

		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_mensagem` WHERE (`remetente_id` = ? AND `destinatario_id` = ?) OR (`remetente_id` = ? AND `destinatario_id` = ?) ORDER BY `id` DESC");
		$sql->execute(array($user_id,$remetente_id,$remetente_id,$user_id));
		$mensagens = $sql->fetchAll();
		$mensagens = array_reverse($mensagens);
		foreach ($mensagens as $key => $value) {
			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
			$sql->execute(array($remetente_id));
			$remetente_dados = $sql->fetch();

			if($value['remetente_id'] != $user_id){
				$data['mensagem'] .= '<div class="chat-single-other">
					<div class="chat-single-img">';
				if($remetente_dados['img_perfil'] == ''){
				$data['mensagem'] .= '<img src="'.INCLUDE_PATH.'images/clientevazio.JPG">';
				}else{
				$data['mensagem'] .= '<img src="'.INCLUDE_PATH.'uploads/'.$remetente_dados['img_perfil'].'">';
				}
				$data['mensagem'] .= '</div>
					<div class="chat-single-text">
						<p>'.$value['mensagem'].'</p>
					</div>
				</div>';
			} else {
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
				$sql->execute(array($user_id));
				$user_dados = $sql->fetch();
				$data['mensagem'] .= '<div class="chat-single-me">
					<div class="chat-single-text">
						<p>'.$value['mensagem'].'</p>
					</div>
					<div class="chat-single-img">';
				if($user_dados['img_perfil'] == ''){
				$data['mensagem'] .= '<img src="'.INCLUDE_PATH.'images/clientevazio.JPG">';
				}else{
				$data['mensagem'] .= '<img src="'.INCLUDE_PATH.'uploads/'.$user_dados['img_perfil'].'">';
				}
				$data['mensagem'] .= '</div>
				</div>';				
			}
		}
	}

	if($isjson){
		die(json_encode($data));
	}
 ?>