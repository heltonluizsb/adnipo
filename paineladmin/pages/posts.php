<?php 
	$sql = MySql::conectar()->prepare("SELECT * FROM `tb_post` ORDER BY `id` DESC");
	$sql->execute();
	$tb_post = $sql->fetchAll();
 ?>
<div class="box-content left w100">
	<h2>Posts</h2>
	<table class="tabela_posts">
		<tr>
			<th>Post</th>
			<th>Data</th>
			<th>Horário</th>
			<th>Excluir</th>
		</tr>
		<?php foreach ($tb_post as $key => $value) { ?>
		<tr>
			<td><?php echo $value['post'] ?></td>
			<td><?php echo date("d/m/Y", strtotime($value['data'])) ?></td>
			<td><?php echo date("h:i:s", strtotime($value['data'])) ?></td>
			<td><a href="<?php echo INCLUDE_PATH_PANEL ?>" post_id="<?php echo $value['id'] ?>" class="excluir">EXCLUIR</a></td>
		</tr>
		<?php } ?>
	</table>
</div>
<div class="clear"></div><!-- clear -->