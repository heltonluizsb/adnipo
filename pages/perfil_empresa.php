	
<section class="section-perfil">
 	<div class="box">
 		<?php 

			$podeEditar = false;
			$podeListar = false;
			$estalogado = false;
			$Eproprietario = false;
			$existesolicitacao = false;
			if(isset($_COOKIE['user']) && isset($_COOKIE['senha'])){
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `login` = ? and `senha` = ?");
				$sql->execute(array($_COOKIE['user'],$_COOKIE['senha']));
				if($sql->rowCount() > 0){
					$estalogado = true;
					$usuario = $sql->fetch();
					$user_id = $usuario['id'];
				}
			}
			
			if (isset($url_explode[1])){
				if($url_explode[1] == ''){
					echo padrao::redirecionaPagina('empresas');
					die();			
				}
				else{
					$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa` WHERE `slug` = ?");
					$sql->execute(array($url_explode[1]));
					if($sql->rowCount() >= 1){
						$empresa_dados = $sql->fetch();
						$empresa_id = $empresa_dados['id'];
					}
					else{
						echo padrao::redirecionaPagina('empresas');
						die();
					}
				}
			}
			else if(!isset($url_explode[1])){
				echo padrao::redirecionaPagina('empresas');
				die();			
			}

			if(isset($_COOKIE['user'])){

				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.user` WHERE `user_id` = $user_id AND `empresa_id` = $empresa_id");
				$sql->execute();

				if($sql->rowCount() > 0){
					$permissao_user = $sql->fetch();
					if($permissao_user['permissao_id'] == 1){
						$podeEditar = true;
						if($empresa_dados['id_criador'] == $user_id){
							$Eproprietario = true;
							if(isset($_GET['excluir'])){
								$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.categoria` WHERE `id_empresa` = ?");
								$sql->execute(array($empresa_dados['id']));
								$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.solicitacao` WHERE `empresa_id` = ?");
								$sql->execute(array($empresa_dados['id']));
								$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.user` WHERE `empresa_id` = ?");
								$sql->execute(array($empresa_dados['id']));
								$sql = MySql::conectar()->prepare("DELETE FROM `tb_post` WHERE `origem_tipo` = ? AND `origem_id` = ?");
								$sql->execute(array(1,$empresa_dados['id']));
								$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa` WHERE `id` = ?");
								$sql->execute(array($empresa_dados['id']));
								echo padrao::redirecionaPagina('empresas');
							}
						}
					}
					else{
						$podeListar = true;
					}
				}

				if(!$podeEditar && !$podeListar && $estalogado){
					$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.solicitacao` WHERE `user_id` = ? AND `empresa_id` = ?");
					$sql->execute(array($user_id,$empresa_id));
					if($sql->rowCount() > 0){
						$existesolicitacao = true;
					}

		 			if(isset($_GET['solicitar-acesso']) && !$existesolicitacao){
						$sql = MySql::conectar()->prepare("INSERT INTO `tb_empresa.solicitacao` VALUES (null,?,?)");
						$sql->execute(array($user_id,$empresa_id));
						echo padrao::redirecionaPagina('perfil_empresa/'.$url_explode[1].'?solicitacaoenviada');
		 			}
				}

				if(($podeEditar || $podeListar) && $estalogado){
					if(isset($_GET['sair_da_empresa'])){
						$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.user` WHERE `empresa_id` = ? AND `user_id` = ?");
						$sql->execute(array($empresa_dados['id'],$_GET['user_id']));
						echo padrao::redirecionaPagina('perfil_empresa/'.$url_explode[1]);
					}
				}

				if(isset($_GET['excluir-acesso-integrante'])){
 			 		$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.user` WHERE `empresa_id` = ? AND `user_id` = ? ");
 			 		$sql->execute(array($empresa_id,$_GET['excluir-acesso-integrante']));
					echo padrao::redirecionaPagina('perfil_empresa/'.$url_explode[1].'?acesso-integrante-excluido');
				}
				else if(isset($_GET['tornar-proprietario'])){
 			 		$sql = MySql::conectar()->prepare("UPDATE `tb_empresa.user` SET `permissao_id` = ? WHERE `empresa_id` = ? AND `user_id` = ? ");
 			 		$sql->execute(array(1,$empresa_id,$_GET['tornar-proprietario']));
 			 		$sql = MySql::conectar()->prepare("UPDATE `tb_empresa` SET `id_criador` = ? WHERE `id` = ?");
 			 		$sql->execute(array($_GET['tornar-proprietario'],$empresa_id));
					echo padrao::redirecionaPagina('perfil_empresa/'.$url_explode[1].'?acesso-integrante-proprietario');

				}
			}
 		?>

		<?php if($empresa_dados['imagem'] == ''){ ?>
			<img src="<?php echo INCLUDE_PATH ?>images/empresa01.png">
		<?php } else{ ?>
			<img src="<?php echo INCLUDE_PATH ?>/uploads/<?php echo $empresa_dados['imagem'] ?>">
		<?php } ?>
 		<p class="perfil-nome"><?php echo $empresa_dados['nome'] ?> <?php if($podeEditar){ echo '<a href="">✎ Editar</a>';} ?></p>
 	</div>
 	<div class="box50 boxleft">
 		<div class="box">
 			<?php if(($podeEditar && $usuario['confirma_email'] != 'confirmado') && $url_explode[1] == 'perfil'){ ?>
 				<div class="nao_confirmado">
 					<p>Seu e-mail ainda não foi confirmado</p>
 				</div>
 			<?php }
 				if($podeEditar){ ?>
				<div class="editar-empresa"> 
					<a href=""><p>✎ Editar</p></a>
				</div>
 			<?php }?>
				<div class="clear"></div>
				<div class="perfil">
					<p class="perfil-empresa"><b>Descrição:</b> <?php echo $empresa_dados['descricao']  ?></p>
					<p class="perfil-empresa"><b>Endereço:</b> <a href="https://maps.google.com/?q=<?php echo $empresa_dados['endereco']  ?>" target="_blank"><?php echo $empresa_dados['endereco']  ?></a></p>
					<p class="perfil-empresa"><b>Telefone:</b> <?php echo $empresa_dados['telefone']  ?></p>
					<p class="perfil-empresa"><b>Site:</b> <a href="<?php echo $empresa_dados['site'] ?>"><?php echo $empresa_dados['site']  ?></a></p>
				</div>
				<div class="clear"></div>
				<?php if($estalogado && ($podeEditar || $podeListar)){ ?>
					<a href="<?php echo INCLUDE_PATH ?>perfil_empresa/<?php echo $empresa_dados['slug']  ?>?sair_da_empresa&user_id=<?php echo $usuario['id']  ?>" class="sair-empresa" empresa_nome="<?php echo $empresa_dados['nome'] ?>">SAIR DA EMPRESA</a>
				<?php } ?>
 		</div>

 		<div class="box">
 			<?php if($podeEditar){ ?>
				<div class="editar-empresa"> 
					<a href=""><p>✎ Editar</p></a>
				</div>
 			<?php }?>
			<div class="clear"></div>
 			<h2>Categorias</h2>
 			<ul>
 				<?php 	 					
 			 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.categoria` WHERE `id_empresa` = $empresa_id");
 			 		$sql->execute();
 			 		$categorias_empresa = $sql->fetchAll();
 			 		foreach ($categorias_empresa as $key => $value) {
 			 			$categoria_id = $value['id_categoria'];	 					
	 			 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` WHERE `id` = $categoria_id");
	 			 		$sql->execute();
	 			 		$categoria_dados = $sql->fetch();?>
	 			 	<li class="categoria_empresa" categoria_id="<?php echo $categoria_id ?>"><?php echo strtoupper($categoria_dados['nome']) ?></li>
 			 	<?php } ?>
 			</ul>

 			<h2>Integrantes</h2>
 			<ul class="integrantes">
 				<?php 	 					
 			 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.user` WHERE `empresa_id` = $empresa_id");
 			 		$sql->execute();
 			 		$users_empresa = $sql->fetchAll();
 			 		foreach ($users_empresa as $key => $value) {
 			 			$user_empresa_id = $value['user_id'];	 					
	 			 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = $user_empresa_id");
	 			 		$sql->execute();
	 			 		$user_empresa_dados = $sql->fetch();
	 			 		$encoding = mb_internal_encoding();?>
	 			 	<li href="<?php echo INCLUDE_PATH ?>perfil/<?php echo $user_empresa_dados['login'] ?>"><?php echo mb_strtoupper($user_empresa_dados['nome'],$encoding) ?></li>
 			 	<?php } ?>
 			</ul>
 			<?php if(!$podeListar && !$podeEditar && !$existesolicitacao && $estalogado){?>
 				<a href="<?php echo INCLUDE_PATH ?>perfil_empresa/<?php echo $url_explode[1] ?>?solicitar-acesso" class="solicitar-acesso"><p>Solicitar Acesso a Esta Empresa</p></a>
 			<?php } else if(!$podeListar && !$podeEditar && $existesolicitacao){
 				if(isset($_GET['solicitacaoenviada'])){
 					padrao::alert('sucesso','Solicitação Enviada com Sucesso!');

 				}else{
 					padrao::alert('atencao','Já existe um solictação de acesso em andamento. Favor aguardar ou entre em contato com o administrador.');
 				}
		 	} ?>
		 	<?php if($podeEditar){ ?>
		 		<h2>Solicitações Pendentes</h2>
		 	<?php

		 		if(isset($_GET['aprovar'])){		 			
 			 		$sql = MySql::conectar()->prepare("INSERT INTO `tb_empresa.user` VALUES (null,?,?,?)");
 			 		$sql->execute(array($empresa_id,$_GET['aprovar'],2));

 			 		$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.solicitacao` WHERE `user_id` = ? AND `empresa_id` = ? ");
 			 		$sql->execute(array($_GET['aprovar'],$empresa_id));

					echo padrao::redirecionaPagina('perfil_empresa/'.$url_explode[1].'?acessoliberado');
		 		}
		 		else if(isset($_GET['rejeitar'])){
 			 		$sql = MySql::conectar()->prepare("DELETE FROM `tb_empresa.solicitacao` WHERE `user_id` = ? AND `empresa_id` = ? ");
 			 		$sql->execute(array($_GET['rejeitar'],$empresa_id));

 			 		padrao::alert('atencao','Acesso Rejeitado');

		 		}
		 		elseif(isset($_GET['acessoliberado'])){
 			 		padrao::alert('sucesso','Acesso Liberado com sucesso!');
		 		}

		 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.solicitacao` WHERE `empresa_id` = $empresa_id");
		 		$sql->execute();
		 		if($sql->rowCount() <= 0){?>
		 			<p style="font-style: italic;">Não há solicitações</p>
	 		<?php
	 			}else{ 
	 				$solicitacoesPendentes = $sql->fetchAll(); ?>
	 			<table class="tabela-solicitacoes">
	 				<tr>
	 					<th>Nome</th>
	 					<th>Solicitação</th>
	 				</tr>
	 		<?php foreach ($solicitacoesPendentes as $key => $value) {
	 					$solicitacao_user_id = $value['user_id'];
	 			 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
	 			 		$sql->execute(array($solicitacao_user_id));
	 			 		$solicitacao_user_dados = $sql->fetch();?>
	 			 	<tr>
	 			 		<td><?php echo $solicitacao_user_dados['nome']; ?></td>
	 			 		<td>
	 			 			<a href="<?php echo INCLUDE_PATH; ?>perfil_empresa/<?php echo $url_explode[1]; ?>?aprovar=<?php echo $solicitacao_user_id; ?>" class="aprovar">Aprovar</a>
	 			 			<a href="<?php echo INCLUDE_PATH; ?>perfil_empresa/<?php echo $url_explode[1]; ?>?rejeitar=<?php echo $solicitacao_user_id; ?>" class="rejeitar">Rejeitar</a>
	 			 		</td>
	 			 	</tr>
 				<div class="celular-solicitacao"><p></p></div>
	 		<?php }}} ?>
	 			</table>
 		</div>
 	</div>
 	<div class="box50 boxright">
 		<!-- PARTE DE POSTS -->
 		<?php 
			$paginaAtual = 1;
			$porPagina = 5;
			$paginaProxima = ($paginaAtual - 1)*$porPagina;

			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_post` WHERE `origem_tipo` = ? AND `origem_id` = ? AND `resposta_id` = ? ORDER BY `id` DESC LIMIT $paginaProxima,$porPagina");
			$sql->execute(array(1,$empresa_id,0));
			if($sql->rowCount() > 0){
				$tb_post = $sql->fetchAll(); ?>
			<listagem pgatual="<?php echo $paginaAtual ?>" porpg="<?php echo $porPagina ?>"> 
			<?php foreach ($tb_post as $key => $value) {?>
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
					<?php if($value['origem_tipo'] == 0){ ?>
					<a href="<?php echo INCLUDE_PATH ?>perfil/<?php echo $tb_post_dados['login'] ?>"><p><b><?php echo $tb_post_dados['nome'] ?></b></p><br></a>
					<?php } else if($value['origem_tipo'] == 1){?>
					<a href="<?php echo INCLUDE_PATH ?>perfil_empresa/<?php echo $tb_post_dados['slug'] ?>"><p><b><?php echo $tb_post_dados['nome'] ?></b></p><br></a>
					<?php } ?>
					<p><?php echo $value['post'] ?></p>
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
			</div><!-- box post-single -->
		<?php }} ?>
 	</div> <!-- DIV DE POSTS - FIM  -->
 	<div class="clear"></div>
</section>

<div class="edita-login edita-login-empresa <?php if(isset($_GET['acesso-integrante-excluido']) || isset($_GET['acesso-integrante-proprietario'])){ echo 'mostra-edicao'; } ?>">
	<div class="box">
		<img src="<?php echo INCLUDE_PATH; ?>images/close01_tamanho_01.png">
		<div class="clear"></div>
		<h2>Alterar Imagem</h2>
		<div class="edita-login-img">
			<?php if($empresa_dados['imagem'] == ''){ ?>
				<img id="editar-imagem-img" src="<?php INCLUDE_PATH ?>images/empresa01.png">
			<?php } else{ ?>
				<img id="editar-imagem-img" src="<?php echo INCLUDE_PATH ?>/uploads/<?php echo $empresa_dados['imagem'] ?>">
			<?php } ?>
		</div>
		<form method="post" action="<?php INCLUDE_PATH ?>ajax/atualiza_empresa.php" class="form-altera-imagem-empresa" enctype="multipart/form-data">
			<input type="file" name="imagem_nova" id="editar-imagem-empresa" onchange="document.getElementById('editar-imagem-img').src = window.URL.createObjectURL(this.files[0])">
			<input type="hidden" name="imagem_atual" value="<?php echo $empresa_dados['imagem'] ?>">
			<input type="hidden" name="empresa_id" value="<?php echo $empresa_dados['id']; ?>">
			<input type="hidden" name="slug" value="<?php echo $empresa_dados['slug']; ?>">
			<input type="hidden" name="nome_tabela" value="tb_user">
		</form>
		<h2>Alterar Dados</h2>
		<form method="post" action="<?php INCLUDE_PATH ?>ajax/atualiza_empresa.php" class="form-altera-descricao-empresa" enctype="multipart/form-data">

			<div class="form-group">
				<label>Descrição: </label>
				<textarea name="descricao"><?php echo $empresa_dados['descricao'] ?></textarea>				
			</div>

			<div class="form-group">
				<label>Endereço: </label>
				<input type="text" name="endereco" value="<?php echo $empresa_dados['endereco'] ?>">
			</div>

			<div class="form-group">
				<label>Telefone: </label>
				<input type="text" name="telefone" value="<?php echo $empresa_dados['telefone'] ?>">
			</div>

			<div class="form-group">
				<label>Site: </label>
				<input type="text" name="site" value="<?php echo $empresa_dados['site'] ?>">
			</div>

			<input type="submit" name="alterar_descricao">
			<input type="hidden" name="imagem_atual" value="<?php echo $empresa_dados['imagem'] ?>">
			<input type="hidden" name="empresa_id" value="<?php echo $empresa_dados['id']; ?>">
			<input type="hidden" name="slug" value="<?php echo $empresa_dados['slug']; ?>">
			<input type="hidden" name="nome_tabela" value="tb_user">
		</form>
		<h2>Alterar Categorias</h2>
		<form method="post" action="<?php INCLUDE_PATH ?>ajax/atualiza_empresa.php" class="form-altera-categoria-empresa" enctype="multipart/form-data">

			<div class="form-group">
				<div>					
					<label>Categoria 01: </label>
					<select name="categoria_1" categoria_id="<?php if(isset($categorias_empresa[0]['id_categoria'])){ echo $categorias_empresa[0]['id_categoria'];} ?>">
						<option disabled selected>Selecione uma categoria</option>
						<?php
							$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` ORDER BY `nome`");
							$sql->execute();
							$listarCategoria1 = $sql->fetchAll();
							foreach ($listarCategoria1 as $key => $value) {								
								  ?>
								<option value="<?php echo $value['id']; ?>"<?php if((isset($categorias_empresa[0]['id_categoria'])) && ($value['id'] == $categorias_empresa[0]['id_categoria'])){ echo 'selected';} ?>><?php echo strtoupper($value['nome']); ?></option>
						<?php } ?>
						<option value="limpar">Limpar Categoria</option>
					</select>				
				</div>
				<div>					
					<label>Categoria 02: </label>
					<select name="categoria_2" categoria_id="<?php if(isset($categorias_empresa[1]['id_categoria'])){ echo $categorias_empresa[1]['id_categoria'];} ?>">
						<option disabled selected>Selecione uma categoria</option>
						<?php
							$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` ORDER BY `nome`");
							$sql->execute();
							$listarCategoria2 = $sql->fetchAll();
							foreach ($listarCategoria2 as $key => $value) { ?>
								<option value="<?php echo $value['id']; ?>"<?php if((isset($categorias_empresa[1]['id_categoria'])) && ($value['id'] == $categorias_empresa[1]['id_categoria'])){ echo 'selected';} ?>><?php echo strtoupper($value['nome']); ?></option>
						<?php } ?>
						<option value="limpar">Limpar Categoria</option>
					</select>
				</div>
				<div>					
					<label>Categoria 03: </label>
					<select name="categoria_3" categoria_id="<?php if(isset($categorias_empresa[2]['id_categoria'])){ echo $categorias_empresa[2]['id_categoria'];} ?>">
						<option disabled selected>Selecione uma categoria</option>
						<?php
							$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` ORDER BY `nome`");
							$sql->execute();
							$listarCategoria3 = $sql->fetchAll();
							foreach ($listarCategoria3 as $key => $value) { ?>
								<option value="<?php echo $value['id']; ?>"<?php if((isset($categorias_empresa[2]['id_categoria'])) && ($value['id'] == $categorias_empresa[2]['id_categoria'])){ echo 'selected';} ?>><?php echo strtoupper($value['nome']); ?></option>
						<?php } ?>
						<option value="limpar">Limpar Categoria</option>
					</select>
				</div>			
			</div>

			<input type="submit" name="alterar_categorias">
			<input type="hidden" name="imagem_atual" value="<?php echo $empresa_dados['imagem'] ?>">
			<input type="hidden" name="empresa_id" value="<?php echo $empresa_dados['id']; ?>">
			<input type="hidden" name="slug" value="<?php echo $empresa_dados['slug']; ?>">
			<input type="hidden" name="nome_tabela" value="tb_user">
		</form>
		<h2>Alterar Permissão dos Integrantes</h2>
		<div class="tabela-wraper">
			<?php if(isset($_GET['acesso-integrante-excluido'])){ 
					padrao::alert('sucesso','Acesso do Integrante removido da empresa.');
				}else if(isset($_GET['acesso-integrante-proprietario'])){
					padrao::alert('sucesso','Integrante virou proprietário da empresa.');					
				}?>
			 
			<table class="tabela-solicitacoes">
				<tr>
					<th>Nome</th>
					<th>Permissão</th>
					<?php if($Eproprietario){ ?><th>Tornar Proprietário</th><?php } ?>
					<th>Excluir Integrante</th>				
				</tr>
				<?php foreach ($users_empresa as $key => $value) {
	 			 			$user_empresa_id = $value['user_id'];
	 			 			if(($user_empresa_id == $user_id) || ($user_empresa_id == $empresa_dados['id_criador'])){
	 			 				continue;
	 			 			}	 					
		 			 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = $user_empresa_id");
		 			 		$sql->execute();
		 			 		$user_empresa_dados = $sql->fetch();?>
				<tr>
					<td><?php echo $user_empresa_dados['nome']; ?></td>
					<td>
						<select class="select-permissao" user_id="<?php echo $value['user_id']; ?>" empresa_id="<?php echo $empresa_id; ?>" slug="<?php echo $empresa_dados['slug']; ?>">
							<?php 													
			 			 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.permissao`");
			 			 		$sql->execute();
			 			 		$listarPermissao = $sql->fetchAll();
			 			 		foreach ($listarPermissao as $key2 => $value2) {?>
	 			 			<option value="<?php echo $value2['id'] ?>" <?php if($value2['id'] == $value['permissao_id']){ echo 'selected'; } ?>><?php echo $value2['nome'] ?></option>
			 			 	<?php } ?>
						</select>
					</td>
					<?php if($Eproprietario){ ?>
					<td>
						<a href="<?php echo INCLUDE_PATH ?>perfil_empresa/<?php echo $url_explode[1]; ?>?tornar-proprietario=<?php echo $value['user_id']; ?>" class="tornar-proprietario" user_empresa_dados_nome="<?php echo $user_empresa_dados['nome'] ?>">Tornar Proprietário</a>
					</td>
					<?php } ?>
					<td>
						<a href="<?php echo INCLUDE_PATH ?>perfil_empresa/<?php echo $url_explode[1]; ?>?excluir-acesso-integrante=<?php echo $value['user_id']; ?>" class="excluir">Excluir Integrante</a>
					</td>

				</tr>
				<?php } ?>
			</table>			
		</div><!-- tabela-wraper -->
		<?php if($Eproprietario){ ?>
		<a class="excluir-empresa-btn" href="<?php echo INCLUDE_PATH ?>perfil_empresa/<?php echo $empresa_dados['slug'] ?>?excluir" empresa_nome="<?php echo $empresa_dados['nome'] ?>">EXCLUIR EMPRESA</a>
	<?php } ?>
	</div><!-- box -->
</div> <!-- edita-login-empresa -->

<div class="mostra-imagem">
	<div class="box">
		<img src="<?php echo INCLUDE_PATH; ?>images/close01_tamanho_01.png">
		<div class="mostra-image-img">
			<?php if($empresa_dados['imagem'] == ''){ ?>
				<img id="editar-imagem-img" src="<?php INCLUDE_PATH ?>images/empresa01.png">
			<?php } else{ ?>
				<img id="editar-imagem-img" src="<?php echo INCLUDE_PATH ?>/uploads/<?php echo $empresa_dados['imagem'] ?>">
			<?php } ?>
		</div>
	</div>
</div>