<?php 
	$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` ORDER BY `nome`");
	$sql->execute();
	$tb_categoria = $sql->fetchAll();
 ?>
<div class="box-content left w100">
	<h2>Categorias</h2>
	<table class="tabela_categorias">
		<tr>
			<th>Nome</th>
			<th>Alterar</th>
			<th>Excluir</th>
		</tr>
		<?php foreach ($tb_categoria as $key => $value) { ?>
		<tr>
			<td><input type="text" name="categoria_<?php echo $value['id'] ?>" value="<?php echo strtoupper($value['nome']) ?>"></td>
			<td><a href="<?php echo INCLUDE_PATH_PANEL ?>" name="categoria_<?php echo $value['id'] ?>" categoria_id="<?php echo $value['id'] ?>" class="alterar">ALTERAR</a></td>
			<td><a href="<?php echo INCLUDE_PATH_PANEL ?>" name="categoria_<?php echo $value['id'] ?>" categoria_id="<?php echo $value['id'] ?>" class="excluir">EXCLUIR</a></td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="3" class="td-cadastrar-categoria"><a href="">CADASTRAR CATEGORIA</a></td>
		</tr>
		<tr class="cadastrando-categoria">
			<td><input type="text" name="categoria_nova"></td>
			<td colspan="2"><a href="">CADASTRAR</a></td>
		</tr>
	</table>
</div>
<div class="clear"></div><!-- clear -->