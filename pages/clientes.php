<?php
	if(isset($_GET['pesquisa_filial'])){
		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `filial_id` = ?");
		$sql->execute(array($_GET['pesquisa_filial']));

	}else{
		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user`");
		$sql->execute();
	}
	$listaUsers = $sql->fetchAll();
 ?>
<section class="section-empresas section-clientes">
	<div class="section-left">
		<img class="section-left-mobile-close" src="<?php INCLUDE_PATH ?>images/close01_tamanho_01.png">
		<div class="section-left-menu-desktop">
			<a href="<?php echo INCLUDE_PATH; ?>home"><div class="header-menu-desktop-single <?php echo padrao::paginaSelecionada($url,'home')?>">
				<img src="<?php echo padrao::imagemPaginaSelecionada($url,'home'); ?>">
				<div class="header-menu-desktop-titulo"><p>Home</p></div>
			</div></a>
			<a href="<?php echo INCLUDE_PATH; ?>empresas"><div class="header-menu-desktop-single <?php echo padrao::paginaSelecionada($url,'empresas')?>">
				<img src="<?php echo padrao::imagemPaginaSelecionada($url,'empresas'); ?>">
				<div class="header-menu-desktop-titulo"><p>Empresas</p></div>
			</div></a>
			<a href="<?php echo INCLUDE_PATH; ?>clientes"><div class="header-menu-desktop-single <?php echo padrao::paginaSelecionada($url,'clientes')?>">
				<img src="<?php echo padrao::imagemPaginaSelecionada($url,'clientes'); ?>">
				<div class="header-menu-desktop-titulo"><p>Clientes</p></div>
			</div></a>
		</div>
		<div class="box">
			<img src="<?php INCLUDE_PATH ?>images/clientes01_tamanho_01.png">
			<h2> Filiais</h2>
			<form method="post" class="filiais" action="<?php INCLUDE_PATH ?>ajax/clientes.ajax.php">
				<?php 
					$sql = MySql::conectar()->prepare("SELECT * FROM `tb_filial`");
					$sql->execute();
					$listaFilial = $sql->fetchAll();
					foreach ($listaFilial as $key => $value) { ?>
				<div>
					<input type="checkbox" name="<?php echo $value['id'] ?>" <?php if(isset($_GET['pesquisa_filial']) && $_GET['pesquisa_filial'] == $value['id']){echo 'checked';} ?>>
					<label><?php echo strtoupper($value['nome']);  ?></label>
				</div>
				<?php } ?>
			</form>			
		</div>
		<br>
		<div class="box">
			<img src="<?php INCLUDE_PATH ?>images/empresa01_tamanho_01.png">
			<h2> A Procura de</h2>
			<form method="post" class="categorias-cliente" action="<?php INCLUDE_PATH ?>ajax/clientes.ajax.php">
				<?php 
					$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` ORDER BY `nome`");
					$sql->execute();
					$listaCategoria = $sql->fetchAll();
					foreach ($listaCategoria as $key => $value) { ?>
				<div>
					<input type="checkbox" name="<?php echo $value['id'] ?>">
					<label><?php echo strtoupper($value['nome']);  ?></label>
				</div>
				<?php } ?>
				<div>
					<input type="checkbox" name="outros">
					<label>Outros</label>
				</div>
			</form>			
		</div>
	</div><!-- section-left -->
	<div class="section-center">
		<?php 
			foreach ($listaUsers as $key => $value) { 
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_filial` WHERE `id` = ?");
				$sql->execute(array($value['filial_id']));
				if($sql->rowCount() > 0){
					$filial_dados_user = $sql->fetch();
				}
				else{
					$filial_dados_user['nome'] = 'NÃO PREENCHEU A FILIAL';
				}
		?>
		<div class="empresa-single">
			<div class="box" href="<?php echo INCLUDE_PATH ?>perfil/<?php echo $value['login'] ?>">
				<?php if($value['img_perfil'] == ''){ ?>
				<img src="<?php echo INCLUDE_PATH ?>images/clientevazio.JPG">
			<?php }else{ ?>
				<img src="<?php echo INCLUDE_PATH ?>uploads/<?php echo $value['img_perfil'] ?>">
			<?php } ?>
				<h3><?php echo $value['nome']; ?></h3>
				<div class="empresa-single-line"></div>
	 			<h2><?php echo $filial_dados_user['nome'] ?></h2>
			</div>
		</div><!-- empresa-single -->
			<?php } ?>
	</div><!-- section-center -->
	<div class="section-right"></div><!-- section-right -->
	<div class="clear"></div>	
</section>