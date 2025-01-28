<?php 
	$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` ORDER BY `nome`");
	$sql->execute();
	$tb_user = $sql->fetchAll();
	$encoding = mb_internal_encoding();
 ?>
<div class="box-content left w100">
	<h2>Usuários</h2>
	<table class="tabela_user">
		<tr>
			<th>Login</th>
			<th>Nome</th>
			<th>Alterar Senha</th>
			<th>Excluir</th>
		</tr>
		<?php foreach ($tb_user as $key => $value) {?>
		<tr>
			<td><?php echo strtoupper($value['login']) ?></td>
			<td><?php echo mb_strtoupper($value['nome'],$encoding) ?></td>
			<td>
				<a href="<?php echo INCLUDE_PATH_PANEL ?>" user_id="<?php echo $value['id'] ?>" class="alterar">ALTERAR</a>
				<form method="post" class="user_<?php echo $value['id'] ?>" action="<?php echo INCLUDE_PATH_PANEL ?>ajax/usuarios.ajax.php">
					<input type="password" name="nova_senha" placeholder="Nova Senha...">
					<input type="password" name="confirma_senha" placeholder="Confirma Nova Senha...">
					<input type="submit" name="acao_alterar" value="ALTERAR SENHA">
					<input type="hidden" name="user_id" value="<?php echo $value['id'] ?>">
				</form>
			</td>
			<td><a href="<?php echo INCLUDE_PATH_PANEL ?>" user_id="<?php echo $value['id'] ?>" class="excluir">EXCLUIR</a></td>
		</tr>
		<?php } ?>
	</table>
</div>
<div class="clear"></div><!-- clear -->