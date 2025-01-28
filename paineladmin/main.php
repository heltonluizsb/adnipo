<?php
if(isset($_GET['logout'])){
	padrao::logout();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Painel de Controle</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<link href="<?php echo INCLUDE_PATH_PANEL; ?>css/style.css" type="text/css" rel="stylesheet" />
</head>
<body>
	<base href_panel="<?php echo INCLUDE_PATH_PANEL ?>" href="<?php echo INCLUDE_PATH ?>">
<?php 
	if(isset($_SESSION['paineladmin_user'])){
?>
		<aside>
			<ul>
				<li class="<?php selecionadoMenu(''); ?> aside-li-opcao"><a href="<?php echo INCLUDE_PATH_PANEL; ?>"><i class="fa fa-home"></i> Home</a></li>
				<li class="<?php selecionadoMenu('categorias'); ?> aside-li-opcao"><a href="<?php echo INCLUDE_PATH_PANEL; ?>categorias">Categorias</a></li>
				<li class="<?php selecionadoMenu('empresas'); ?> aside-li-opcao"><a href="<?php echo INCLUDE_PATH_PANEL; ?>empresas">Empresas</a></li>
				<li class="<?php selecionadoMenu('filiais'); ?> aside-li-opcao"><a href="<?php echo INCLUDE_PATH_PANEL; ?>filiais">Filiais</a></li>
				<li class="<?php selecionadoMenu('posts'); ?> aside-li-opcao"><a href="<?php echo INCLUDE_PATH_PANEL; ?>posts">Posts</a></li>
				<li class="<?php selecionadoMenu('usuarios'); ?> aside-li-opcao"><a href="<?php echo INCLUDE_PATH_PANEL; ?>usuarios">Usuários</a></li>
			</ul>			
		</aside>

		<header>
			<div class="center">
				<div class="menu-btn">
					<img src="<?php echo INCLUDE_PATH_PANEL; ?>images/menu_40_white.png">
				</div>
				<div class="btn-home">
					<a href="<?php echo INCLUDE_PATH_PANEL; ?>"><img src="<?php echo INCLUDE_PATH_PANEL; ?>images/casa_tamanho_01_white.png"></a>
				</div>
				<div class="logout">
					<a href="<?php echo INCLUDE_PATH_PANEL; ?>?logout"><i class="fa fa-window-close"></i> LOGOUT</a>
				</div>
				<div class="clear"></div><!-- clear -->
			</div>
		</header>

		<section class="content">
			<?php
			 padrao::carregarPagina(); 
			 ?>
		</section>

<?php	}
	else{
		echo '<h2>Tentativa anormal de Login.</h2>';
	}
 ?>

<script src="<?php echo INCLUDE_PATH; ?>js/jquery.js"></script>
<script src="<?php echo INCLUDE_PATH; ?>js/jquery.ajaxform.js"></script>
<script src="<?php echo INCLUDE_PATH; ?>js/jquery.mask.js"></script>
<script src="<?php echo INCLUDE_PATH; ?>js/jquery.maskMoney.js"></script>
<script src="<?php echo INCLUDE_PATH_PANEL; ?>js/functions.js"></script>
</body>
</html>