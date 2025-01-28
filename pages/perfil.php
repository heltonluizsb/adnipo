	
<section class="section-perfil">
 	<div class="box">
 		<?php 
			$podeEditar = false;
			$perfil = null;
			if(isset($_COOKIE['user'])){
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `login` = ?");
				$sql->execute(array($_COOKIE['user']));
				if($sql->rowCount() >= 1){
					$usuario = $sql->fetch();
					$sql = MySql::conectar()->prepare("SELECT * FROM `tb_filial` WHERE `id` = ?");
					$sql->execute(array($usuario['filial_id']));
					if($sql->rowCount() > 0){
						$filial_dados = $sql->fetch();
					}
					else{
						$filial_dados['nome'] = 'NÃO PREENCHEU A FILIAL';
					}
				}
				else{
					padrao::alert('erro','Problemas com esse usuário');
					die();
				}

				if(isset($url_explode[1]) && $url_explode[1] == $_COOKIE['user']){
					$podeEditar = true;
				}
			}
			
			if (isset($url_explode[1])){
				if($url_explode[1] == ''){
					padrao::alert('erro','Perfil INCOMPLETO');
					die();			
				}
				else{
					$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `login` = ?");
					$sql->execute(array($url_explode[1]));
					if($sql->rowCount() >= 1){
						$perfil = $sql->fetch();
						$sql = MySql::conectar()->prepare("SELECT * FROM `tb_filial` WHERE `id` = ?");
						$sql->execute(array($perfil['filial_id']));
						if($sql->rowCount() > 0){
							$filial_dados_perfil = $sql->fetch();
						}
						else{
							$filial_dados_perfil['nome'] = 'NÃO PREENCHEU A FILIAL';
						}
					}
					else{
						padrao::alert('erro','Não existe este perfil');
						die();
					}
				}
			}
			else if(!isset($url_explode[1])){
				padrao::alert('erro','Perfil em Branco');
				die();			
			}
 		?>
 		<div class="perfil-box-wraper">
 			<div>
		 		<div>
					<?php if($perfil['img_perfil'] == ''){ ?>
						<img src="<?php echo INCLUDE_PATH ?>images/clientevazio.JPG">
					<?php } else{ ?>
						<img src="<?php echo INCLUDE_PATH ?>/uploads/<?php echo $perfil['img_perfil'] ?>">
					<?php } ?>
			 		<p class="perfil-nome"><?php echo $perfil['nome'] ?> <?php if($podeEditar){ echo '<a href="">✎ Editar</a>';} ?></p> 			
		 		</div>
		 		<?php if(!$podeEditar){ ?>
		 		<div>
		 			<a href="<?php echo INCLUDE_PATH ?>mensagens?enviar=<?php echo $perfil['id'] ?>">💬 Mensagem</a>
		 		</div><?php } ?>
 			</div>
 		</div><!-- perfil-box-wraper -->
 	</div>
 	<div class="box50 boxleft">
 		<div class="box">
 			<?php if($podeEditar && $usuario['confirma_email'] != 'confirmado'){ ?>
 				<div class="nao_confirmado">
 					<p>Seu e-mail ainda não foi confirmado</p>
 				</div>
 			<?php }
 				if($podeEditar){ ?>
				<div class="editar"> 
					<a href=""><p>✎ Editar</p></a>
				</div>
				<div class="clear"></div>
				<div class="perfil">
					<p><b>E-mail:</b> <?php echo $usuario['email'] ?></p>
					<p><b>Tipo:</b> <?php echo ucwords($usuario['tipo_login'])  ?></p>
					<p><b>CPF / CNPJ:</b> <span>(Seu CPF/CNPJ fica em OCULTO quando visto por outras pessoas.)</span><br> <?php echo $usuario['cpf_cnpj']  ?></p>
					<p><b>Endereço:</b> <span>(Seu ENDEREÇO fica em OCULTO quando visto por outras pessoas.)</span><br><?php echo $usuario['endereco']  ?></p>
					<p><b>Carteirinha de Membro:</b><span>(Sua CARTEIRINHA DE MEMBRO fica em OCULTO quando visto por outras pessoas.)</span><br> <?php echo $usuario['carteirinha']  ?></p>
					<p><b>Filial:</b> <?php echo $filial_dados['nome']  ?></p>
					<p><b>Descrição:</b> <?php echo $usuario['descricao']  ?></p>
				</div>
 			<?php } else{?>
				<div class="clear"></div>
				<div class="perfil">
					<p><b>Filial:</b> <?php echo $filial_dados_perfil['nome']  ?></p>
					<p><b>Descrição:</b> <?php echo $perfil['descricao']  ?></p>
				</div>
 			<?php } ?>
 		</div>

		<?php if($podeEditar){ ?>
 			<a href="" class="click-altera-senha"><p>ALTERAR SENHA</p></a>
		<?php }?>

 		<div class="box">
 			<h2>Suas Empresas</h2>
 			<?php 
 				$podeListarEmpresas = false;
	 			if($podeEditar){
					$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.user` WHERE `user_id` = ?");
					$sql->execute(array($usuario['id']));
					if($sql->rowCount() <= 0){
						padrao::alert('atencao','Você ainda não tem nenhuma empresa cadastrada');
					}
					else{
						$listarEmpresasUser = $sql->fetchAll();
						$podeListarEmpresas = true;
					}
 			 	}
 			 	else{
					$sql2 = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.user` WHERE `user_id` = ?");
					$sql2->execute(array($perfil['id']));
					if($sql2->rowCount() <= 0){
						padrao::alert('atencao','Este Perfil ainda não tem nenhuma empresa cadastrada');
					}
					else{
						$listarEmpresasUser = $sql2->fetchAll();
						$podeListarEmpresas = true;
					}
				}

				if($podeListarEmpresas){

	 			 	foreach ($listarEmpresasUser as $key => $value) {
	 			 		$empresa_id = $value['empresa_id'];
	 			 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa` WHERE `id` = $empresa_id");
	 			 		$sql->execute();
	 			 		$empresa_dados = $sql->fetch();
	 			 		$sql->execute();

	 			 		$criador_id = $empresa_dados['id_criador'];
	 			 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = $criador_id");
	 			 		$sql->execute();
	 			 		$empresa_criador = $sql->fetch();
	 			 		if($podeEditar){
	 			 			$user_id = $usuario['id'];
	 			 			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = $user_id"); 			 			
	 			 		}
	 			 		else{
	 			 			$user_id = $perfil['id'];
	 			 			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = $user_id"); 			 			
	 			 		}
	 		?>
 			<div class="empresa-user-single" href="<?php echo $empresa_dados['slug']; ?>">
 				<img src="<?php echo INCLUDE_PATH ?>uploads/<?php echo $empresa_dados['imagem'] ?>">
	 			<h3><?php echo $empresa_dados['nome'] ?></h3>
	 			<p><b>Proprietário:</b> <?php echo $empresa_criador['nome'] ?></p>
 			</div>
		 	<?php }
 			 	}	
 			 	if($podeEditar){
 			 ?>
 			 <a href="<?php echo INCLUDE_PATH ?>cadastrar-empresa" class="cadastrar-empresa"><p>Cadastrar uma nova empresa</p></a>
 			<?php } ?>
 		</div>
 	</div>
 	<div class="box50 boxright">
 		<!-- PARTE DE POSTS -->
 		<?php 
			$paginaAtual = 1;
			$porPagina = 5;
			$paginaProxima = ($paginaAtual - 1)*$porPagina;

			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_post` WHERE `origem_tipo` = ? AND `origem_id` = ? AND `resposta_id` = ? ORDER BY `id` DESC LIMIT $paginaProxima,$porPagina");
			if($podeEditar){
				$sql->execute(array(0,$usuario['id'],0));
			}
			else{
				$sql->execute(array(0,$perfil['id'],0));				
			}
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

<div class="edita-login">
	<div class="box">
		<img src="<?php echo INCLUDE_PATH; ?>images/close01_tamanho_01.png">
		<div class="clear"></div>
		<h2>Alterar Imagem</h2>
		<div class="edita-login-img">
			<?php if($perfil['img_perfil'] == ''){ ?>
				<img id="editar-imagem-img" src="<?php INCLUDE_PATH ?>images/clientevazio.JPG">
			<?php } else{ ?>
				<img id="editar-imagem-img" src="<?php echo INCLUDE_PATH ?>/uploads/<?php echo $perfil['img_perfil'] ?>">
			<?php } ?>
		</div>
		<form method="post" action="<?php INCLUDE_PATH ?>ajax/atualiza_perfil.php" class="form-altera-imagem-perfil" enctype="multipart/form-data">
			<input type="file" name="imagem_nova" id="editar-imagem-alterar"onchange="document.getElementById('editar-imagem-img').src = window.URL.createObjectURL(this.files[0])">
			<input type="hidden" name="imagem_atual" value="<?php echo $usuario['img_perfil'] ?>">
			<input type="hidden" name="user_id" value="<?php echo $usuario['id']; ?>">
			<input type="hidden" name="user_login" value="<?php echo $usuario['login']; ?>">
			<input type="hidden" name="nome_tabela" value="tb_user">
		</form>
		<h2>Alterar Nome</h2>
		<form method="post" action="<?php INCLUDE_PATH ?>ajax/atualiza_perfil.php" class="form-altera-nome-perfil" enctype="multipart/form-data">
			<input type="text" name="nome" value="<?php echo $usuario['nome'] ?>">
			<input type="hidden" name="imagem_atual" value="<?php echo $usuario['img_perfil'] ?>">
			<input type="hidden" name="user_id" value="<?php echo $usuario['id']; ?>">
			<input type="hidden" name="user_login" value="<?php echo $usuario['login']; ?>">
			<input type="hidden" name="nome_tabela" value="tb_user">
		</form>
	</div>
</div>

<div class="altera-senha">
	<div class="box">
		<img src="<?php echo INCLUDE_PATH; ?>images/close01_tamanho_01.png">
		<h2>Alterar Senha</h2>
		<form method="post" action="<?php INCLUDE_PATH ?>ajax/atualiza_perfil.php" class="form-altera-senha" enctype="multipart/form-data">
			<input type="password" name="senha_antiga" autocomplete="off" placeholder="Senha Antiga">
			<input type="password" name="senha_nova" autocomplete="off" placeholder="Senha Nova">
			<input type="password" name="confirma_senha" autocomplete="off" placeholder="Confirmar Senha Nova">
			<input type="submit" name="acao-troca-senha">
			<input type="hidden" name="user_id" value="<?php echo $usuario['id']; ?>">
			<input type="hidden" name="user_login" value="<?php echo $usuario['login']; ?>">
			<input type="hidden" name="nome_tabela" value="tb_user">
		</form>
		<a href="" class="esqueceu-senha" user_id="<?php echo $usuario['id'] ?>" user_name="<?php echo $usuario['nome'] ?>" user_email="<?php echo $usuario['email'] ?>">Esqueceu sua senha atual?</a>
	</div>
</div>

<div class="loading">
	<div class="box">
		<img src="<?php echo INCLUDE_PATH; ?>images/loading.png">
	</div>
</div>

<div class="mostra-imagem">
	<div class="box">
		<img src="<?php echo INCLUDE_PATH; ?>images/close01_tamanho_01.png">
		<div class="mostra-image-img">
			<?php if($perfil['img_perfil'] == ''){ ?>
				<img id="editar-imagem-img" src="<?php INCLUDE_PATH ?>images/clientevazio.JPG">
			<?php } else{ ?>
				<img id="editar-imagem-img" src="<?php echo INCLUDE_PATH ?>/uploads/<?php echo $perfil['img_perfil'] ?>">
			<?php } ?>
		</div>
	</div>
</div>

<div class="edita-perfil">
	<div class="box">	
		<img src="<?php echo INCLUDE_PATH; ?>images/close01_tamanho_01.png">
		<div class="clear"></div>	
		<h2>Alterar Perfil</h2>
		<form method="post" action="<?php INCLUDE_PATH ?>ajax/atualiza_perfil.php" class="form-altera-perfil" enctype="multipart/form-data">

			<div class="form-group">
				<label>E-mail: </label>
				<input type="text" name="email" value="<?php echo $usuario['email'] ?>">				
			</div>

			<div class="form-group">
				<label>Tipo: </label>
				<select name="tipo_login">
					<option value="fisico" <?php if($usuario['tipo_login'] == 'fisico'){ echo 'selected'; } ?>>Físico</option>
					<option value="juridico" <?php if($usuario['tipo_login'] == 'juridico'){ echo 'selected'; } ?>>Jurídico</option>
				</select>			
			</div>

			<div class="form-group">
				<label>CPF: </label>
				<input type="text" name="cpf" placeholder="CPF" value="<?php echo $usuario['cpf_cnpj'] ?>">
			</div>

			<div class="form-group">
				<label>CNPJ: </label>
				<input type="text" name="cnpj" placeholder="CNPJ" value="<?php echo $usuario['cpf_cnpj'] ?>">	
			</div>

			<div class="form-group">
				<label>Endereço: </label>
				<input type="text" name="endereco" value="<?php echo $usuario['endereco'] ?>">				
			</div>

			<div class="form-group">
				<label>Carteirinha de Membro: </label>
				<input type="text" name="carteirinha" value="<?php echo $usuario['carteirinha'] ?>">				
			</div>

			<div class="form-group">
				<label>Filial: </label>
				<select name="filial">
					<option disabled>Selecione uma filial</option>
					<?php
						$sql = MySql::conectar()->prepare("SELECT * FROM `tb_filial`");
						$sql->execute();
						$listarFilial = $sql->fetchAll();
						foreach ($listarFilial as $key => $value) { ?>
							<option value="<?php echo $value['id']; ?>" <?php if($usuario['filial_id'] == $value['id']){ echo 'selected'; } ?>><?php echo $value['nome']; ?></option>
					<?php } ?>
				</select>
			</div>

			<div class="form-group">
				<label>Descricao: </label>
				<textarea name="descricao"><?php echo $usuario['descricao'] ?></textarea>			
			</div>

			<div class="form-group">
				<input type="submit" name="editar-perfil" value="ATUALIZAR">				
			</div>

			<input type="hidden" name="user_id" value="<?php echo $usuario['id']; ?>">
			<input type="hidden" name="email_antigo" value="<?php echo $usuario['email']; ?>">
			<input type="hidden" name="nome" value="<?php echo $usuario['nome']; ?>">
			<input type="hidden" name="user_login" value="<?php echo $usuario['login']; ?>">
			<input type="hidden" name="nome_tabela" value="tb_user">
		</form>
	</div>
</div>