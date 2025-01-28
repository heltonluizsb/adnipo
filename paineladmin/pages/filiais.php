<?php 
	$sql = MySql::conectar()->prepare("SELECT * FROM `tb_filial` ORDER BY `nome`");
	$sql->execute();
	$tb_filiais = $sql->fetchAll();
 ?>
<div class="box-content left w100">
	<h2>Filiais</h2>
	<table class="tabela_filiais">
		<tr>
			<th>Nome</th>
			<th>Alterar</th>
			<th>Excluir</th>
		</tr>
		<?php foreach ($tb_filiais as $key => $value) { ?>
		<tr>
			<td><input type="text" name="filial_<?php echo $value['id'] ?>" value="<?php echo strtoupper($value['nome']) ?>"></td>
			<td><a href="<?php echo INCLUDE_PATH_PANEL ?>" name="filial_<?php echo $value['id'] ?>" filial_id="<?php echo $value['id'] ?>" class="alterar">ALTERAR</a></td>
			<td><a href="<?php echo INCLUDE_PATH_PANEL ?>" name="filial_<?php echo $value['id'] ?>" filial_id="<?php echo $value['id'] ?>" class="excluir">EXCLUIR</a></td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="3" class="td-cadastrar-filial"><a href="">CADASTRAR FILIAL</a></td>
		</tr>
		<tr class="cadastrando-filial">
			<td><input type="text" name="filial_nova"></td>
			<td colspan="2"><a href="">CADASTRAR</a></td>
		</tr>
	</table>
</div>
<div class="clear"></div><!-- clear -->