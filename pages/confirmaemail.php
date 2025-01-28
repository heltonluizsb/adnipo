<section class="section-login">
	<div class="section-login-wraper">
		<div class="section-login-logo">
			<img src="<?php echo INCLUDE_PATH; ?>images/logo01_forbusiness.png">
		</div>
		<div class="box">
			<?php 
				if(isset($url_explode[1]) && $url_explode[1] != ''){	
					$confirmaemail = $url_explode[1];				
					$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `confirma_email` = ?");
					$sql->execute(array($confirmaemail));
					if($sql->rowCount() >= 1){
						$perfil = $sql->fetch();
						$user_id = $perfil['id'];
						$sql = MySql::conectar()->prepare("UPDATE `tb_user` SET `confirma_email` = ? WHERE id = ?");
						$sql->execute(array('confirmado',$user_id));
					}
					else{						
						padrao::alert('erro','Não existe este código');
						die();
					}
				}
				else{						
					padrao::alert('erro','Código Inexistente');
					die();

				}
			?>
			<h2>Confirmação de E-mail</h2>				
			<?php 				
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
				$sql->execute(array($user_id));
				$perfil = $sql->fetch();
			padrao::alert('sucesso','Parabéns '.$perfil['nome'].', Status da Confirmação de E-mail: '.$perfil['confirma_email']); ?>
			<a href="<?php echo INCLUDE_PATH; ?>">Voltar para página principal</a>
		</div>
	</div>
</section>