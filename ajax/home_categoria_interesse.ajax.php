<?php 
	date_default_timezone_set('America/Sao_Paulo');

	$autoload = function($class){
		include('../classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);
	include('../config.php');

	$data['sucesso'] = true;
	$data['mensagem'] = "";
	$data['dados'] = "";
	$data['estalogado'] = "";

	if(isset($_POST['acao']) && $_POST['acao'] == 'home_categoria_interesse'){
		$query = '';
		$categorias = [];
		$post_categoria = [];	
		$paginaAtual = $_POST['pgatual'];
		$porPagina = $_POST['porpg'];
		$estalogado = $_POST['estalogado'];
		$user_id = $_POST['user_id'];

		$tem_empresa = false;

		if($estalogado == 'true'){
			$estalogado = true;
			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.user` WHERE `user_id` = ?");
			$sql->execute(array($user_id));
			if($sql->rowCount() > 0){
				$tem_empresa = true;
				$empresas_user = $sql->fetchAll();
			}
		}else{
			$estalogado = false;
		}
		$data['estalogado'] = $estalogado;

		$usuario = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
		$usuario->execute(array($user_id));
		$usuario = $usuario->fetch();

		$paginaProxima = ($paginaAtual - 1)*$porPagina;

		foreach ($_POST as $key => $value) {
			if($key != 'acao' && $key != 'categoria_outra' && $key != 'pgatual' && $key != 'porpg' && $key != 'estalogado' && $key != 'user_id'){
				if($query == '')
					$query = ' WHERE `categoria_id` = ?';
				else
					$query .= ' OR `categoria_id` = ?';
				$categorias[] = $key;
			} else if($key == 'categoria_outra'){
				if($query == '')
					$query = " WHERE `categoria_outra` != ''";
				else
					$query .= " OR `categoria_outra` != ''";

			}
		}

		if($query == ''){
			$query .= 'WHERE `resposta_id` = ?';
		}else{
			$query .= 'AND `resposta_id` = ?';
		}

		$data['query'] = $query;
		$categorias[] = 0;

		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_post` $query ORDER BY `id` DESC LIMIT $paginaProxima,$porPagina");
		$sql->execute($categorias);
		$tb_post = $sql->fetchAll();

		$data['query'] = $query;
		$data['execute'] = $categorias;

		foreach ($tb_post as $key => $value) {
			$data['dados'] .= '<div class="box post-single">';
			if($value['origem_tipo'] == 0){
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
				$sql->execute(array($value['origem_id']));
				$tb_post_dados = $sql->fetch();
				$tb_post_dados_img = $tb_post_dados['img_perfil'];
				$data['dados'] .='<div class="post-single-div-perfil-img"><a href="'.INCLUDE_PATH.'perfil/'.$tb_post_dados['login'].'">';
				if($tb_post_dados_img == ''){
					$data['dados'] .='<img class="post-single-perfil-img" src="'.INCLUDE_PATH.'images/clientevazio.JPG">';
				} else{
					$data['dados'] .='<img class="post-single-perfil-img" src="'.INCLUDE_PATH.'/uploads/'.$tb_post_dados_img.'">';
				}
				$data['dados'] .='</a></div>';
			}else if($value['origem_tipo'] == 1){
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa` WHERE `id` = ?");
				$sql->execute(array($value['origem_id']));
				$tb_post_dados = $sql->fetch();
				$tb_post_dados_img = $tb_post_dados['imagem'];
				$data['dados'] .='<div class="post-single-div-perfil-img"><a href="'.INCLUDE_PATH.'perfil_empresa/'.$tb_post_dados['slug'].'">';
				if($tb_post_dados_img == ''){
					$data['dados'] .='<img class="post-single-perfil-img" src="'.INCLUDE_PATH.'images/empresa01.png">';
				} else{
					$data['dados'] .='<img class="post-single-perfil-img" src="'.INCLUDE_PATH.'/uploads/'.$tb_post_dados_img.'">';
				}
				$data['dados'] .='</a></div>';
			}
			$data['dados'] .= '<div class="post-single-div-conteudo">
					<div class="post-single-div-conteudo-nome">
						<div class="post-single-div-conteudo-nome-conteudo">';
			if($value['origem_tipo'] == 0){
				$data['dados'] .= '<a href="'.INCLUDE_PATH.'perfil/'.$tb_post_dados['login'].'"><p><b>'.$tb_post_dados['nome'].'</b></p><br></a>							
						</div>';
				if($estalogado && $usuario['id'] == $value['origem_id']){
					$data['dados'] .='<div class="post-single-div-conteudo-nome-editar">
								<div class="post-single-div-conteudo-nome-editar-simbolo" post_id="'.$value['id'].'">
									<p>...</p>
									<div class="box post-single-div-conteudo-nome-editar-simbolo-menu">
										<p post_id="'.$value['id'].'" class="editar">✎ Editar</p>
										<p post_id="'.$value['id'].'" class="excluir">X Excluir</p>
									</div>
								</div>
								<div class="post-single-div-conteudo-nome-editar-texto">
									<p post_id="'.$value['id'].'" class="editar">✎ Editar</p>
									<p post_id="'.$value['id'].'" class="excluir">X Excluir</p>								
								</div>
							</div>';
				}
			} else if($value['origem_tipo'] == 1){
				$data['dados'] .= '<a href="'.INCLUDE_PATH.'perfil_empresa/'.$tb_post_dados['slug'].'"><p><b>'.$tb_post_dados['nome'].'</b></p><br></a>							
					</div>';
				if($estalogado){
					$post_compara_user_empresa = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.user` WHERE `empresa_id` = ? AND `user_id` = ?");
					$post_compara_user_empresa->execute(array($value['origem_id'],$usuario['id']));
					if($post_compara_user_empresa->rowCount() > 0){
						$data['dados'] .='<div class="post-single-div-conteudo-nome-editar">
									<div class="post-single-div-conteudo-nome-editar-simbolo" post_id="'.$value['id'].'">
										<p>...</p>
										<div class="box post-single-div-conteudo-nome-editar-simbolo-menu">
											<p post_id="'.$value['id'].'" class="editar">✎ Editar</p>
											<p post_id="'.$value['id'].'" class="excluir">X Excluir</p>
										</div>
									</div>
									<div class="post-single-div-conteudo-nome-editar-texto">
										<p post_id="'.$value['id'].'" class="editar">✎ Editar</p>
										<p post_id="'.$value['id'].'" class="excluir">X Excluir</p>								
									</div>
								</div>';
					}
				}
			}
			$data['dados'] .= '</div>
					<p class="post-single-div-conteudo-texto p_post_'.$value['id'].'">'.nl2br($value['post']).'</p>
					<div class="post-single-div-conteudo-textarea textarea_post_'.$value['id'].'">
						<form method="post" action="'.INCLUDE_PATH.'ajax/home_post.ajax.php">
							<textarea name="textarea_post" class="autosize" value_old="'.$value['post'].'">'.$value['post'].'</textarea>
							<div>
								<input type="hidden" name="post_id" value="'.$value['id'].'">
								<input type="submit" name="acao_altera_post" value="ENVIAR">
								<input type="submit" name="acao_cancela_post" value="CANCELAR" post_id="'.$value['id'].'">
							</div>
						</form>
					</div>';
			if(($value['origem_tipo'] == 0)  || ($value['categoria_outra'] != '')) {
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` WHERE `id` = ?");
				$sql->execute(array($value['categoria_id']));
				$tb_post_categoria_dados = $sql->fetch();
				if($value['categoria_outra'] == '' && isset($tb_post_categoria_dados['nome'])){
					$data['dados'] .= '<br><p><b>Categoria Desejada: </b>'.$tb_post_categoria_dados['nome'].'</p><br>';
				}
				else if($value['categoria_outra'] != ''){
					$data['dados'] .= '<br><p><b>Categoria Desejada: </b>'.$value['categoria_outra'].'</p><br>';					
				}
			}
				$data['dados'] .= '</div><!-- post-single-div-conteudo -->
				<div class="post-single-div-img">';
			if($value['imagem'] != ''){ 
				$data['dados'] .= '<img src="'.INCLUDE_PATH.'/uploads/posts/'.$value['imagem'].'">';
			}
				$data['dados'] .= '</div>';

				if($estalogado){
					$data['dados'] .= '<div class="resposta-line"></div>
					<div class="post-resposta">
						<div class="post-postador">	
							<div class="resposta-post-postador-div1" post_id="'.$value['id'].'">
								<div class="post-postador-img post-postador-img_'.$value['id'].'">';
					if($usuario['img_perfil'] == ''){
						$data['dados'] .= '<img src="'.INCLUDE_PATH.'images/clientevazio.JPG">';
					} else{
						$data['dados'] .= '<img src="'.INCLUDE_PATH.'/uploads/'.$usuario['img_perfil'].'">';
					}	
					$data['dados'] .= '</div>
								<div>		
									 <p>▼</p>						
								</div>					
							</div>
							<div class="resposta-menu-escolher resposta-menu-escolher_post_'.$value['id'].'">
								<div class="box">
									<h3>Escolha quem irá postar</h3>
									<div class="menu-escolher-postador-line"></div>
									<a href="" class="escolha-postador" tipo="0" origem_id="'.$usuario['id'].'" img_src="'.$usuario['img_perfil'].'" post_id="'.$value['id'].'"><p>'.$usuario['nome'].'</p></a>
									<div class="menu-logado-line"></div>
									<div class="menu-escolher-postador-line"></div>';
					if($tem_empresa){
						foreach ($empresas_user as $key2 => $value2) {
							$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa` WHERE `id` = ?");
							$sql->execute(array($value2['empresa_id']));
							$empresa_user_dados = $sql->fetch();
							$data['dados'] .= '<a href="" class="escolha-postador" tipo="1" origem_id="'.$empresa_user_dados['id'].'" img_src="'.$empresa_user_dados['imagem'].'" post_id="'.$value['id'].'"><p>'.$empresa_user_dados['nome'].'</p></a>';
						}						 
					}								
					$data['dados'] .= '</div>
							</div>		
						</div>
						<form class="form-post-resposta form-post_'.$value['id'].'" method="post" enctype="multipart/form-data" action="'.INCLUDE_PATH.'ajax/home_post.ajax.php">
							<textarea class="autosize" name="post" placeholder="Escreva um comentário..." post_id="'.$value['id'].'"></textarea>
							<input type="hidden" name="post_id" value="'.$value['id'].'">
							<input type="hidden" name="origem_tipo" value="0">
							<input type="hidden" name="origem_id" value="'.$usuario['id'].'">
							<input type="hidden" name="resposta_id" value="0">
							<input type="hidden" name="acao_resposta">
						</form>		
					</div>
					<div class="post-resposta-lista post-resposta-lista_'.$value['id'].'">';

					$sql = MySql::conectar()->prepare("SELECT * FROM `tb_post` WHERE `resposta_id` = ? ORDER BY `id` DESC LIMIT 0,5");
					$sql->execute(array($value['id']));
					$tb_post_respostas = $sql->fetchAll();

					foreach ($tb_post_respostas as $key3 => $value3) {
						$data['dados'] .= '<div class="post-resposta-lista-single">
						<div class="post-resposta-lista-img">';
						if($value3['origem_tipo'] == 0){
							$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
							$sql->execute(array($value3['origem_id']));
							$tb_post_dados = $sql->fetch();
							$tb_post_dados_img = $tb_post_dados['img_perfil'];
							$data['dados'] .= '<div class="post-single-div-perfil-img"><a href="'.INCLUDE_PATH.'perfil/'.$tb_post_dados['login'].'">';
							if($tb_post_dados_img == ''){
								$data['dados'] .= '<img class="post-single-perfil-img" src="'.INCLUDE_PATH.'images/clientevazio.JPG">';
							} else{
								$data['dados'] .= '<img class="post-single-perfil-img" src="'.INCLUDE_PATH.'/uploads/'.$tb_post_dados_img.'">';
							}
							$data['dados'] .= '</a></div>';
						} 
						else if($value3['origem_tipo'] == 1){
							$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa` WHERE `id` = ?");
							$sql->execute(array($value3['origem_id']));
							$tb_post_dados = $sql->fetch();
							$tb_post_dados_img = $tb_post_dados['imagem'];
							$data['dados'] .= '<div class="post-single-div-perfil-img"><a href="'.INCLUDE_PATH.'perfil_empresa/'.$tb_post_dados['slug'].'">';
							if($tb_post_dados_img == ''){
								$data['dados'] .= '<img class="post-single-perfil-img" src="'.INCLUDE_PATH.'images/empresa01.png">';
							} else{
								$data['dados'] .= '<img class="post-single-perfil-img" src="'.INCLUDE_PATH.'/uploads/'.$tb_post_dados_img.'">';
							}
							$data['dados'] .= '</a></div>';
						}
						$data['dados'] .= '</div><!-- post-resposta-lista-img -->
						<div class="post-resposta-lista-conteudo">
							<div class="post-single-div-conteudo-nome">
								<div class="post-single-div-conteudo-nome-conteudo">';
						if($value3['origem_tipo'] == 0){
							$data['dados'] .= '<a href="'.INCLUDE_PATH.'perfil/'.$tb_post_dados['login'].'"><p><b>'.$tb_post_dados['nome'].'</b></p></a>
								</div>';
							if($estalogado && $usuario['id'] == $value3['origem_id']){
								$data['dados'] .='<div class="post-single-div-conteudo-nome-editar">
											<div class="post-single-div-conteudo-nome-editar-simbolo" post_id="'.$value['id'].'">
												<p>...</p>
												<div class="box post-single-div-conteudo-nome-editar-simbolo-menu">
													<p post_id="'.$value3['id'].'" class="editar">✎ Editar</p>
													<p post_id="'.$value3['id'].'" class="excluir">X Excluir</p>
												</div>
											</div>
											<div class="post-single-div-conteudo-nome-editar-texto">
												<p post_id="'.$value3['id'].'" class="editar">✎ Editar</p>
												<p post_id="'.$value3['id'].'" class="excluir">X Excluir</p>								
											</div>
										</div>';
							}
						} else if($value3['origem_tipo'] == 1){
							$data['dados'] .= '<a href="'.INCLUDE_PATH.'perfil_empresa/'.$tb_post_dados['slug'].'"><p><b>'.$tb_post_dados['nome'].'</b></p><br></a>	
							</div>';
							if($estalogado){
								$post_compara_user_empresa = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.user` WHERE `empresa_id` = ? AND `user_id` = ?");
								$post_compara_user_empresa->execute(array($value3['origem_id'],$usuario['id']));
								if($post_compara_user_empresa->rowCount() > 0){
									$data['dados'] .='<div class="post-single-div-conteudo-nome-editar">
												<div class="post-single-div-conteudo-nome-editar-simbolo" post_id="'.$value['id'].'">
													<p>...</p>
													<div class="box post-single-div-conteudo-nome-editar-simbolo-menu">
														<p post_id="'.$value3['id'].'" class="editar">✎ Editar</p>
														<p post_id="'.$value3['id'].'" class="excluir">X Excluir</p>
													</div>
												</div>
												<div class="post-single-div-conteudo-nome-editar-texto">
													<p post_id="'.$value3['id'].'" class="editar">✎ Editar</p>
													<p post_id="'.$value3['id'].'" class="excluir">X Excluir</p>								
												</div>
											</div>';
								}
							}
						}
						$data['dados'] .= '</div>
							<p class="post-single-div-conteudo-texto p_post_'.$value3['id'].'">'.nl2br($value3['post']).'</p>
							<div class="post-single-div-conteudo-textarea textarea_post_'.$value3['id'].'">
								<form method="post" action="'.INCLUDE_PATH.'ajax/home_post.ajax.php">
									<textarea name="textarea_post" class="autosize" value_old="'.$value3['post'].'">'.$value3['post'].'</textarea>
									<div>
										<input type="hidden" name="post_id" value="'.$value3['id'].'">
										<input type="submit" name="acao_altera_post" value="ENVIAR">
										<input type="submit" name="acao_cancela_post" value="CANCELAR" post_id="'.$value3['id'].'">
									</div>
								</form>
							</div>';
						if(($value3['origem_tipo'] == 0) || ($value3['categoria_outra'] != '')) {
							$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` WHERE `id` = ?");
							$sql->execute(array($value3['categoria_id']));
							$tb_post_categoria_dados = $sql->fetch(); 
							if($value3['categoria_outra'] == '' && isset($tb_post_categoria_dados['nome'])){
								$data['dados'] .= '<br><p><b>Categoria Desejada: </b> '.$tb_post_categoria_dados['nome'].'</p><br>';
							}else if($value3['categoria_outra'] != ''){
								$data['dados'] .= '<br><p><b>Categoria Desejada: </b> '.$value3['categoria_outra'].'</p><br>';
							}
						}
						$data['dados'] .= '</div><!-- post-resposta-lista-conteudo -->
						</div><!-- post-resposta-lista-single -->';
					}
					$data['dados'] .= '</div><!-- post-resposta-lista -->';
					if(count($tb_post_respostas) > 0){
						$data['dados'] .= '<div class="post-resposta-lista-paginacao">
						<p class="post-resposta-lista-paginacao_p_'.$value['id'].'" pagina_atual="1" por_pagina="5" pagina_proxima="0" post_id="'.$value['id'].'" fim_respostas="false">Ver Mais Comentários</p>
						</div><!-- post-resposta-lista-paginacao -->';
					}
				}
				$data['dados'] .= '</div><!-- box post-single -->';
		}
	}

	die(json_encode($data));
?>