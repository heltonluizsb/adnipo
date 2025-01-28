<section class="section-login">
	<div class="section-login-wraper">
		<div class="section-login-logo">
			<img src="<?php echo INCLUDE_PATH; ?>images/logo01_forbusiness.png">
		</div>
		<div class="box">
			<?php 
				if(isset($url_explode[1]) && $url_explode[1] != ''){	
					$confirmaemail = $url_explode[1];				
					$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `troca_senha` = ?");
					$sql->execute(array($confirmaemail));
					if($sql->rowCount() >= 1){
						$perfil = $sql->fetch();
						$user_id = $perfil['id'];
					}
					else{						
						padrao::alert('erro','Não existe este código');
						die();
					}
				}
				else{						
					padrao::alert('erro','Código Em Branco');
					die();

				}
			?>
			<h2>Alteração de Senha</h2>				
			<?php 				
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
				$sql->execute(array($user_id));
				$perfil = $sql->fetch();
			?>
			<form class="form-altera-senha-por-email" method="post" action="<?php echo INCLUDE_PATH; ?>ajax/altera_senha.ajax.php">
				<input type="password" name="nova_senha" placeholder="Insira uma nova senha">
				<input type="password" name="confirma_senha" placeholder="Confirme sua nova senha">
				<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
				<input type="hidden" name="user_login" value="<?php echo $perfil['login']; ?>">
				<input type="submit" name="acao_altera_senha">
			</form>
		</div>
	</div>
</section>