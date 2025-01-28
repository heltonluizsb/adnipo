<?php 
	date_default_timezone_set('America/Sao_Paulo');

	$autoload = function($class){
		include('../classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);
	include('../config.php');

	$data['sucesso'] = true;
	$data['mensagem'] = "";

	if(isset($_POST['tipo_acao']) && $_POST['tipo_acao'] == 'cadastrar_empresa'){

		$camposExistentes = '';
		$categoriasExistentes = '';

		$nome = $_POST['nome'];
		$descricao = $_POST['descricao'];
		$endereco = $_POST['endereco'];
		$telefone = $_POST['telefone'];
		$site = $_POST['site'];
		$categoria_1 = $_POST['categoria_1'];
		$categoria_2 = $_POST['categoria_2'];
		$categoria_3 = $_POST['categoria_3'];
		$cadastrar_categoria1 = $_POST['cadastrar_categoria1'];
		$cadastrar_categoria2 = $_POST['cadastrar_categoria2'];
		$cadastrar_categoria3 = $_POST['cadastrar_categoria3'];
		$imagem = @$_FILES['imagem_nova'];
		$user_id = $_POST['user_id'];
		$nome_tabela = $_POST['nome_tabela'];

		$categoriaPreenchida = false;
		$novaCategoria1vazia = false;
		$novaCategoria2vazia = false;
		$novaCategoria3vazia = false;

		if($nome == ''){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* NOME';
			}
			else{
				$data['mensagem'] .= '<br>*  NOME';
			}
		}

		$slug = padrao::generateSlug($nome);

		$empresas = MySql::conectar()->prepare("SELECT * FROM `tb_empresa` WHERE `slug` = ?");
		$empresas->execute(array($slug));

		if($empresas->rowCount() > 0){
			$data['sucesso'] = false;
			$camposExistentes .= '<br>Já existe um NOME semelhante. Favor cadastrar outro. ';			
		}

		if($descricao == ''){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* DESCRIÇÃO';
			}
			else{
				$data['mensagem'] .= '<br>*  DESCRIÇÃO';
			}
		}

		if($categoria_1 == 'Selecione uma categoria' && $categoria_2 == 'Selecione uma categoria' && $categoria_3 == 'Selecione uma categoria'){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* Ao menos uma categoria deve ser preenchida';
			}
			else{
				$data['mensagem'] .= '<br>*  Ao menos uma categoria deve ser preenchida';
			}
		}

		if($categoria_1 == 'Nova Categoria'){
			if($cadastrar_categoria1 == ''){
				$novaCategoria1vazia = true;
			}
			else{
				$slug_categoria1 = padrao::generateSlug($cadastrar_categoria1);
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` WHERE `slug` = ?");
				$sql->execute(array($slug_categoria1));
				if($sql->rowCount() > 0){
					$data['sucesso'] = false;
					$categoriasExistentes .= '<br> O nome da Categoria 1 já existe.';
				}
			}
		}

		if($categoria_2 == 'Nova Categoria'){
			if($cadastrar_categoria2 == ''){
				$novaCategoria2vazia = true;
			}
			else{
				$slug_categoria2 = padrao::generateSlug($cadastrar_categoria2);
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` WHERE `slug` = ?");
				$sql->execute(array($slug_categoria2));
				if($sql->rowCount() > 0){
					$data['sucesso'] = false;
					$categoriasExistentes .= '<br> O nome da Categoria 2 já existe.';
				}
			}
		}

		if($categoria_3 == 'Nova Categoria'){
			if($cadastrar_categoria3 == ''){
				$novaCategoria3vazia = true;
			}
			else{
				$slug_categoria3 = padrao::generateSlug($cadastrar_categoria3);
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` WHERE `slug` = ?");
				$sql->execute(array($slug_categoria3));
				if($sql->rowCount() > 0){
					$data['sucesso'] = false;
					$categoriasExistentes .= '<br> O nome da Categoria 3 já existe.';
				}
			}
		}

		if($novaCategoria1vazia && $novaCategoria2vazia && $novaCategoria3vazia){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* Ao menos uma nova categoria deve ser preenchida';
			}
			else{
				$data['mensagem'] .= '<br>*  Ao menos uma nova categoria deve ser preenchida';
			}
		}

		if(isset($imagem['name']) && $imagem['name'] == ''){
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* Falta selecionar uma IMAGEM';
			}
			else{
				$data['mensagem'] .= '<br>*  Falta selecionar uma IMAGEM';
			}
		}
		else if(isset($imagem['name']) && $imagem['name'] != ''){
			if(padrao::imagemValida($imagem) == false){
				$data['sucesso'] = false;
				if($data['mensagem'] == ""){
					$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* Formato da Imagem incorreto ou a imagem é maior do que 1024KB';
				}
				else{
					$data['mensagem'] .= '<br>*  Formato da Imagem incorreto';
				}
			}
		}
		else{
			$data['sucesso'] = false;
			if($data['mensagem'] == ""){
				$data['mensagem'] = ' O(s) Campo(s) a seguir estão vazios: <br>* Falta selecionar uma IMAGEM';
			}
			else{
				$data['mensagem'] .= '<br>*  Não selecionou uma IMAGEM';
			}			
		}

		$data['mensagem'] .= $camposExistentes;
		$data['mensagem'] .= $categoriasExistentes;

		if($data['sucesso']){

			$imagem = padrao::uploadFile($imagem);

			$sql = MySql::conectar()->prepare("INSERT INTO `tb_empresa` VALUES (null,?,?,?,?,?,?,?,?)");
			$sql->execute(array($nome,$slug,$descricao,$endereco,$telefone,$site,$imagem,$user_id));
			$lastId_empresa = MySql::conectar()->lastInsertId();

			$sql = MySql::conectar()->prepare("INSERT INTO `tb_empresa.user` VALUES (null,?,?,?)");
			$sql->execute(array($lastId_empresa,$user_id,1));

			if($cadastrar_categoria1 != ''){				
				$sql = MySql::conectar()->prepare("INSERT INTO `tb_categoria.empresa` VALUES (null,?,?)");
				$sql->execute(array($cadastrar_categoria1,$slug_categoria1));
				$lastId_categoria = MySql::conectar()->lastInsertId();

				$sql = MySql::conectar()->prepare("INSERT INTO `tb_empresa.categoria` VALUES (null,?,?)");
				$sql->execute(array($lastId_empresa,$lastId_categoria));
			}
			else{
				$sql = MySql::conectar()->prepare("INSERT INTO `tb_empresa.categoria` VALUES (null,?,?)");
				$sql->execute(array($lastId_empresa,$categoria_1));
			}

			if($cadastrar_categoria2 != ''){				
				$sql = MySql::conectar()->prepare("INSERT INTO `tb_categoria.empresa` VALUES (null,?,?)");
				$sql->execute(array($cadastrar_categoria2,$slug_categoria2));
				$lastId_categoria = MySql::conectar()->lastInsertId();

				$sql = MySql::conectar()->prepare("INSERT INTO `tb_empresa.categoria` VALUES (null,?,?)");
				$sql->execute(array($lastId_empresa,$lastId_categoria));
			}
			else{
				$sql = MySql::conectar()->prepare("INSERT INTO `tb_empresa.categoria` VALUES (null,?,?)");
				$sql->execute(array($lastId_empresa,$categoria_2));
			}

			if($cadastrar_categoria3 != ''){				
				$sql = MySql::conectar()->prepare("INSERT INTO `tb_categoria.empresa` VALUES (null,?,?)");
				$sql->execute(array($cadastrar_categoria3,$slug_categoria3));
				$lastId_categoria = MySql::conectar()->lastInsertId();

				$sql = MySql::conectar()->prepare("INSERT INTO `tb_empresa.categoria` VALUES (null,?,?)");
				$sql->execute(array($lastId_empresa,$lastId_categoria));
			}
			else{
				$sql = MySql::conectar()->prepare("INSERT INTO `tb_empresa.categoria` VALUES (null,?,?)");
				$sql->execute(array($lastId_empresa,$categoria_3));
			}

			$data['slug'] = $slug;
			$data['mensagem'] = 'CADASTRO DA EMPRESA REALIZADO COM SUCESSO!';
		}
	}
	
	die(json_encode($data));

?>