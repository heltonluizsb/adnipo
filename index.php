<?php ob_start();
include('config.php'); ?>

<?php 
	$url = isset($_GET['url']) ? $_GET['url'] : 'home';

	if(isset($_GET['logout'])){
 		setcookie('user','',time()-1,'/');
 		setcookie('senha','',time()-1,'/');
		echo padrao::redirecionaPagina('');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>ADNIPO FOR BUSINESS</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<link href="<?php echo INCLUDE_PATH; ?>css/style.css" type="text/css" rel="stylesheet" />
</head>
<body>
	<base href="<?php echo INCLUDE_PATH; ?>">
	<header>
		<div class="header-logo">
			<div class="section-left">
				<div class="header-logo-img">
					<a href="<?php echo INCLUDE_PATH; ?>home"><img src="<?php echo INCLUDE_PATH; ?>images/logo01_tamanho_01.png"></a>					
				</div>
				<div class="header-logo-form-desktop"><form method="post">
					<input type="text" name="pesquisar" placeholder="&#x1f50d; Pesquisar" autocomplete="off">
				</form></div>
				<div class="header-logo-form-mobile">
					<p>&#x1f50d;</p>
					<form method="post">
						<input type="text" name="pesquisar" placeholder="&#x1f50d; Pesquisar" autocomplete="off">
					</form>
				</div>
				<div class="section-left-mobile-open" <?php if($url != 'home' && $url != 'empresas' && $url != 'clientes'){ echo 'style="display: none;"';} ?>>
					<img src="<?php INCLUDE_PATH ?>images/menu_red.png">
				</div>
			</div><!-- section-left -->
			<div class="section-center">
				<div class="header-menu">
					<div class="header-menu-desktop">
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
				</div>
			</div><!-- section-center -->
			<div class="section-right">
				<?php 
					if(isset($_COOKIE['user']) && isset($_COOKIE['senha'])){						
						$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `login` = ? AND `senha` = ?");
						$sql->execute(array($_COOKIE['user'],$_COOKIE['senha']));
						if($sql->rowCount() > 0){
							$validaLogin = $sql->fetch();?>

							<a href="" class="logo-logado"><div class="header-menu-desktop-single"><div>
								<?php if($validaLogin['img_perfil'] != ''){ ?>
									<img src="<?php echo INCLUDE_PATH ?>uploads/<?php echo $validaLogin['img_perfil'] ?>">
								<?php } else { ?>
									<img src="<?php echo INCLUDE_PATH ?>images/clientevazio.JPG">
								<?php } ?>
								<p><span><?php echo $validaLogin['login'] ?></span> ▼</p>
							</div></div></a>

							<a href="<?php echo INCLUDE_PATH ?>mensagens" class="messenger"><p><img src="<?php echo INCLUDE_PATH ?>images/messenger.png"></p></a>

							<div class="clear"></div>

							<div class="menu-logado">
								<div class="box">
									<a href="<?php echo INCLUDE_PATH ?>perfil/<?php echo $validaLogin['login'] ?>"><p>Meu Perfil</p></a>
									<div class="menu-logado-line"></div>
									<a href="<?php echo INCLUDE_PATH; ?>?logout" class="menu-logado-logout"><p>LOGOUT</p></a>									
								</div>
							</div>

					<?php } else{ ?>

							<a class="logo-deslogado" href="<?php echo INCLUDE_PATH; ?>login"><div class="header-menu-desktop-single">
								<img src="<?php echo INCLUDE_PATH ?>images/clientevazio.JPG">
								<p>LOGIN</p>
							</div></a>

					<?php }
					}else{ ?>

						<a class="logo-deslogado"  href="<?php echo INCLUDE_PATH; ?>login"><div class="header-menu-desktop-single">
							<img src="<?php echo INCLUDE_PATH ?>images/clientevazio.JPG">
							<p>LOGIN</p>
						</div></a>

				<?php } ?>

				<div class="clear"></div>
			</div><!-- section-right -->
			<div class="clear"></div>
		</div>
	</header>

	<?php

		if(file_exists('pages/'.$url.'.php')){
			include('pages/'.$url.'.php');
		} else{
			if(strpos($url,'/')){
				$url_explode = explode('/',$url);
				if(file_exists('pages/'.$url_explode[0].'.php')){
					include('pages/'.$url_explode[0].'.php');
				}
				else{
					include('pages/error404.php');
				}
			}
			else{				
				include('pages/error404.php');
			}
		}
	?>

<script src="<?php echo INCLUDE_PATH; ?>js/jquery.js"></script>
<script src="<?php echo INCLUDE_PATH; ?>js/jquery.ajaxform.js"></script>
<script src="<?php echo INCLUDE_PATH; ?>js/jquery.mask.js"></script>
<script src="<?php echo INCLUDE_PATH; ?>js/jquery.maskMoney.js"></script>
<script src="<?php echo INCLUDE_PATH; ?>js/functions.js"></script>
<?php padrao::adicionaScript($url,'mensagens','mensagens'); ?>
</body>
</html>
<?php ob_end_flush(); ?>