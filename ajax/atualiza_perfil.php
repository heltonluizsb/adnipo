<?php 
	date_default_timezone_set('America/Sao_Paulo');

	$autoload = function($class){
		include('../classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);
	include('../config.php');

	$data['sucesso'] = true;
	$data['mensagem'] = "";

	if(isset($_POST['acao']) && $_POST['acao'] == 'editar-imagem-perfil'){
		$imagem_atual = $_POST['imagem_atual'];
		$imagem = $_FILES['imagem_nova'];

		if(padrao::imagemValida($imagem) == false){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* Formato da Imagem incorreto ou Imagem muito Grande';
			}
			else{
				$data['mensagem'] .= '<br>*  Formato da Imagem incorreto';
			}			
		}

		if($data['sucesso']){
			padrao::deleteFile($imagem_atual);
			$imagem = padrao::uploadFile($imagem);
			$data['imagem'] = $imagem;
			$nome_tabela = $_POST['nome_tabela'];
			$user_id = $_POST['user_id'];
			$data['user_login'] = $_POST['user_login'];
			$sql = MySql::conectar()->prepare("UPDATE `tb_user` SET `img_perfil` = ? WHERE id = ?");
			$sql->execute(array($imagem,$user_id));
		}
	}
	else if(isset($_POST['acao']) && $_POST['acao'] == 'editar-nome-perfil'){
		$nome = $_POST['nome'];

		if($nome == ''){
			$data['sucesso'] = false;
			$data['mensagem'] .= ' Seu nome não pode estar em branco';
		}

		if($data['sucesso']){
			$nome_tabela = $_POST['nome_tabela'];
			$user_id = $_POST['user_id'];
			$data['user_login'] = $_POST['user_login'];
			$sql = MySql::conectar()->prepare("UPDATE `tb_user` SET `nome` = ? WHERE id = ?");
			$sql->execute(array($nome,$user_id));			
		}
	}
	else if(isset($_POST['acao']) && $_POST['acao'] == 'editar-perfil'){
		$nome_tabela = $_POST['nome_tabela'];
		$user_id = $_POST['user_id'];
		$data['user_login'] = $_POST['user_login'];
		$email_antigo = $_POST['email_antigo'];
		$nome = $_POST['nome'];

		$camposExistentes = '';

		$email = $_POST['email'];

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
		$filial_id = $_POST['filial'];

		$descricao = $_POST['descricao'];

		$usuarios = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `email` = ? AND `id` != ?");
		$usuarios->execute(array($email, $user_id));

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

		$usuarios = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `cpf_cnpj` = ? AND `id` != ?");
		$usuarios->execute(array($dado_final, $user_id));

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

		$usuarios = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `carteirinha` = ? AND `id` != ?");
		$usuarios->execute(array($carteirinha, $user_id));

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
				$data['sucesso'] = false;
				$camposExistentes .= '<br>Já existe esta CARTEIRINHA. ';
			}
		}

		if($filial_id == ''){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* FILIAL';
			}
			else{
				$data['mensagem'] .= '<br>*  FILIAL';
			}
		}

		$data['mensagem'] .= $camposExistentes;

		if($data['sucesso']){
			if($email != $email_antigo){
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
				$sql = MySql::conectar()->prepare("UPDATE `tb_user` SET `confirma_email` = ? WHERE id = ?");
				$sql->execute(array($confirmaEmail,$user_id));
			}
			$sql = MySql::conectar()->prepare("UPDATE `tb_user` SET `email` = ?, `tipo_login` = ?, `cpf_cnpj` = ?, `endereco` = ?, `carteirinha` = ?, `filial_id` = ?, `descricao` = ? WHERE id = ?");
			$sql->execute(array($email,$tipo_login,$dado_final,$endereco,$carteirinha,$filial_id,$descricao,$user_id));
			$data['mensagem'] = "Seu Perfil foi alterado com sucesso";
		}
	}
	else if(isset($_POST['acao']) && $_POST['acao'] == 'alterar-senha'){
		$senha_antiga = $_POST['senha_antiga'];
		$senha_nova = $_POST['senha_nova'];
		$confirma_senha = $_POST['confirma_senha'];
		$data['user_login'] = $_POST['user_login'];

		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE login = ? AND senha = ?");
		$sql->execute(array($data['user_login'],$senha_antiga));

		if($sql->rowCount() == 0){
			$data['sucesso'] = false;
			$data['mensagem'] .= '<br> Senha Incorreta.';
		}

		if($senha_nova != $confirma_senha){
			$data['sucesso'] = false;
			$data['mensagem'] .= '<br> As senhas não conferem.';
		}

		if($data['sucesso']){

			$sql = MySql::conectar()->prepare("UPDATE `tb_user` SET `senha` = ? WHERE id = ?");
			$sql->execute(array($senha_nova,$_POST['user_id']));

			$data['mensagem'] .= 'Senha alterada com sucesso!';
		}
	}

	die(json_encode($data));
 ?>