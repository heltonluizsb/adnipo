<?php 
	$sql = MySql::conectar()->prepare("SELECT * FROM tb_user");
	$sql->execute();
	$tb_user = $sql->fetchAll();

	$sql = MySql::conectar()->prepare("SELECT * FROM tb_empresa");
	$sql->execute();
	$tb_empresa = $sql->fetchAll();

	$sql = MySql::conectar()->prepare("SELECT * FROM tb_empresa");
	$sql->execute();
	$tb_filial = $sql->fetchAll();
 ?>
<div class="box-content left w100">
	<h2>Painel de Controle - ADNIPO</h2>
	<div class="content-wraper">
		<div class="content-usuarios-online">
			<h2>Usuários Cadastrados</h2>
			<p><?php echo count($tb_user); ?></p>
		</div>
		<div class="content-total-visitas">
			<h2>Empresas Cadastradas</h2>
			<p><?php echo count($tb_empresa); ?></p>
		</div>
		<div class="content-visitas-hoje">
			<h2>Filiais Cadastradas</h2>
			<p><?php echo count($tb_filial); ?></p>
		</div>
	</div>
</div>

<div class="clear"></div><!-- clear -->