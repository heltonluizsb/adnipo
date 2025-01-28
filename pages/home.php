<?php 
	
	$estalogado = false;
	$perfil = null;
	$tem_empresa = false;
	if(isset($_COOKIE['user'])){
		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `login` = ?");
		$sql->execute(array($_COOKIE['user']));
		if($sql->rowCount() > 0){
			$usuario = $sql->fetch();
			$estalogado = true;	
			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.user` WHERE `user_id` = ?");
			$sql->execute(array($usuario['id']));
			if($sql->rowCount() > 0){
				$tem_empresa = true;
				$empresas_user = $sql->fetchAll();
			}
		}
	}

	$paginaAtual = 1;
	$porPagina = 5;
	$paginaProxima = ($paginaAtual - 1)*$porPagina;

	$sql = MySql::conectar()->prepare("SELECT * FROM `tb_post` WHERE `resposta_id` = ? ORDER BY `id` DESC LIMIT $paginaProxima,$porPagina");
	$sql->execute(array(0));
	if($sql->rowCount() > 0){
		$tb_post = $sql->fetchAll();
	}
 ?>
<listagem pgatual="<?php echo $paginaAtual ?>" porpg="<?php echo $porPagina ?>">
<estalogado  condicao="<?php echo $estalogado; ?>" user_id="<?php echo @$usuario['id']; ?>">
<section class="section-home">
	<div class="section-left">
		<img class="section-left-mobile-close" src="<?php INCLUDE_PATH ?>images/close01_tamanho_01.png">
		<div class="section-left-menu-desktop">
			<a href="<?php echo INCLUDE_PATH; ?>home"><div class="header-menu-desktop-single <?php echo padrao::paginaSelecionada($url,'home')?>">
				<img src="<?php echo padrao::imagemPaginaSelecionada($url,'home'); ?>">
				<div class="header-menu-desktop-titulo"><p>Home</p></div>
			</div></a>
			<a href="<?php echo INCLUDE_PATH; ?>empresas"><div class="header-menu-desktop-single <?php echo padrao::paginaSelecionada($url,'empresas')?>">
				<img src="<?php echo padrao::imagemPaginaSelecionada($url,'empresas'); ?>">
				<div class="header-menu-desktop-titulo"><p>Empresas</p></div>
			</div></a>
			<a href="<?php echo INCLUDE_PATH; ?>clientes"><div class="header-menu-desktop-single <?php echo padrao::paginaSelecionada($url,'clientes')?>">
				<img src="<?php echo padrao::imagemPaginaSelecionada($url,'clientes'); ?>">
				<div class="header-menu-desktop-titulo"><p>Clientes</p></div>
			</div></a>
		</div>
		<div class="box">
			<img src="<?php INCLUDE_PATH ?>images/empresa01_tamanho_01.png">
			<h2> Categorias</h2>
			<form class="categorias">
				<?php 
					$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` ORDER BY `nome`");
					$sql->execute();
					$listaCategoria = $sql->fetchAll();
					foreach ($listaCategoria as $key => $value) { ?>
				<div>
					<input type="checkbox" name="<?php echo $value['id'] ?>" goto="empresas">
					<label><?php echo strtoupper($value['nome']);  ?></label>
				</div>
				<?php } ?>
			</form>			
		</div><!-- box -->
		<div class="box categoria-interesse">
			<div>
				<div>
					<img src="<?php INCLUDE_PATH ?>images/clientes01_tamanho_01.png">
					
				</div>
				<div>
					<h2> Filtrar por Categoria de Interesse do Cliente</h2>					
				</div>
			</div>
			<form class="categorias-interesse" method="post" action="<?php echo INCLUDE_PATH ?>ajax/home_categoria_interesse.ajax.php">
				<?php 
					$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` ORDER BY `nome`");
					$sql->execute();
					$listaCategoria = $sql->fetchAll();
					foreach ($listaCategoria as $key => $value) { ?>
				<div>
					<input type="checkbox" name="<?php echo $value['id'] ?>" goto="empresas">
					<label><?php echo strtoupper($value['nome']);  ?></label>
				</div>
				<?php } ?>
				<div>
					<input type="checkbox" name="categoria_outra" goto="empresas">
					<label>Outras Categorias</label>
				</div>
			</form>			
		</div><!-- box -->
	</div><!-- section-left -->
	<div class="section-center">
		<div> <!-- INICIO parte de escrever o post -->
			<?php if($estalogado){?>
			<div class="box box-post">
				<div class="post-postador">	
					<div class="post-postador-div1">
						<div class="post-postador-img">						
						<?php if($usuario['img_perfil'] == ''){ ?>
							<img src="<?php echo INCLUDE_PATH ?>images/clientevazio.JPG">
						<?php } else{ ?>
							<img src="<?php echo INCLUDE_PATH ?>/uploads/<?php echo $usuario['img_perfil'] ?>">
						<?php } ?>	
							
						</div>
						<div>		
							 <p>▼</p>						
						</div>					
					</div>
					<div class="menu-escolher-postador">
						<div class="box">
							<h3>Escolha quem irá postar</h3>
							<div class="menu-escolher-postador-line"></div>
							<a href="" class="escolha-postador" tipo="0" origem_id="<?php echo $usuario['id']; ?>" img_src="<?php echo $usuario['img_perfil'] ?>"><p><?php echo $usuario['nome']; ?></p></a>
							<div class="menu-logado-line"></div>
							<div class="menu-escolher-postador-line"></div>
							<?php if($tem_empresa){
									foreach ($empresas_user as $key => $value) {
										$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa` WHERE `id` = ?");
										$sql->execute(array($value['empresa_id']));
										$empresa_user_dados = $sql->fetch();?>
							<a href="" class="escolha-postador" tipo="1" origem_id="<?php echo $empresa_user_dados['id']; ?>"img_src="<?php echo $empresa_user_dados['imagem']; ?>"><p><?php echo $empresa_user_dados['nome']; ?></p></a>
								<?php }						 
							}?>									
						</div>
					</div>
				</div>
				<div class="post-texto">
				<form class="form-post" method="post" enctype="multipart/form-data" action="<?php echo INCLUDE_PATH; ?>ajax/home_post.ajax.php">
					<div class="div-img-postar">
						<img class="img-postar-close" onclick="document.getElementById('editar-imagem-img').src = ''" src="<?php echo INCLUDE_PATH; ?>images/close01_tamanho_01_stroke_white.png">
						<img class="img-postar" src="" id="editar-imagem-img" >
					</div>
					<textarea id="form-post-textarea" class="autosize" name="post" placeholder="Descreva serviço você procura ou quer divulgar..."></textarea>
					<div class="post-categoria">
						<label>Categoria: </label>
						<select name="categoria_id">
							<option></option>
							<?php foreach ($listaCategoria as $key => $value) {?>
							<option value="<?php echo strtoupper($value['id']); ?>"><?php echo strtoupper($value['nome']); ?></option>
							<?php } ?>
							<option>Outra Categoria</option>
						</select>
						<input type="text" name="categoria_outra">
					</div>
					<input type="file" id="file" name="imagem" onchange="document.getElementById('editar-imagem-img').src = window.URL.createObjectURL(this.files[0])">
	  				<label for="file"><img src="<?php echo INCLUDE_PATH; ?>images/img-file-40.png"><p>Selecionar Foto</p></label>
					<input type="hidden" name="origem_tipo" value="0">
					<input type="hidden" name="origem_id" value="<?php echo $usuario['id']; ?>">
					<input type="hidden" name="resposta_id" value="0">
					<input type="submit" name="acao_post" value="ENVIAR" disabled>
				</form>				
				</div><!-- post-texto -->
			</div><!-- box -->
			<?php } ?>
		</div> <!-- parte de escrever o post -->
		<div>
			<?php if(isset($tb_post)){
			foreach ($tb_post as $key => $value) {?>
				<div class="box post-single">
				<?php
					if($value['origem_tipo'] == 0){
						$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
						$sql->execute(array($value['origem_id']));
						$tb_post_dados = $sql->fetch();
						$tb_post_dados_img = $tb_post_dados['img_perfil']; ?>
					<div class="post-single-div-perfil-img"><a href="<?php echo INCLUDE_PATH ?>perfil/<?php echo $tb_post_dados['login'] ?>">
					<?php if($tb_post_dados_img == ''){?>
						<img class="post-single-perfil-img" src="<?php echo INCLUDE_PATH ?>images/clientevazio.JPG">
					<?php } else{ ?>
						<img class="post-single-perfil-img" src="<?php echo INCLUDE_PATH ?>/uploads/<?php echo $tb_post_dados_img ?>">
					<?php } ?>
					</a></div>
				<?php } 
					else if($value['origem_tipo'] == 1){
						$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa` WHERE `id` = ?");
						$sql->execute(array($value['origem_id']));
						$tb_post_dados = $sql->fetch();
						$tb_post_dados_img = $tb_post_dados['imagem']; ?>
					<div class="post-single-div-perfil-img"><a href="<?php echo INCLUDE_PATH ?>perfil_empresa/<?php echo $tb_post_dados['slug'] ?>">
					<?php if($tb_post_dados_img == ''){?>
						<img class="post-single-perfil-img" src="<?php echo INCLUDE_PATH ?>images/empresa01.png">
					<?php } else{ ?>
						<img class="post-single-perfil-img" src="<?php echo INCLUDE_PATH ?>/uploads/<?php echo $tb_post_dados_img ?>">
					<?php } ?>
					</a></div>
				<?php }?>
					<div class="post-single-div-conteudo">
						<div class="post-single-div-conteudo-nome">
							<div class="post-single-div-conteudo-nome-conteudo">
								<?php if($value['origem_tipo'] == 0){ ?>
								<a href="<?php echo INCLUDE_PATH ?>perfil/<?php echo $tb_post_dados['login'] ?>"><p><b><?php echo $tb_post_dados['nome'] ?></b></p><br></a>							
							</div>
							<?php if($estalogado && $usuario['id'] == $value['origem_id']){?>
							<div class="post-single-div-conteudo-nome-editar">
								<div class="post-single-div-conteudo-nome-editar-simbolo" post_id="<?php echo $value['id'] ?>">
									<p>...</p>
									<div class="box post-single-div-conteudo-nome-editar-simbolo-menu">
										<p post_id="<?php echo $value['id'] ?>" class="editar">✎ Editar</p>
										<p post_id="<?php echo $value['id'] ?>" class="excluir">X Excluir</p>
									</div>
								</div>
								<div class="post-single-div-conteudo-nome-editar-texto">
									<p post_id="<?php echo $value['id'] ?>" class="editar">✎ Editar</p>
									<p post_id="<?php echo $value['id'] ?>" class="excluir">X Excluir</p>									
								</div>
							</div>
							<?php } ?>
								<?php } else if($value['origem_tipo'] == 1){?>
								<a href="<?php echo INCLUDE_PATH ?>perfil_empresa/<?php echo $tb_post_dados['slug'] ?>"><p><b><?php echo $tb_post_dados['nome'] ?></b></p><br></a>							
						</div>
							<?php if($estalogado){
								$post_compara_user_empresa = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.user` WHERE `empresa_id` = ? AND `user_id` = ?");
								$post_compara_user_empresa->execute(array($value['origem_id'],$usuario['id']));
								if($post_compara_user_empresa->rowCount() > 0){?>
							<div class="post-single-div-conteudo-nome-editar">
								<div class="post-single-div-conteudo-nome-editar-simbolo">
									<p>...</p>
									<div class="box post-single-div-conteudo-nome-editar-simbolo-menu">
										<p post_id="<?php echo $value['id'] ?>" class="editar">✎ Editar</p>
										<p post_id="<?php echo $value['id'] ?>" class="excluir">X Excluir</p>
									</div>
								</div>
								<div class="post-single-div-conteudo-nome-editar-texto">
									<p post_id="<?php echo $value['id'] ?>" class="editar">✎ Editar</p>
									<p post_id="<?php echo $value['id'] ?>" class="excluir">X Excluir</p>									
								</div>
							</div>
							<?php }} ?>
								<?php } ?>
						</div>
						<p class="post-single-div-conteudo-texto p_post_<?php echo $value['id'] ?>"><?php echo nl2br($value['post']) ?></p>
						<div class="post-single-div-conteudo-textarea textarea_post_<?php echo $value['id'] ?>">
							<form method="post" action="<?php echo INCLUDE_PATH; ?>ajax/home_post.ajax.php">
								<textarea name="textarea_post" class="autosize" value_old="<?php echo $value['post'] ?>"><?php echo $value['post'] ?></textarea>
								<div>
									<input type="hidden" name="post_id" value="<?php echo $value['id'] ?>">
									<input type="submit" name="acao_altera_post" value="ENVIAR">
									<input type="submit" name="acao_cancela_post" value="CANCELAR" post_id="<?php echo $value['id'] ?>">
								</div>
							</form>
						</div>
						<?php if(($value['origem_tipo'] == 0) || ($value['categoria_outra'] != '')) {
								$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` WHERE `id` = ?");
								$sql->execute(array($value['categoria_id']));
								$tb_post_categoria_dados = $sql->fetch(); 
								if($value['categoria_outra'] == '' && isset($tb_post_categoria_dados['nome'])){?>
						<br><p><b>Categoria Desejada: </b> <?php echo $tb_post_categoria_dados['nome']; ?></p><br>
								<?php }else if($value['categoria_outra'] != ''){ ?>
						<br><p><b>Categoria Desejada: </b> <?php echo $value['categoria_outra']; ?></p><br>
							<?php } ?>
						<?php } ?>
					</div><!-- post-single-div-conteudo -->
					<div class="post-single-div-img">
					<?php if($value['imagem'] != ''){ ?>
						<img src="<?php echo INCLUDE_PATH ?>/uploads/posts/<?php echo $value['imagem'] ?>">
					<?php } ?>
					</div>
					<?php if($estalogado){ ?>
					<div class="resposta-line"></div>
					<div class="post-resposta">
						<div class="post-postador">	
							<div class="resposta-post-postador-div1" post_id="<?php echo $value['id'] ?>">
								<div class="post-postador-img post-postador-img_<?php echo $value['id'] ?>">						
								<?php if($usuario['img_perfil'] == ''){ ?>
									<img src="<?php echo INCLUDE_PATH ?>images/clientevazio.JPG">
								<?php } else{ ?>
									<img src="<?php echo INCLUDE_PATH ?>/uploads/<?php echo $usuario['img_perfil'] ?>">
								<?php } ?>	
									
								</div>
								<div>		
									 <p>▼</p>						
								</div>					
							</div>
							<div class="resposta-menu-escolher resposta-menu-escolher_post_<?php echo $value['id'] ?>">
								<div class="box">
									<h3>Escolha quem irá postar</h3>
									<div class="menu-escolher-postador-line"></div>
									<a href="" class="escolha-postador" tipo="0" origem_id="<?php echo $usuario['id']; ?>" img_src="<?php echo $usuario['img_perfil'] ?>" post_id="<?php echo $value['id'] ?>"><p><?php echo $usuario['nome']; ?></p></a>
									<div class="menu-logado-line"></div>
									<div class="menu-escolher-postador-line"></div>
									<?php if($tem_empresa){
											foreach ($empresas_user as $key2 => $value2) {
												$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa` WHERE `id` = ?");
												$sql->execute(array($value2['empresa_id']));
												$empresa_user_dados = $sql->fetch();?>
									<a href="" class="escolha-postador" tipo="1" origem_id="<?php echo $empresa_user_dados['id']; ?>"img_src="<?php echo $empresa_user_dados['imagem']; ?>" post_id="<?php echo $value['id'] ?>"><p><?php echo $empresa_user_dados['nome']; ?></p></a>
										<?php }						 
									}?>									
								</div>
							</div>		
						</div>
						<form class="form-post-resposta form-post_<?php echo $value['id'] ?>" method="post" enctype="multipart/form-data" action="<?php echo INCLUDE_PATH; ?>ajax/home_post.ajax.php">
							<textarea class="autosize" name="post" placeholder="Escreva um comentário..." post_id="<?php echo $value['id'] ?>"></textarea>
							<input type="hidden" name="post_id" value="<?php echo $value['id'] ?>">
							<input type="hidden" name="origem_tipo" value="0">
							<input type="hidden" name="origem_id" value="<?php echo $usuario['id']; ?>">
							<input type="hidden" name="resposta_id" value="0">
							<input type="hidden" name="acao_resposta">
						</form>		
					</div>
					<div class="post-resposta-lista post-resposta-lista_<?php echo $value['id'] ?>">
					<?php 
						$post_paginaAtual[$value['id']] = 1;
						$post_porPagina = 5;
						$post_paginaProxima[$value['id']] = ($post_paginaAtual[$value['id']] - 1)*$post_porPagina;
						$sql = MySql::conectar()->prepare("SELECT * FROM `tb_post` WHERE `resposta_id` = ? ORDER BY `id` DESC LIMIT 0,$post_porPagina");
						$sql->execute(array($value['id']));
						$tb_post_respostas = $sql->fetchAll();
						foreach ($tb_post_respostas as $key3 => $value3) {?>
						<div class="post-resposta-lista-single">
							<div class="post-resposta-lista-img">
								<?php if($value3['origem_tipo'] == 0){
									$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
									$sql->execute(array($value3['origem_id']));
									$tb_post_dados = $sql->fetch();
									$tb_post_dados_img = $tb_post_dados['img_perfil']; ?>
									<div class="post-single-div-perfil-img"><a href="<?php echo INCLUDE_PATH ?>perfil/<?php echo $tb_post_dados['login'] ?>">
									<?php if($tb_post_dados_img == ''){?>
										<img class="post-single-perfil-img" src="<?php echo INCLUDE_PATH ?>images/clientevazio.JPG">
									<?php } else{ ?>
										<img class="post-single-perfil-img" src="<?php echo INCLUDE_PATH ?>/uploads/<?php echo $tb_post_dados_img ?>">
									<?php } ?>
									</a></div>
								<?php } 
									else if($value3['origem_tipo'] == 1){
										$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa` WHERE `id` = ?");
										$sql->execute(array($value3['origem_id']));
										$tb_post_dados = $sql->fetch();
										$tb_post_dados_img = $tb_post_dados['imagem']; ?>
									<div class="post-single-div-perfil-img"><a href="<?php echo INCLUDE_PATH ?>perfil_empresa/<?php echo $tb_post_dados['slug'] ?>">
									<?php if($tb_post_dados_img == ''){?>
										<img class="post-single-perfil-img" src="<?php echo INCLUDE_PATH ?>images/empresa01.png">
									<?php } else{ ?>
										<img class="post-single-perfil-img" src="<?php echo INCLUDE_PATH ?>/uploads/<?php echo $tb_post_dados_img ?>">
									<?php } ?>
									</a></div>
								<?php }?>
							</div><!-- post-resposta-lista-img -->
							<div class="post-resposta-lista-conteudo">
								<div class="post-single-div-conteudo-nome">
									<div class="post-single-div-conteudo-nome-conteudo">
										<?php if($value3['origem_tipo'] == 0){ ?>
										<a href="<?php echo INCLUDE_PATH ?>perfil/<?php echo $tb_post_dados['login'] ?>"><p><b><?php echo $tb_post_dados['nome'] ?></b></p></a>							
									</div>
									<?php if($estalogado && $usuario['id'] == $value3['origem_id']){?>
									<div class="post-single-div-conteudo-nome-editar">
										<div class="post-single-div-conteudo-nome-editar-simbolo" post_id="<?php echo $value['id'] ?>">
											<p>...</p>
											<div class="box post-single-div-conteudo-nome-editar-simbolo-menu">
												<p post_id="<?php echo $value3['id'] ?>" class="editar">✎ Editar</p>
												<p post_id="<?php echo $value3['id'] ?>" class="excluir">X Excluir</p>
											</div>
										</div>
										<div class="post-single-div-conteudo-nome-editar-texto">
											<p post_id="<?php echo $value3['id'] ?>" class="editar">✎ Editar</p>
											<p post_id="<?php echo $value3['id'] ?>" class="excluir">X Excluir</p>									
										</div>
									</div>
									<?php } ?>
										<?php } else if($value3['origem_tipo'] == 1){?>
										<a href="<?php echo INCLUDE_PATH ?>perfil_empresa/<?php echo $tb_post_dados['slug'] ?>"><p><b><?php echo $tb_post_dados['nome'] ?></b></p><br></a>							
								</div>
									<?php if($estalogado){
										$post_compara_user_empresa = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.user` WHERE `empresa_id` = ? AND `user_id` = ?");
										$post_compara_user_empresa->execute(array($value3['origem_id'],$usuario['id']));
										if($post_compara_user_empresa->rowCount() > 0){?>
									<div class="post-single-div-conteudo-nome-editar">
										<div class="post-single-div-conteudo-nome-editar-simbolo" post_id="<?php echo $value['id'] ?>">
											<p>...</p>
											<div class="box post-single-div-conteudo-nome-editar-simbolo-menu">
												<p post_id="<?php echo $value3['id'] ?>" class="editar">✎ Editar</p>
												<p post_id="<?php echo $value3['id'] ?>" class="excluir">X Excluir</p>
											</div>
										</div>
										<div class="post-single-div-conteudo-nome-editar-texto">
											<p post_id="<?php echo $value3['id'] ?>" class="editar">✎ Editar</p>
											<p post_id="<?php echo $value3['id'] ?>" class="excluir">X Excluir</p>									
										</div>
									</div>
									<?php }} ?>
										<?php } ?>
								</div>
								<p class="post-single-div-conteudo-texto p_post_<?php echo $value3['id'] ?>"><?php echo nl2br($value3['post']) ?></p>
								<div class="post-single-div-conteudo-textarea textarea_post_<?php echo $value3['id'] ?>">
									<form method="post" action="<?php echo INCLUDE_PATH; ?>ajax/home_post.ajax.php">
										<textarea name="textarea_post" class="autosize" value_old="<?php echo $value3['post'] ?>"><?php echo $value3['post'] ?></textarea>
										<div>
											<input type="hidden" name="post_id" value="<?php echo $value3['id'] ?>">
											<input type="submit" name="acao_altera_post" value="ENVIAR">
											<input type="submit" name="acao_cancela_post" value="CANCELAR" post_id="<?php echo $value3['id'] ?>">
										</div>
									</form>
								</div>
								<?php if(($value3['origem_tipo'] == 0) || ($value3['categoria_outra'] != '')) {
										$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` WHERE `id` = ?");
										$sql->execute(array($value3['categoria_id']));
										$tb_post_categoria_dados = $sql->fetch(); 
										if($value3['categoria_outra'] == '' && isset($tb_post_categoria_dados['nome'])){?>
								<br><p><b>Categoria Desejada: </b> <?php echo $tb_post_categoria_dados['nome']; ?></p><br>
										<?php }else if($value3['categoria_outra'] != ''){ ?>
								<br><p><b>Categoria Desejada: </b> <?php echo $value3['categoria_outra']; ?></p><br>
									<?php } ?>
								<?php } ?>
							</div><!-- post-resposta-lista-conteudo -->
						</div><!-- post-resposta-lista-single -->
						<?php } ?>
					</div><!-- post-resposta-lista -->
					<?php if(count($tb_post_respostas) > 0){?>
					<div class="post-resposta-lista-paginacao">
						<p class="post-resposta-lista-paginacao_p_<?php echo $value['id']; ?>" pagina_atual="<?php echo $post_paginaAtual[$value['id']] ?>" por_pagina="<?php echo $post_porPagina; ?>" pagina_proxima="<?php echo $post_paginaProxima[$value['id']] ?>" post_id="<?php echo $value['id']; ?>" fim_respostas="false">Ver Mais Comentários</p>
					</div><!-- post-resposta-lista-paginacao -->
					<?php } ?>
					<?php } ?>
				</div><!-- box post-single -->
			<?php }} ?>
		</div>
	</div><!-- section-center -->
	<div class="section-right"></div><!-- section-right -->
	<div class="clear"></div>	
</section>