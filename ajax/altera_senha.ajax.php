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

	if(isset($_POST['acao']) && $_POST['acao'] == 'altera_senha'){
		$user_id = $_POST['user_id'];
		$nome = $_POST['user_name'];
		$email = $_POST['user_email'];
		$data['email'] = $_POST;

		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
		$sql->execute(array($user_id));

		if($sql->rowCount() == 0){
			$data['sucesso'] = false;
			$data['mensagem'] = " Usuário não encontrado";

		}
		else if($sql->rowCount() > 0){
			$user_dados = $sql->fetch();
			if($user_dados['confirma_email'] != 'confirmado'){
			$data['sucesso'] = false;
			$data['mensagem'] .= " Seu e-mail ainda não foi confirmado. Acesse seu e-mail e confirme seu cadastro.";				
			}
		}


		if($data['sucesso']){
			$confirmaEmail = uniqid();
			$sql = MySql::conectar()->prepare("UPDATE `tb_user` SET `troca_senha` = ? WHERE `id` = ?");
			$sql->execute(array($confirmaEmail, $user_id));
			$subject = 'ADNIPO For Business - '.$nome.', Confirmação de e-mail';
			$body = '<html>
						<head>
							<title>Template de E-mail</title>
							<meta charset="utf-8" />
							<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
						</head>
						<style>
							.box > img{
								width: 300px;
							}

							.box{
								background-color: white;
								-webkit-box-shadow: 0px 1px 5px 0px rgba(0,0,0,0.3);
								-moz-box-shadow: 0px 1px 5px 0px rgba(0,0,0,0.3);
								box-shadow: 0px 1px 5px 0px rgba(0,0,0,0.3);
								padding: 10px;
								border-radius: 10px;
								width: 600px;
								position: absolute;
								top: 50%;
								left: 50%;
								transform: translate(-50%, -50%);
							}

							body .box > a{
								display: inline-block;
								margin: 30px 0;
							}
						</style>
						<body>
							<div class="box">
								<img src="'.INCLUDE_PATH.'images/logo01_forbusiness.png">
								<h2>Favor confirmar seu e-mail no link abaixo:</h2>
								<a href="'.INCLUDE_PATH.'altera_senha/'.$confirmaEmail.'">'.INCLUDE_PATH.'altera_senha/'.$confirmaEmail.'</a>		
							</div>
						</body>
					</html>';
			$mail = new Mail($_POST, $subject, $body);
			$mail->addAdress($email,$nome);
			$mail->sendMail();
			$data['mensagem'] = "Um e-mail foi enviado para você com instrução de alteração de senha.";
		}
	} else if(isset($_POST['acao']) && $_POST['acao'] == 'alterando_senha'){
		if($_POST['nova_senha'] == ''){
			$data['sucesso'] = false;
			$data['mensagem'] .= ' Senha em branco.';
		}else if($_POST['nova_senha'] != $_POST['confirma_senha']){
			$data['sucesso'] = false;
			$data['mensagem'] .= ' Senhas não conferem.';
		}
		else{
			$sql = MySql::conectar()->prepare("UPDATE `tb_user` SET `senha` = ?, `troca_senha` = ? WHERE `id` = ?");
			$sql->execute(array($_POST['nova_senha'],'',$_POST['user_id']));
			$data['mensagem'] .= ' Senha alterada com sucesso. Em Breve você será direcionado(a) para a página principal.';
			$data['user_login'] = $_POST['user_login'];
		}
	} else if(isset($_POST['acao']) && $_POST['acao'] == 'alterando_senha_pagina_login'){

		if(isset($_POST['email']) && $_POST['email'] != ''){
			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `email` = ?");
			$sql->execute(array($_POST['email']));
			if($sql->rowCount() == 0){
				$data['sucesso'] = false;
				$data['mensagem'] .= "E-mail Não encontrado.";
			}
			else if($sql->rowCount() > 0){
				$user_dados = $sql->fetch();
				$user_id = $user_dados['id'];
				$nome = $user_dados['nome'];
				$email = $user_dados['email'];

				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
				$sql->execute(array($user_id));

				if($sql->rowCount() == 0){
					$data['sucesso'] = false;
					$data['mensagem'] = " Usuário não encontrado";

				}
				else if($sql->rowCount() > 0){
					$user_dados = $sql->fetch();
					if($user_dados['confirma_email'] != 'confirmado'){
					$data['sucesso'] = false;
					$data['mensagem'] .= " Seu e-mail ainda não foi confirmado. Acesse seu e-mail e confirme seu cadastro.";				
					}
				}


				if($data['sucesso']){
					$confirmaEmail = uniqid();
					$sql = MySql::conectar()->prepare("UPDATE `tb_user` SET `troca_senha` = ? WHERE `id` = ?");
					$sql->execute(array($confirmaEmail, $user_id));
					$subject = 'ADNIPO For Business - '.$nome.', Confirmação de e-mail';
					$body = '<html>
								<head>
									<title>Template de E-mail</title>
									<meta charset="utf-8" />
									<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
								</head>
								<style>
									.box > img{
										width: 300px;
									}

									.box{
										background-color: white;
										-webkit-box-shadow: 0px 1px 5px 0px rgba(0,0,0,0.3);
										-moz-box-shadow: 0px 1px 5px 0px rgba(0,0,0,0.3);
										box-shadow: 0px 1px 5px 0px rgba(0,0,0,0.3);
										padding: 10px;
										border-radius: 10px;
										width: 600px;
										position: absolute;
										top: 50%;
										left: 50%;
										transform: translate(-50%, -50%);
									}

									body .box > a{
										display: inline-block;
										margin: 30px 0;
									}
								</style>
								<body>
									<div class="box">
										<img src="'.INCLUDE_PATH.'images/logo01_forbusiness.png">
										<h2>Favor confirmar seu e-mail no link abaixo:</h2>
										<a href="'.INCLUDE_PATH.'altera_senha/'.$confirmaEmail.'">'.INCLUDE_PATH.'altera_senha/'.$confirmaEmail.'</a>		
									</div>
								</body>
							</html>';
					$mail = new Mail($_POST, $subject, $body);
					$mail->addAdress($email,$nome);
					$mail->sendMail();
					$data['mensagem'] = "Um e-mail foi enviado para você com instrução de alteração de senha.";
				}
			}
		} else if(isset($_POST['email']) && $_POST['email'] == ''){
				$data['sucesso'] = false;
				$data['mensagem'] .= "E-mail Em Branco.";
		}
	} 

	if($isjson){
		die(json_encode($data));
	}
 ?>