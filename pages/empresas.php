<?php 
	$categoriaSelecionada = @$_SESSION['home_categoria'];
	$_SESSION['home_categoria'] = null;

	if(isset($_GET['pesquisa_categoria'])){
		$categoriaSelecionada = $_GET['pesquisa_categoria'];
	}

	$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa`");
	$sql->execute();
	$listaEmpresas = $sql->fetchAll();
 ?>
<section class="section-empresas">
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
			<img src="<?php INCLUDE_PATH ?>images/empresa01_tamanho_01.png">
			<h2> Categorias</h2>
			<form method="post" class="categorias" action="<?php INCLUDE_PATH ?>ajax/empresas.ajax.php">
				<?php 
					$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` ORDER BY `nome`");
					$sql->execute();
					$listaCategoria = $sql->fetchAll();
					foreach ($listaCategoria as $key => $value) { ?>
				<div>
					<input type="checkbox" name="<?php echo $value['id'] ?>" <?php if($categoriaSelecionada == $value['id']){echo 'checked';} ?>>
					<label><?php echo strtoupper($value['nome']);  ?></label>
				</div>
				<?php } ?>
			</form>			
		</div>
	</div><!-- section-left -->
	<div class="section-center">
		<?php 
			if($categoriaSelecionada == ''){
			foreach ($listaEmpresas as $key => $value) { ?>
		<div class="empresa-single">
			<div class="box box-single-goto" href="<?php echo INCLUDE_PATH ?>perfil_empresa/<?php echo $value['slug'] ?>">
				<img src="<?php echo INCLUDE_PATH ?>uploads/<?php echo $value['imagem'] ?>">
				<h3><?php echo $value['nome']; ?></h3>
				<div class="empresa-single-line"></div>
	 			<h2>Categorias</h2>
	 			<ul>
	 				<?php
	 					$empresa_id = $value['id'];
	 			 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.categoria` WHERE `id_empresa` = $empresa_id");
	 			 		$sql->execute();
	 			 		$categorias_empresa = $sql->fetchAll();
	 			 		foreach ($categorias_empresa as $key => $value) {
	 			 			$categoria_id = $value['id_categoria'];	 					
		 			 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` WHERE `id` = $categoria_id");
		 			 		$sql->execute();
		 			 		$categoria_dados = $sql->fetch();?>
		 			 	<li><?php echo strtoupper($categoria_dados['nome']) ?></li>
	 			 	<?php } ?>
	 			</ul>				
			</div>
		</div><!-- empresa-single -->
		<?php }
			} else{

				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.categoria` WHERE `id_categoria` = ?");
				$sql->execute(array($categoriaSelecionada));
				$empresa_categoria = $sql->fetchAll();

				foreach ($listaEmpresas as $key => $value) {
					$empresa_na_lista = false;
					foreach ($empresa_categoria as $key2 => $value2) {
						if($value2['id_empresa'] == $value['id']){
							$empresa_na_lista = true;
						}
					}

					if($empresa_na_lista){ ?>

		<div class="empresa-single">
			<div class="box" href="<?php echo INCLUDE_PATH ?>perfil_empresa/<?php echo $value['slug'] ?>">
				<img src="<?php echo INCLUDE_PATH ?>uploads/<?php echo $value['imagem'] ?>">
				<h3><?php echo $value['nome']; ?></h3>
				<div class="empresa-single-line"></div>
	 			<h2>Categorias</h2>
	 			<ul>
				<?php 
					$empresa_id = $value['id'];
			 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_empresa.categoria` WHERE `id_empresa` = $empresa_id");
			 		$sql->execute();
			 		$categorias_empresa = $sql->fetchAll();
			 		foreach ($categorias_empresa as $key => $value) {
			 			$categoria_id = $value['id_categoria'];	 					
				 		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` WHERE `id` = $categoria_id");
				 		$sql->execute();
				 		$categoria_dados = $sql->fetch();?>
		 			 	<li><?php echo strtoupper($categoria_dados['nome']) ?></li>
	 			 	<?php } ?>
	 			</ul>				
			</div>
		</div><!-- empresa-single -->

				<?php }
				}
			}?>
	</div><!-- section-center -->
	<div class="section-right"></div><!-- section-right -->
	<div class="clear"></div>	
</section>