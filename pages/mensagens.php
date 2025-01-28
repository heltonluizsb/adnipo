<?php
	$estalogado=false;
	if(isset($_COOKIE['user'])){
		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `login` = ?");
		$sql->execute(array($_COOKIE['user']));
		if($sql->rowCount() >= 1){
			$estalogado = true;
			$usuario = $sql->fetch();
		}
		else{
			padrao::alert('erro','Problemas com esse usuário');
			die();
		}
	}

 ?>
<section class="section-messenger">
 	<div class="box30 boxleft box-messenger">
 		<div class="box">
 			<?php 
 				if(!$estalogado){
					padrao::alert('erro','Você não está logado');
					die(); 					
 				}

 				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_mensagem` WHERE `destinatario_id` = ? OR `remetente_id` = ? GROUP BY `destinatario_id`");
 				$sql->execute(array($usuario['id'],$usuario['id']));
 				if(($sql->rowCount() == 0) && (!isset($_GET['enviar']))){
 					padrao::alert('atencao','Você não tem nenhuma mensagem');
 				}
 				else if(isset($_GET['enviar'])){
	 				$sql2 = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
	 				$sql2->execute(array($_GET['enviar']));
	 				if($sql2->rowCount() == 0){
	 					padrao::alert('erro','Usuário não existe');
	 				}
	 				else{
	 					$remetente_dados = $sql2->fetch(); ?>
	 	<div class="remetente-single remetente-single-selected">
	 		<div class="remetente-single-img">
	 			<?php if($remetente_dados['img_perfil'] == ''){ ?>
	 			<img src="<?php echo INCLUDE_PATH ?>images/clientevazio.JPG">
	 			<?php }else{ ?>
	 			<img src="<?php echo INCLUDE_PATH ?>uploads/<?php echo $remetente_dados['img_perfil'] ?>">
	 			<?php } ?>
	 		</div>
	 		<div class="remetente-single-text">
	 			<p><?php echo $remetente_dados['nome'] ?></p>
	 		</div>
	 	</div><!-- remetente-single -->
	 		<?php 	}
 				} else if($sql->rowCount() > 0){
 					$user_mensagens = $sql->fetchAll();
 					$lista_remetente = [];
 					foreach ($user_mensagens as $key => $value) {
 						if($value['remetente_id'] != $usuario['id']){
 							$tem_na_lista = false;
 							foreach ($lista_remetente as $key => $value2) {
 								if($value2 == $value['remetente_id']){
 									$tem_na_lista = true;
 								}
 							}
 							if(!$tem_na_lista){
 							$lista_remetente[] = $value['remetente_id'];
	 						$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
	 						$sql->execute(array($value['remetente_id']));
	 						$user_mensagens_dados = $sql->fetch();?> 
	 	<div class="remetente-single" remetente_id="<?php echo $value['remetente_id'] ?>" user_id="<?php echo $usuario['id'] ?>">
	 		<div class="remetente-single-img">
	 			<?php if($user_mensagens_dados['img_perfil'] == ''){ ?>
	 			<img src="<?php echo INCLUDE_PATH ?>images/clientevazio.JPG">
	 			<?php }else{ ?>
	 			<img src="<?php echo INCLUDE_PATH ?>uploads/<?php echo $remetente_dados['img_perfil'] ?>">
	 			<?php } ?>
	 		</div>
	 		<div class="remetente-single-text">
	 			<p><?php echo $user_mensagens_dados['nome'] ?></p>
	 		</div>
	 	</div><!-- remetente-single -->							
 			<?php 		}} else{
 							$tem_na_lista = false;
 							foreach ($lista_remetente as $key => $value2) {
 								if($value2 == $value['destinatario_id']){
 									$tem_na_lista = true;
 								}
 							}
 							if(!$tem_na_lista){
 							$lista_remetente[] = $value['destinatario_id'];
	 						$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
	 						$sql->execute(array($value['destinatario_id']));
	 						$user_mensagens_dados = $sql->fetch();?>
	 	<div class="remetente-single" remetente_id="<?php echo $value['destinatario_id'] ?>" user_id="<?php echo $usuario['id'] ?>">
	 		<div class="remetente-single-img">
	 			<?php if($user_mensagens_dados['img_perfil'] == ''){ ?>
	 			<img src="<?php echo INCLUDE_PATH ?>images/clientevazio.JPG">
	 			<?php }else{ ?>
	 			<img src="<?php echo INCLUDE_PATH ?>uploads/<?php echo $user_mensagens_dados['img_perfil'] ?>">
	 			<?php } ?>
	 		</div>
	 		<div class="remetente-single-text">
	 			<p><?php echo $user_mensagens_dados['nome'] ?></p>
	 		</div>
	 	</div><!-- remetente-single -->							

 			<?php 		}
 				 	}}
 				}
 			 ?>
 		</div>
 	</div>
 	<div class="box70 boxright box-messenger">
 		<div class="box">
 			<div class="chat-area">
 				<div class="chat-area-wraper">
 					<?php
 						if(isset($remetente_dados)){
	 						$sql = MySql::conectar()->prepare("SELECT * FROM `tb_mensagem` WHERE (`remetente_id` = ? AND `destinatario_id` = ?) OR (`remetente_id` = ? AND `destinatario_id` = ?)");
	 						$sql->execute(array($remetente_dados['id'],$usuario['id'],$usuario['id'],$remetente_dados['id']));
	 						if($sql->rowCount() > 0){
	 							$mensagens_user = $sql->fetchAll();
		 						foreach ($mensagens_user as $key => $value) {
		 							if($value['remetente_id'] == $usuario['id']){?>
	 				<div class="chat-single-me">
	 					<div class="chat-single-text">
	 						<p><?php echo $value['mensagem'] ?></p>
	 					</div>
	 					<div class="chat-single-img">
	 						<?php if($usuario['img_perfil'] == ''){?>
	 						<img src="<?php echo INCLUDE_PATH ?>images/clientevazio.JPG">
	 						<?php }else{?>
	 						<img src="<?php echo INCLUDE_PATH ?>uploads/<?php echo $usuario['img_perfil'] ?>">
	 						<?php } ?>
	 					</div>
	 				</div><!-- chat-single-me -->
	 				<?php			} else{?>
	 				<div class="chat-single-other">
	 					<div class="chat-single-img">
	 						<?php if($remetente_dados['img_perfil'] == ''){?>
	 						<img src="<?php echo INCLUDE_PATH ?>images/clientevazio.JPG">
	 						<?php }else{?>
	 						<img src="<?php echo INCLUDE_PATH ?>uploads/<?php echo $remetente_dados['img_perfil'] ?>">
	 						<?php } ?>
	 					</div>
	 					<div class="chat-single-text">
	 						<p><?php echo $value['mensagem'] ?></p>
	 					</div>
	 				</div><!-- chat-single-other -->
	 				<?php 			}
	 							}
	 					  	}
 						}
 						else{ ?>
 					<semget verdadeiro="true">
 					<?php }?>
 				</div><!-- chat-area-wraper -->
 			</div><!-- chat-area -->
			<textarea class="autosize" name="post" placeholder="Digite sua mensagem..." user_img="<?php if($usuario['img_perfil'] == ''){echo INCLUDE_PATH.'images/clientevazio.JPG';}else{echo INCLUDE_PATH.'uploads/'.$usuario['img_perfil'];} ?>" user_id="<?php echo $usuario['id'] ?>" remetente_id="<?php if(isset($remetente_dados)){ echo $remetente_dados['id'];} ?>"></textarea>
 		</div>
 	</div><!-- box-messenger -->
 	<div class="clear"></div><!-- clear -->
</section>