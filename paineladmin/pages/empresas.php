<?php 
	$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa` ORDER BY `nome`");
	$sql->execute();
	$tb_empresa = $sql->fetchAll();
 ?>
<div class="box-content left w100">
	<h2>Empresas</h2>
	<table class="tabela_empresas">
		<tr>
			<th>Nome</th>
			<th>Proprietário</th>
			<th>Alterar</th>
			<th>Excluir</th>
		</tr>
		<?php foreach ($tb_empresa as $key => $value) {
			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.user` WHERE `empresa_id` = ?");
			$sql->execute(array($value['id']));
			$tb_empresa_users = $sql->fetchAll(); ?>
		<tr>
			<td><?php echo strtoupper($value['nome']) ?></td>
			<td><select name="empresa_proprietario" empresa_id="<?php echo $value['id'] ?>">
				<?php foreach ($tb_empresa_users as $key2 => $value2) {
						$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `id` = ?");
						$sql->execute(array($value2['user_id']));
						$tb_empresa_user_dados = $sql->fetch();?>
					<option value="<?php echo $tb_empresa_user_dados['id'] ?>" <?php if($value['id_criador'] == $value2['user_id']){ echo 'selected';} ?>><?php echo $tb_empresa_user_dados['nome'];  ?></option>
				<?php } ?>
			</select></td>
			<td><a href="<?php echo INCLUDE_PATH_PANEL ?>" empresa_id="<?php echo $value['id'] ?>" class="alterar">ALTERAR</a></td>
			<td><a href="<?php echo INCLUDE_PATH_PANEL ?>" empresa_id="<?php echo $value['id'] ?>" class="excluir">EXCLUIR</a></td>
		</tr>
		<?php } ?>
	</table>
</div>
<div class="clear"></div><!-- clear -->