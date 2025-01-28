<?php 
	
 class padrao
 {


 	public static function logado(){
 		return isset($_SESSION['paineladmin_login']) ? true : false;
 	}

 	public static function logout(){
 		session_destroy();
 		setcookie('lembrar','true',time()-1,'/');
 		header('Location:'.INCLUDE_PATH_PAINEL);
 	}

 	public static function carregarPagina(){
 		if(isset($_GET['url'])){
 			$url = explode('/',$_GET['url']);
 			if(file_exists('pages/'.$url[0].'.php')){
 				include('pages/'.$url[0].'.php');
 			}else{
				header('Location:'.INCLUDE_PATH_PANEL);
 			}
 		} else{
 			include('pages/home.php');
 		}
 	}

 	public static function paginaSelecionada($url, $page){
 		if($url == $page){
 			return 'pagina-selecionada';
 		}
 	}

 	public static function imagemPaginaSelecionada($url, $page){
 		if($url == $page){
 			if($page == 'home'){
 				return  INCLUDE_PATH.'images/casa_tamanho_01_red01.png';
 			}
 			else if($page == 'empresas'){
 				return  INCLUDE_PATH.'images/empresa01_tamanho_01_red01.png';
 			}
 			else if($page == 'clientes'){
 				return  INCLUDE_PATH.'images/clientes01_tamanho_01_red01.png';
 			}
 		}
 		else{
 			if($page == 'home'){
 				return  INCLUDE_PATH.'images/casa_tamanho_01_cinza01.png';
 			}
 			else if($page == 'empresas'){
 				return  INCLUDE_PATH.'images/empresa01_tamanho_01_cinza01.png';
 			}
 			else if($page == 'clientes'){
 				return  INCLUDE_PATH.'images/clientes01_tamanho_01_cinza01.png';
 			}
 		}
 	}

 	public static function alert($tipo,$mensagem){
 		if($tipo == 'sucesso'){
 			echo '<div class="box-alert sucesso"><img src="'.INCLUDE_PATH.'images/check01_tamanho_01_white.png"> <p>'.$mensagem.'</p></div>';
 		}else if ($tipo == 'erro'){
 			echo '<div class="box-alert erro"><img src="'.INCLUDE_PATH.'images/close01_tamanho_01_white.png"> <p>'.$mensagem.'</p></div>';
 		}else if ($tipo == 'atencao'){
 			echo '<div class="box-alert atencao"><img src="'.INCLUDE_PATH.'images/atencao01_tamanho_01_white.png"> <p>'.$mensagem.'</p></div>';
 		}
 	}

 	public static function imagemValida($imagem){
 		if($imagem['type'] == 'image/jpeg' ||
 			$imagem['type'] == 'image/jpg' ||
 			$imagem['type'] == 'image/png'){
 			$tamanho = intval($imagem['size']/1024);
 			if($tamanho < 900){
 				return true;
 			} else{
 				return false;
 			}
 		}
 		else{
 			return false;
 		}
 	}

 	public static function uploadFile($file,$local = null){
 		$formatoarquivo = explode('.',$file['name']);
 		$imagemnome = uniqid().'.'.$formatoarquivo[count($formatoarquivo) - 1];
 		if($local == null){
	 		if(move_uploaded_file($file['tmp_name'],BASE_DIR.'/uploads/'.$imagemnome)){
	 			return $imagemnome;
	 		}
	 		else{
	 			return false;
	 		}
 		}
 		else{
	 		if(move_uploaded_file($file['tmp_name'],BASE_DIR.'/uploads/'.$local.'/'.$imagemnome)){
	 			return $imagemnome;
	 		}
	 		else{
	 			return false;
	 		}
 		}
 	}

 	public static function deleteFile($file){
 		unlink(BASE_DIR.'/uploads/'.$file);
 	}

 	public static function generateSlug($str){
 		$str = mb_strtolower($str);
 		$str = preg_replace('/(â|á|ã|à)/','a',$str);
 		$str = preg_replace('/(ê|é|è)/','e',$str);
 		$str = preg_replace('/(î|í|ì)/','i',$str);
 		$str = preg_replace('/(ô|ó|õ|ò)/', 'o',$str);
 		$str = preg_replace('/(û|ú|ù)/', 'u',$str);
 		$str = preg_replace('/(_|\/|!|\?)/','',$str);
 		$str = preg_replace('/( )/','-',$str);
 		$str = preg_replace('/(ç)/','c',$str);
 		$str = preg_replace('/(-[-]{1,})/','-',$str);
 		$str = preg_replace('/(,)/','-',$str);
 		$str = strtolower($str);
 		return $str;
 	}

 	public static function insert($arr){
 		$certo = true;
 		$nome_tabela = $arr['nome_tabela'];
 		$query = "INSERT INTO `$nome_tabela` VALUES (null";
 		foreach ($arr as $key => $value) {
 			$nome = $key;
 			$valor = $value;
 			if($nome == 'acao' || $nome == 'nome_tabela' || $nome == 'acao_cadastro_login'){
 				continue;
 			}
 			if($value == ''){
 				$certo = false;
 				break;
 			}
 			$query.=",?";
 			$parametros[] = $value;
 		}

 		$query.=")";
 		if($certo == true){
	 		$sql = MySql::conectar()->prepare($query);
	 		$sql->execute($parametros);
 		}
 		return $certo;
 	}

 	public static function update($arr){
 		$certo = true;
 		$first = false;
 		$nome_tabela = $arr['nome_tabela'];
 		$query = "UPDATE `$nome_tabela` SET ";
 		foreach ($arr as $key => $value) {
 			$nome = $key;
 			$valor = $value;
 			if($nome == 'acao' || $nome == 'nome_tabela' || $nome == 'id'){
 				continue;
 			}
 			if($value == ''){
 				$certo = false;
 				break;
 			}
 			if($first == false){
 				$first = true;
 				$query.=$nome."=? ";
 			}
 			else{
 				$query.=",".$nome."=? ";
 			}
 			$parametros[] = $value;
 		}

 		if($certo == true){
 			$parametros[] = $arr['id'];
 			$query.="WHERE id=?";
	 		$sql = MySql::conectar()->prepare($query);
	 		$sql->execute($parametros);
 		}
 		return $certo;
 	}

 	public static function redirecionaPagina($page){
 		return '<script> window.location.href = "'.INCLUDE_PATH.$page.'"</script>';
 	}

 	public static function adicionaScript($url,$paginaCorreta,$script){
		if((file_exists('pages/'.$url.'.php')) && $paginaCorreta == $url){
			echo '<script src="'.INCLUDE_PATH.'js/'.$script.'.js"></script>';
		}
 	}
 }
 ?>