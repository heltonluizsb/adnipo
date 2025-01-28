<?php include('../config.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Template de E-mail</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<link href="<?php echo INCLUDE_PATH; ?>css/confirmaemail.css" type="text/css" rel="stylesheet" />
</head>
<body>
	<div class="box">
		<img src="<?php echo INCLUDE_PATH; ?>images/logo01_forbusiness.png">
		<h2>Favor confirmar seu e-mail no link abaixo:</h2>
		<a href="<?php echo INCLUDE_PATH; ?>confirmaemail/codigo"><?php echo INCLUDE_PATH; ?>confirmaemail/codigo</a>		
	</div>
</body>
</html>