$(function(){
	include_path = $('base').attr('href');
	include_path_panel = $('base').attr('href_panel');

	/************************* CATEGORIAS ******************/

	$('.tabela_categorias a.alterar').click(function(){
		var categoria_id = $(this).attr('categoria_id');
		var name_link = $(this).attr('name');
		var nome_novo = $('.tabela_categorias td input[name='+name_link+']').val();
		$.ajax({
				dataType:'json',
				url:include_path_panel+'ajax/categorias.ajax.php',
				method:'post',
				data: {'acao':'categoria_alterar',
				'categoria_id':categoria_id,
				'nome_novo':nome_novo}
			}).done(function(data){
				if(data.sucesso){
					$('.box-alert').remove();
					$('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_categorias');
					setTimeout(function(){
						window.location.href = include_path_panel + 'categorias';
					},1500);
				}
				else{
					$('.box-alert').remove();
					$('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_categorias');
				}
			})
		return false;
	})

	$('.tabela_categorias a.excluir').click(function(){
		var r = confirm("Você tem certeza que deseja excluir esta categoria?");
		if(r == true){
			var categoria_id = $(this).attr('categoria_id');
			var name_link = $(this).attr('name');
			var nome_novo = $('.tabela_categorias td input[name='+name_link+']').val();
			$.ajax({
					dataType:'json',
					url:include_path_panel+'ajax/categorias.ajax.php',
					method:'post',
					data: {'acao':'categoria_excluir',
					'categoria_id':categoria_id,
					'nome_novo':nome_novo}
				}).done(function(data){
					if(data.sucesso){
						$('.box-alert').remove();
						$('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_categorias');
						setTimeout(function(){
							window.location.href = include_path_panel + 'categorias';
						},1500);
					}
					else{
						$('.box-alert').remove();
						$('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_categorias');
					}
				})		
			return false;
		} else{
			return false;
		}
	})

	$('.cadastrando-categoria a').click(function(){
		var nome_novo = $('.cadastrando-categoria input').val();
		var categoria_id = $(this).attr('categoria_id');
		$.ajax({
				dataType:'json',
				url:include_path_panel+'ajax/categorias.ajax.php',
				method:'post',
				data: {'acao':'categoria_cadastrar',
				'categoria_id':categoria_id,
				'nome_novo':nome_novo}
			}).done(function(data){
				if(data.sucesso){
					$('.box-alert').remove();
					$('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_categorias');
					setTimeout(function(){
						window.location.href = include_path_panel + 'categorias';
					},1500);
				}
				else{
					$('.box-alert').remove();
					$('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_categorias');
				}
			})
		return false;
	})

	$('.td-cadastrar-categoria a').click(function(){
		$('.cadastrando-categoria').show();
		$(this).parent().remove();
		return false;
	})

	/************************* EMPRESAS **********************/

	$('.tabela_empresas a.alterar').click(function(){
		var empresa_id = $(this).attr('empresa_id');
		var proprietario_novo = $('.tabela_empresas td select[empresa_id='+empresa_id+']').val();
		$.ajax({
				dataType:'json',
				url:include_path_panel+'ajax/empresas.ajax.php',
				method:'post',
				data: {'acao':'empresa_alterar',
				'empresa_id':empresa_id,
				'proprietario_novo':proprietario_novo}
			}).done(function(data){
				if(data.sucesso){
					$('.box-alert').remove();
					$('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_empresas');
					setTimeout(function(){
						window.location.href = include_path_panel + 'empresas';
					},1500);
				}
				else{
					$('.box-alert').remove();
					$('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_empresas');
				}
			})
		return false;
	})

	$('.tabela_empresas a.excluir').click(function(){
		var empresa_id = $(this).attr('empresa_id');
		var r = confirm("Você tem certeza que deseja excluir esta empresa?");
		if(r == true){	
			$.ajax({
					dataType:'json',
					url:include_path_panel+'ajax/empresas.ajax.php',
					method:'post',
					data: {'acao':'empresa_excluir',
					'empresa_id':empresa_id}
				}).done(function(data){
					if(data.sucesso){
						$('.box-alert').remove();
						$('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_empresas');
						setTimeout(function(){
							window.location.href = include_path_panel + 'empresas';
						},1500);
					}
					else{
						$('.box-alert').remove();
						$('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_empresas');
						setTimeout(function(){
							window.location.href = include_path_panel + 'empresas';
						},1500);
					}
				})
			return false;
		}
		else{
			return false;
		}
	})

	/************************* FILIAIS **********************/

	$('.tabela_filiais a.alterar').click(function(){
		var filial_id = $(this).attr('filial_id');
		var name_link = $(this).attr('name');
		var nome_novo = $('.tabela_filiais td input[name='+name_link+']').val();
		$.ajax({
				dataType:'json',
				url:include_path_panel+'ajax/filiais.ajax.php',
				method:'post',
				data: {'acao':'filial_alterar',
				'filial_id':filial_id,
				'nome_novo':nome_novo}
			}).done(function(data){
				if(data.sucesso){
					$('.box-alert').remove();
					$('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_filiais');
					setTimeout(function(){
						window.location.href = include_path_panel + 'filiais';
					},1500);
				}
				else{
					$('.box-alert').remove();
					$('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_filiais');
				}
			})
		return false;
	})

	$('.tabela_filiais a.excluir').click(function(){
		var r = confirm("Você tem certeza que deseja excluir esta filial?");
		if(r == true){
			var filial_id = $(this).attr('filial_id');
			var name_link = $(this).attr('name');
			var nome_novo = $('.tabela_filiais td input[name='+name_link+']').val();
			$.ajax({
					dataType:'json',
					url:include_path_panel+'ajax/filiais.ajax.php',
					method:'post',
					data: {'acao':'filial_excluir',
					'filial_id':filial_id,
					'nome_novo':nome_novo}
				}).done(function(data){
					if(data.sucesso){
						$('.box-alert').remove();
						$('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_filiais');
						setTimeout(function(){
							window.location.href = include_path_panel + 'filiais';
						},1500);
					}
					else{
						$('.box-alert').remove();
						$('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_filiais');
					}
				})
			return false;
		}else{
			return false;
		}
	})

	$('.cadastrando-filial a').click(function(){
		var nome_novo = $('.cadastrando-filial input').val();
		var filial_id = $(this).attr('filial_id');
		$.ajax({
				dataType:'json',
				url:include_path_panel+'ajax/filiais.ajax.php',
				method:'post',
				data: {'acao':'filial_cadastrar',
				'filial_id':filial_id,
				'nome_novo':nome_novo}
			}).done(function(data){
				if(data.sucesso){
					$('.box-alert').remove();
					$('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_filiais');
					setTimeout(function(){
						window.location.href = include_path_panel + 'filiais';
					},1500);
				}
				else{
					$('.box-alert').remove();
					$('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_filiais');
				}
			})
		return false;
	})

	$('.td-cadastrar-filial a').click(function(){
		$('.cadastrando-filial').show();
		$(this).parent().remove();
		return false;
	})

	/************************* POSTS **********************/

	$('.tabela_posts a.excluir').click(function(){
		var r = confirm("Você tem certeza que deseja excluir esta filial?");
		if(r == true){
			var post_id = $(this).attr('post_id');
			$.ajax({
					dataType:'json',
					url:include_path_panel+'ajax/posts.ajax.php',
					method:'post',
					data: {'acao':'post_excluir',
					'post_id':post_id}
				}).done(function(data){
					if(data.sucesso){
						$('.box-alert').remove();
						$('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_posts');
						setTimeout(function(){
							window.location.href = include_path_panel + 'posts';
						},1500);
					}
					else{
						$('.box-alert').remove();
						$('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_posts');
					}
				})
			return false;
		}else{
			return false;
		}
	})

	/************************* USUÁRIOS **********************/

	$('.tabela_user a.alterar').click(function(){
		var user_id = $(this).attr('user_id');
		$('.tabela_user form.user_'+user_id).show();
		$(this).hide();
		return false;
	})

	$('.tabela_user form').ajaxForm({
		dataType:'json',
		data: {'acao':'user_alterar'},
		success: function(data){
			if(data.sucesso){
				$('.box-alert').remove();
				$('.tabela_user form.'+data.class).prepend('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');
				setTimeout(function(){
					window.location.href = include_path_panel + 'usuarios';
				},1500);
			}else{
				$('.box-alert').remove();
				$('.tabela_user form.'+data.class).prepend('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');

			}
		}
	})

	$('.tabela_user a.excluir').click(function(){
		var r = confirm("Você tem certeza que deseja excluir este usuário?");
		if(r == true){
			var user_id = $(this).attr('user_id');
			$.ajax({
					dataType:'json',
					url:include_path_panel+'ajax/usuarios.ajax.php',
					method:'post',
					data: {'acao':'user_excluir',
					'user_id':user_id}
				}).done(function(data){
					if(data.sucesso){
						$('.box-alert').remove();
						$('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_user');
						setTimeout(function(){
							window.location.href = include_path_panel + 'usuarios';
						},1500);
					}
					else{
						$('.box-alert').remove();
						$('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela_user');
					}
				})
			return false;
		}else{
			return false;
		}
	})

})

// window.onbeforeunload = function() {
//   return false;
// }