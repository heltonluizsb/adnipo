<?php
	date_default_timezone_set('America/Sao_Paulo');

	$autoload = function($class){
		include('../classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);
	include('../config.php');

	$data['sucesso'] = true;
	$data['mensagem'] = "";
	
	if(isset($_POST['tipo_acao']) && $_POST['tipo_acao'] == 'cadastrar_user'){

		$camposExistentes = '';

		$login = $_POST['login'];
		$email = $_POST['email'];
		$nome = $_POST['nome'];
		$tipo_login = $_POST['tipo_login'];
		$dado_final = '';
		if($tipo_login == 'fisico'){
			$dado_final = $_POST['cpf'];
			if(strlen($dado_final) != 14){
				$data['sucesso'] = false;
				$data['mensagem'] = ' O CPF está incorreto. O(s) Campo(s) a seguir estão vazios: ';
			}
		}
		else if($tipo_login == 'juridico'){
			$dado_final = $_POST['cnpj'];
			if(strlen($dado_final) != 18){
				$data['sucesso'] = false;
				$data['tamanho_campo'] = strlen($dado_final);
				$data['mensagem'] = ' O CNPJ está incorreto. O(s) Campo(s) a seguir estão vazios: ';
			}
		}

		$endereco = $_POST['endereco'];
		$carteirinha = $_POST['carteirinha'];
		$filial = $_POST['filial'];
		$nova_filial = $_POST['cadastrar_filial'];
		$slug_filial = padrao::generateSlug($nova_filial);
		$podeCadastrarFilial = false;
		$senha = $_POST['senha'];

		$usuarios = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `login` = ?");
		$usuarios->execute(array($login));

		if($login == ''){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* LOGIN';
			}
			else{
				$data['mensagem'] .= '<br>*  LOGIN';
			}
		}
		else{
			if($usuarios->rowCount() >= 1){
				$data['sucesso'] = false;
				$camposExistentes .= '<br>Já existe este LOGIN. ';
			}
		}

		$usuarios = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `email` = ?");
		$usuarios->execute(array($email));

		if($email == ''){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* E-MAIL';
			}
			else{
				$data['mensagem'] .= '<br>*  E-MAIL';
			}
		}
		else{
			if($usuarios->rowCount() >= 1){
				$data['sucesso'] = false;
				$camposExistentes .= '<br>Já existe este E-MAIL. ';
			}
		}

		if($nome == ''){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* NOME';
			}
			else{
				$data['mensagem'] .= '<br>*  NOME';
			}
		}

		$usuarios = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `cpf_cnpj` = ?");
		$usuarios->execute(array($dado_final));

		if($dado_final == ''){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* CPF ou CNPJ';
			}
			else{
				$data['mensagem'] .= '<br>*  CPF ou CNPJ';
			}
		}
		else{
			if($usuarios->rowCount() >= 1){
				$data['sucesso'] = false;
				$camposExistentes .= '<br>Já existe este CPF ou CNPJ. ';
			}
		}

		if($endereco == ''){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* ENDEREÇO';
			}
			else{
				$data['mensagem'] .= '<br>*  ENDEREÇO';
			}
		}

		$usuarios = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `carteirinha` = ?");
		$usuarios->execute(array($carteirinha));

		if($carteirinha == ''){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* CARTEIRINHA DE MEMBRO';
			}
			else{
				$data['mensagem'] .= '<br>*  CARTEIRINHA DE MEMBRO';
			}
		}
		else{
			if($usuarios->rowCount() >= 1){
				$usuarios = $usuarios->fetch();
				if($carteirinha != 'não tem' && $carteirinha != 'Não tem' && $carteirinha != 'nao tem' && $carteirinha != 'Nao tem' ){
					$data['sucesso'] = false;
					$camposExistentes .= '<br>Já existe esta CARTEIRINHA. ';
				}
			}
		}

		if($filial == ''){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* FILIAL';
			}
			else{
				$data['mensagem'] .= '<br>*  FILIAL';
			}
		}
		else if(($filial == 'Nova Filial')){
			if($nova_filial == ''){
				$data['sucesso'] = false;
				if($data['mensagem'] == ""){
					$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* CADASTRAR FILIAL';
				}
				else{
					$data['mensagem'] .= '<br>*  CADASTRAR FILIAL';
				}
			}
			else{
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_filial` WHERE `slug` = ?");
				$sql->execute(array($slug_filial));
				if($sql->rowCount() > 0){
					$data['sucesso'] = false;
					$camposExistentes .= '<br>Já existe esta FILIAL. ';
				}
				else{
					$podeCadastrarFilial = true;	
				}
			}
		}

		if($senha == ''){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* SENHA';
			}
			else{
				$data['mensagem'] .= '<br>*  SENHA';
			}
		}
		else if ($senha != $_POST['confirmasenha']){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' As senhas não conferem.';
			}
			else{
				$data['mensagem'] .= '<br> As senhas não conferem.';
			}
		}

		$data['mensagem'] .= $camposExistentes;

		if($data['sucesso']){
			if($podeCadastrarFilial){
				$sql = MySql::conectar()->prepare("INSERT INTO `tb_filial` VALUES (null,?,?)");
				$sql->execute(array($nova_filial,$slug_filial));
				$lastId = MySql::conectar()->lastInsertId();
				$filial = $lastId;
			}
			$confirmaEmail = uniqid();
			$subject = 'ADNIPO For Business - '.$nome.', Confirmação de e-mail';
			$body = '<html>
						<head>
							<title>Template de E-mail</title>
							<meta charset="utf-8" />
							<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
							<link href="'.INCLUDE_PATH.'css/confirmaemail.css" type="text/css" rel="stylesheet" />
						</head>
						<body>
							<div class="box">
								<img src="'.INCLUDE_PATH.'images/logo01_forbusiness.png">
								<h2>Favor confirmar seu e-mail no link abaixo:</h2>
								<a href="'.INCLUDE_PATH.'confirmaemail/'.$confirmaEmail.'">'.INCLUDE_PATH.'confirmaemail/'.$confirmaEmail.'</a>		
							</div>
						</body>
					</html>';
			$mail = new Mail($_POST, $subject, $body);
			$mail->addAdress($email,$nome);
			$mail->sendMail();
			$sql = MySql::conectar()->prepare("INSERT INTO `tb_user` VALUES (null,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$sql->execute(array($login,$email,$confirmaEmail,$nome,$tipo_login,$dado_final,$endereco,$carteirinha,$filial,$senha,'','',''));
			setcookie('user',$login,time()+(60*60*24*30),'/');
			setcookie('senha',$senha,time()+(60*60*24*30),'/');
			$data['login'] = $login;
			$data['mensagem'] = 'CADASTRO REALIZADO COM SUCESSO <br> Um e-mail foi enviado para você.
				Algumas funções só estarão disponíveis após a confirmação do e-mail';
		}
	}	
	
	die(json_encode($data));
 ?>