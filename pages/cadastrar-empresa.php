<section class="section-cadastrar-empresa">
	<h2>Cadastro de Empresa</h2>
	<div class="box">
		<div class="cadastrar-empresa-wraper">
			<?php
				if(isset($_COOKIE['user'])){
					$sql = MySql::conectar()->prepare("SELECT * FROM `tb_user` WHERE `login` = ?");
					$sql->execute(array($_COOKIE['user']));
					if($sql->rowCount() < 1){
						padrao::alert('erro','Usuário não encontrado para acessar essa página');
						die();
					}
					else{
						$usuario = $sql->fetch();
					}
				}
				else{
					padrao::alert('erro','Você precisa estar logado para acessar essa página');
					die();				
				}
			?>
			<div>
				<img id="editar-imagem-img" src="<?php echo INCLUDE_PATH ?>/images/empresa01.png">			
			</div>
			<div>			
				<form method="post" enctype="multipart/form-data" action="<?php echo INCLUDE_PATH ?>ajax/cadastro_empresa.ajax.php">		

					<div class="form-group">
						<label>Nome da Empresa: </label>
						<input type="text" name="nome">				
					</div>

					<div class="form-group">
						<label>Descrição: </label>
						<textarea name="descricao"></textarea>
					</div>		

					<div class="form-group">
						<label>Endereço: </label>
						<input type="text" name="endereco">				
					</div>		

					<div class="form-group">
						<label>Telefone: </label>
						<input type="text" name="telefone">				
					</div>	

					<div class="form-group">
						<label>Site: </label>
						<input type="text" name="site">				
					</div>

					<div class="form-group">
						<label>Escolha ao menos uma categoria: </label>
						<div>					
							<label>Categoria 01: </label>
							<select name="categoria_1">
								<option disabled selected>Selecione uma categoria</option>
								<?php
									$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` ORDER BY `nome`");
									$sql->execute();
									$listarCategoria1 = $sql->fetchAll();
									foreach ($listarCategoria1 as $key => $value) { ?>
										<option value="<?php echo $value['id']; ?>"><?php echo strtoupper($value['nome']); ?></option>
								<?php } ?>
								<option value="Nova Categoria">Nova Categoria</option>
							</select>
							<input type="text" name="cadastrar_categoria1" placeholder="CADASTRAR CATEGORIA">						
						</div>
						<div>					
							<label>Categoria 02: </label>
							<select name="categoria_2">
								<option disabled selected>Selecione uma categoria</option>
								<?php
									$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` ORDER BY `nome`");
									$sql->execute();
									$listarCategoria2 = $sql->fetchAll();
									foreach ($listarCategoria2 as $key => $value) { ?>
										<option value="<?php echo $value['id']; ?>"><?php echo strtoupper($value['nome']); ?></option>
								<?php } ?>
								<option value="Nova Categoria">Nova Categoria</option>
							</select>	
							<input type="text" name="cadastrar_categoria2" placeholder="CADASTRAR CATEGORIA">				
						</div>
						<div>					
							<label>Categoria 03: </label>
							<select name="categoria_3">
								<option disabled selected>Selecione uma categoria</option>
								<?php
									$sql = MySql::conectar()->prepare("SELECT * FROM `tb_categoria.empresa` ORDER BY `nome`");
									$sql->execute();
									$listarCategoria3 = $sql->fetchAll();
									foreach ($listarCategoria3 as $key => $value) { ?>
										<option value="<?php echo $value['id']; ?>"><?php echo strtoupper($value['nome']); ?></option>
								<?php } ?>
								<option value="Nova Categoria">Nova Categoria</option>
							</select>	
							<input type="text" name="cadastrar_categoria3" placeholder="CADASTRAR CATEGORIA">				
						</div>			
					</div>

					<div class="form-group">
						<label>Escolher uma Imagem: </label>
						<input type="file" name="imagem_nova" id="editar-imagem-alterar"onchange="document.getElementById('editar-imagem-img').src = window.URL.createObjectURL(this.files[0])">
					</div>

					<div class="form-group">
						<input type="hidden" name="tipo_acao" value="cadastrar_empresa" required>
						<input type="hidden" name="user_id" value="<?php echo $usuario['id'] ?>">
						<input type="hidden" name="nome_tabela" value="tb_empresa">
						<input type="submit" name="cadastrar_empresa" value="CADASTRAR">				
					</div>
				</form>
			</div>			
		</div><!-- CADSARTAR EMPRESA WRAPER -->
	</div><!-- box -->
</section>