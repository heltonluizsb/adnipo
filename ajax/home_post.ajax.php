<?php 
	date_default_timezone_set('America/Sao_Paulo');

	$autoload = function($class){
		include('../classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);
	include('../config.php');

	$data['sucesso'] = true;
	$data['mensagem'] = "";
	$data['conteudo_mobile'] = "";

	if(isset($_POST['acao_post'])){
		$origem_tipo = $_POST['origem_tipo'];
		$origem_id = $_POST['origem_id'];
		$post = $_POST['post'];
		$categoria_id = $_POST['categoria_id'];
		$categoria_outra = $_POST['categoria_outra'];
		$imagem = @$_FILES['imagem'];
		$resposta_id = $_POST['resposta_id'];

		if(isset($imagem['name']) && $imagem['name'] != ''){
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

		if($data['sucesso']){
			if($imagem != ''){
				$imagem = padrao::uploadFile($imagem,'posts');
			}
			else{
				$imagem = '';
			}
			$sql = MySql::conectar()->prepare("INSERT INTO `tb_post` VALUES(null,?,?,?,?,?,?,?,null)");
			$sql->execute(array($origem_tipo,$origem_id,$post,$categoria_id,$categoria_outra,$imagem,$resposta_id));
		}
	}
	else if(isset($_POST['acao_altera_post'])){
		$post_id = $_POST['post_id'];
		$textarea_post = $_POST['textarea_post'];

		$sql = MySql::conectar()->prepare("UPDATE `tb_post` SET `post` = ? WHERE `id` = ?");
		$sql->execute(array($textarea_post,$post_id));
	}
	else if(isset($_POST['acao']) && $_POST['acao'] == 'acao_exclui_post'){
		$post_id = $_POST['post_id'];

		$sql = MySql::conectar()->prepare("DELETE FROM `tb_post` WHERE `id` = ?");
		$sql->execute(array($post_id));
	}
	else if(isset($_POST['form_post'])){
		$form_post = $_POST['form_post'];
		$data['mensagem'] = 'Entrou aqui';
		$data['post_id'] = $form_post['post_id'];

		if(isset($_POST['conteudo_mobile'])){
			if($form_post['post'] != ''){
				$sql = MySql::conectar()->prepare("INSERT INTO `tb_post` VALUES(null,?,?,?,?,?,?,?,null)");
				$sql->execute(array($form_post['origem_tipo'],$form_post['origem_id'],$form_post['post'],'','','',$form_post['post_id']));
			}
		}
		else{
			$sql = MySql::conectar()->prepare("INSERT INTO `tb_post` VALUES(null,?,?,?,?,?,?,?,null)");
			$sql->execute(array($form_post['origem_tipo'],$form_post['origem_id'],$form_post['post'],'','','',$form_post['post_id']));			
		}
	}

	die(json_encode($data));
 ?>