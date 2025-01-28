<?php 
	if(isset($_COOKIE['paineladmin_lembrar'])){
		$user = $_COOKIE['paineladmin_user'];
		$password = $_COOKIE['paineladmin_password'];
		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.usuarios` WHERE user = ? AND password = ?");
		$sql->execute(array($user,$password));
		if($sql->rowCount() == 1){
			$info = $sql->fetch();
			$_SESSION['paineladmin_login'] = true;
			$_SESSION['paineladmin_user'] = $user;
			$_SESSION['paineladmin_password'] = $password;
			$_SESSION['paineladmin_id_user'] = $info['id'];
			echo 'user: '.$info['id'];
			header('Location: '.INCLUDE_PATH_PANEL);
			die();
		}
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
	<div class="box-login">

		<?php
			if(isset($_POST['acao'])){
				$user = $_POST['paineladmin_user'];
				$password = $_POST['paineladmin_password'];
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin` WHERE user = ? AND senha = ?");
				$sql->execute(array($user,$password));
				if($sql->rowCount() == 1){
					$info = $sql->fetch();
					$_SESSION['paineladmin_login'] = true;
					$_SESSION['paineladmin_user'] = $user;
					$_SESSION['paineladmin_password'] = $password;
					$_SESSION['paineladmin_id_user'] = $info['id'];
					if(isset($_POST['lembrar'])){
						setcookie('paineladmin_lembrar',true,time()+(60*60*24),'/');
						setcookie('paineladmin_user',$user,time()+(60*60*24),'/');
						setcookie('paineladmin_password',$password,time()+(60*60*24),'/');
						echo 'deveria lembrar';
					}
					header('Location: '.INCLUDE_PATH_PANEL);
					die();
				}else{
					echo '<div class="erro-box"><h2>Usuário ou senha incorretos</h2></div>';
				}
			}
		?>

		<h2>Efetua o Login:</h2>
		<form method="post">
			<input type="text" name="paineladmin_user" placeholder="Login..." required>
			<input type="password" name="paineladmin_password" placeholder="Senha..." required>
			<div class="form-group-login left">
				<input type="submit" name="acao" value="Logar">
			</div>
			<div class="form-group-login right">
				<label>Lembrar-me</label>
				<input type="checkbox" name="lembrar">
			</div>
			<div class="clear"></div><!--clear-->
		</form>
	</div><!-- box-login -->
</body>
</html>