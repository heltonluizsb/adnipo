<?php 
	if(isset($_COOKIE['user'])){
		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `login` = ?");
		$sql->execute(array($_COOKIE['user']));
		if($sql->rowCount() > 0){
			$validaLogin = $sql->fetch();
			echo padrao::redirecionaPagina('perfil/'.$validaLogin["login"]);
			die();
		}
	}
 ?>
<section class="section-login">
	<div class="section-login-wraper">
		<div class="section-login-logo">
			<img src="<?php echo INCLUDE_PATH; ?>images/logo01_forbusiness.png">
		</div>
		<div class="box">
			<form method="post">
				<?php 
					if(isset($_POST['acao'])){
						$login = $_POST['login'];
						$senha = $_POST['password'];

						$validaLogin = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `login` = ? AND `senha` = ?");
						$validaLogin->execute(array($login,$senha));
						if($validaLogin->rowCount() > 0){
							$validaLogin = $validaLogin->fetch();
							setcookie('user',$validaLogin['login'],time()+(60*60*24*30),'/');
							setcookie('senha',$validaLogin['senha'],time()+(60*60*24*30),'/');
							echo padrao::redirecionaPagina('');
							die();
						}
					}
				 ?>
				<input type="text" name="login" autocomplete="username" placeholder="LOGIN" value="<?php if(isset($_COOKIE['user'])){ echo $_COOKIE['user'];} ?>">
				<input type="password" name="password"autocomplete="current-password" placeholder="SENHA" value="<?php if(isset($_COOKIE['senha'])){ echo $_COOKIE['senha'];} ?>">
				<input type="submit" name="acao" value="Entrar">
			</form>	
			<a href="" class="link-esqueceu-senha">ESQUECEU A SENHA?</a>
			<div class="section-login-logo-line"></div>	
			<a href="" class="section-login-btn">CRIAR NOVA CONTA</a>
		</div>
	</div>
	<div class="cria-login <?php if(isset($_POST['acao_cadastro_login'])) echo 'mostra-cadastro' ?>">
		<div class="box">
			<img src="<?php echo INCLUDE_PATH; ?>images/close01_tamanho_01.png">
			<div class="clear"></div>
			<form method="post" action="<?php echo INCLUDE_PATH ?>ajax/cadastro_user.ajax.php" class="ajax">
				<input type="text" name="login" autocomplete="username" placeholder="LOGIN">
				<input type="email" name="email" placeholder="E-MAIL">
				<input type="text" name="nome" placeholder="NOME COMPLETO OU RAZÃO SOCIAL">
				<select name="tipo_login">
					<option value="fisico">Físico</option>
					<option value="juridico">Jurídico</option>
				</select>

				<div class="form-group">
					<label><span><br>(Seu CPF/CNPJ aparecerá somente para você, na pagina de Perfil. Para as demais pessoas ficará em oculto.)</span></label>
				</div>

				<input type="text" name="cpf" placeholder="CPF">
				<input type="text" name="cnpj" placeholder="CNPJ">

				<input type="text" name="endereco" placeholder="Endereço">

				<div class="form-group">
					<label>CARTEIRINHA DE MEMBRO <span><br>(Caso não tenha em mãos, escrever 'não tem')</span></label>
					<input type="text" name="carteirinha">					
				</div>

				<select name="filial" class="select_filial">
					<option disabled selected>Selecione uma filial</option>
					<?php
						$sql = MySql::conectar()->prepare("SELECT * FROM `tb_filial`");
						$sql->execute();
						$listarFilial = $sql->fetchAll();
						foreach ($listarFilial as $key => $value) { ?>
							<option value="<?php echo $value['id']; ?>"><?php echo $value['nome']; ?></option>
					<?php } ?>
					<option value="Nova Filial">Nova Filial</option>
				</select>
				<input type="text" name="cadastrar_filial" placeholder="CADASTRAR FILIAL">
				<input type="password" name="senha" autocomplete="current-password" placeholder="SENHA">
				<input type="password" name="confirmasenha" autocomplete="current-password" placeholder="CONFIRMAR SENHA">
				<input type="hidden" name="nome_tabela" value="tb_user">
				<input type="hidden" name="tipo_acao" value="cadastrar_user" required>
				<input type="submit" name="acao_cadastro_login" value="CRIAR CONTA">
			</form>
		</div>		
	</div>
	<div class="login-esqueceu-senha">
		<div class="box">
			<img src="<?php echo INCLUDE_PATH; ?>images/close01_tamanho_01.png">
			<form method="post" action="<?php echo INCLUDE_PATH ?>ajax/altera_senha.ajax.php" class="form-esqueceu-senha-pagina-login">
				<div class="form-group">
					<label>Insira seu e-mail</label>
					<input type="email" name="email">
					<input type="submit" name="acao_confirma_email">
				</div>
			</form>
		</div>
	</div>

	<div class="loading">
		<div class="box">
			<img src="<?php echo INCLUDE_PATH; ?>images/loading.png">
		</div>
	</div>
</section>