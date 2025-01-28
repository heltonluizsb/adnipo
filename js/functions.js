$(function(){
	include_path = $('base').attr('href');

	/******** HOME ********/
	var form_pesquisa_show = false;

	$('header .section-left .header-logo-form-mobile p').click(function(){
		if(form_pesquisa_show){
			$('header .section-left .header-logo-form-mobile form').hide();
			form_pesquisa_show = false;
		} else{
			$('header .section-left .header-logo-form-mobile form').show();	
			form_pesquisa_show = true;		
		}
	})

	$('.section-left-mobile-close').click(function(){
		$('section.section-home .section-left, section.section-empresas .section-left').hide();
	})

	$('.section-left-mobile-open').click(function(){
		$('section.section-home .section-left, section.section-empresas .section-left').show();
	})

	$('.section-home form.categorias [type=checkbox]').change(function(){
		if($(this).prop('checked')){
			var name = $(this).attr('name');
			var goto = $(this).attr('goto');
		$.ajax({
				url:include_path+'ajax/home_categoria.ajax.php',
				method:'post',
				data: {'acao':'home_categoria','name':name}
			}).done(function(data){
				window.location.href = include_path + goto;
			})
		}
	});

	$('.section-login a.section-login-btn').click(function(){
		$('.cria-login').fadeIn();
		return false;
	})

	$('.cria-login .box > img').click(function(){
		$('.cria-login').fadeOut();		
	})

	var mostra_menu_logado = false;

	$('header .section-right a.logo-logado').click(function(){
		if(!mostra_menu_logado){
			var old_text = $('header .section-right a.logo-logado .header-menu-desktop-single p').html();
			$('header .section-right a.logo-logado .header-menu-desktop-single p').html(old_text.replace('▼','▲'))
			$('.menu-logado').show();
			mostra_menu_logado = true;
		}
		else{
			var old_text = $('header .section-right a.logo-logado .header-menu-desktop-single p').html();
			$('header .section-right a.logo-logado .header-menu-desktop-single p').html(old_text.replace('▲','▼'))
			$('.menu-logado').hide();
			mostra_menu_logado = false;			
		}
		return false;
	})

	$('body').click(function(){
		if(mostra_menu_logado){
			var old_text = $('header .section-right a.logo-logado .header-menu-desktop-single p').html();
			$('header .section-right a.logo-logado .header-menu-desktop-single p').html(old_text.replace('▲','▼'))
			$('.menu-logado').hide();
			mostra_menu_logado = false;
		}		
	})

	/*************** CADASTRAR LOGIN ***********/
	$('[type=text],[type=password],select').change(function(){
		if($(this).hasClass('campo-vazio') && $(this).val() != ''){
			$(this).removeClass('campo-vazio');
		}
	})

	$('.select_filial').change(function(){
		if($(this).val() == 'Nova Filial'){
			$('[name=cadastrar_filial]').show();
		}
		else{
			$('[name=cadastrar_filial]').hide();			
		}
		return false;
	})

	mostraFormCadastro();

	function mostraFormCadastro(){
		if($('.cria-login').hasClass('mostra-cadastro')){
			$('.cria-login').fadeIn();
		}
	}

	$('.ajax').ajaxForm({
		dataType:'json',
		beforeSend:function(){
			$('.ajax').animate({'opacity':'0.6'});
			$('.ajax').find('input[type=submit]').attr('disabled','true');
		},
		success: function(data){
			$('.ajax').animate({'opacity':'1'});
			$('.ajax').find('input[type=submit]').removeAttr('disabled');
			$('.box-alert').remove();
			if(data.sucesso){
				$('.ajax').prepend('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');

				setTimeout(function(){
					window.location.href = include_path + 'perfil/'+data.login;
				},3000);
			} else{
				$('.ajax').prepend('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');
				if(data.mensagem.includes('LOGIN')){
					$('[name=login]').addClass('campo-vazio');
				}
				if(data.mensagem.includes('NOME')){
					$('[name=nome]').addClass('campo-vazio');
				}
				if(data.mensagem.includes('CPF ou CNPJ')){
					$('[name=cpf]').addClass('campo-vazio');
					$('[name=cnpj]').addClass('campo-vazio');
				}
				if(data.mensagem.includes('CARTEIRINHA DE MEMBRO')){
					$('[name=carteirinha]').addClass('campo-vazio');
				}
				if(data.mensagem.includes('SENHA')){
					$('[name=senha]').addClass('campo-vazio');
				}
			}
		}
	})

	/******** PERFIL ********/
	$('.perfil-nome a').click(function(){
		$('.edita-login').fadeIn();
		return false;
	})

	$('.edita-login .box > img').click(function(e){
		$('.edita-login').fadeOut();		
	})

	$('.edita-login .box').click(function(e){
		e.stopPropagation();		
	})

	$('body').click(function(){
		$('.edita-login').fadeOut();
		$('.menu-escolher-postador').hide();
		menu_escolher_show = true;
	})

	$('#editar-imagem-alterar').change(function(){
		$('.form-altera-imagem-perfil').submit();
	})

	$('.form-altera-imagem-perfil').ajaxForm({
		dataType:'json',
		data: {'acao':'editar-imagem-perfil'},
		success: function(data){
			if(data.sucesso){
				window.location.href = include_path + 'perfil/' + data.user_login;
			}else{
				$('.box-alert').remove();
				$('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.edita-login .box h2:first-of-type');

			}
		}
	})

	$('section.section-perfil .box > img').click( function(){
		$('.mostra-imagem').fadeIn();
	})

	$('.mostra-imagem .box > img').click(function(){
		$('.mostra-imagem').fadeOut();		
	})

	$('section.section-perfil .boxleft .editar a').click( function(){
		$('.edita-perfil').fadeIn();
		return false;
	})

	$('.edita-perfil .box > img').click( function(){
		$('.edita-perfil').fadeOut();
	})

	$('.form-altera-nome-perfil input[type=text]').change(function(){
		$('.form-altera-nome-perfil').submit();
	})

	$('.form-altera-nome-perfil').ajaxForm({
		dataType:'json',
		data: {'acao':'editar-nome-perfil'},
		success: function(data){
			if(data.sucesso){
				window.location.href = include_path + 'perfil/' + data.user_login;
			}
			else{
				$('.box-alert').remove();
				$('.edita-login .box > h2:first-of-type').prepend('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');

			}
		}
	})

	$('.form-altera-perfil').ajaxForm({
		dataType:'json',
		data: {'acao':'editar-perfil'},
		beforeSend:function(){
			$('.form-altera-perfil').animate({'opacity':'0.6'});
			$('.form-altera-perfil').find('input[type=submit]').attr('disabled','true');
		},
		success: function(data){
			if(data.sucesso){
				$('.form-altera-perfil').animate({'opacity':'1'});
				$('.form-altera-perfil').find('input[type=submit]').removeAttr('disabled');
				$('.box-alert').remove();
				$('.form-altera-perfil').prepend('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');

				setTimeout(function(){
					window.location.href = include_path  + 'perfil/' + data.user_login;
				},3000);
			} else{
				$('.form-altera-perfil').animate({'opacity':'1'});
				$('.form-altera-perfil').find('input[type=submit]').removeAttr('disabled');
				$('.box-alert').remove();
				$('.form-altera-perfil').prepend('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');
			}
		}
	})


	var pathname = window.location.pathname;
	pathname = pathname.split('/');
	pathname = pathname[pathname.length - 2];
	EditaPerfilStart();

	function EditaPerfilStart(){
		if(pathname == 'perfil'){
			if($('.edita-perfil select option[value=juridico]')[0].hasAttribute("selected")){
				$('.edita-perfil [name=cpf]').parent().hide();
				$('.edita-perfil [name=cnpj]').parent().show();
			}
			else if($('.edita-perfil select option[value=fisico]')[0].hasAttribute("selected")){
				$('.edita-perfil [name=cnpj]').parent().hide();
				$('.edita-perfil [name=cpf]').parent().show();
			}
		}
	}

	/*********** PERFIL - EMPRESAS DO PERFIL ************/

	$('.empresa-user-single').click(function(){
		var slug = $(this).attr('href');
		window.location.href = include_path + 'perfil_empresa/' + slug;
	})

	/*********** PERFIL - ALTERAR SENHA ************/

	$('.click-altera-senha').click(function(){
		$('.altera-senha').fadeIn();
		return false;
	})

	$('.altera-senha .box > img').click(function(){
		$('.altera-senha').fadeOut();		
	})

	$('.form-altera-senha').ajaxForm({
		dataType:'json',
		data: {'acao':'alterar-senha'},
		success: function(data){
			if(data.sucesso){
				$('.box-alert').remove();
				$('.form-altera-senha').prepend('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');

			}else{
				$('.box-alert').remove();
				$('.form-altera-senha').prepend('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');

			}
		}
	})

	/*************** PERFIL - ESQUECEU SENHA *******************/

	$('.esqueceu-senha').click(function(){
		var user_id = $(this).attr('user_id');
		var user_name = $(this).attr('user_name');
		var user_email = $(this).attr('user_email');
		console.log(user_email);
		$.ajax({
				dataType:'json',
				url:include_path+'ajax/altera_senha.ajax.php',
				method:'post',
				data: {'acao':'altera_senha',
				'user_id':user_id,
				'user_name':user_name,
				'user_email':user_email},
			beforeSend:function(){
				$('.loading').fadeIn();
			},
			}).done(function(data){
				if(data.sucesso){
					console.log(data.email);
					$('.loading').fadeOut();
					$('.altera-senha').fadeOut();
					$('.section-perfil .boxleft .box .perfil .box-alert').remove();
					$('.section-perfil .boxleft .box .perfil').prepend('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');
				}else{
					$('.altera-senha').fadeOut();
					$('.loading').fadeOut();
					$('.section-perfil .boxleft .box .perfil .box-alert').remove();
					$('.section-perfil .boxleft .box .perfil').prepend('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');
				}
			}).fail(function(xhr, status, error){
		        var errorMessage = xhr.status + ': ' + xhr.statusText
		        console.log('Error - ' + errorMessage);
			})
		return false;
	})

	$('.form-altera-senha-por-email').ajaxForm({
		dataType:'json',
		data: {'acao':'alterando_senha'},
		success: function(data){
			if(data.sucesso){
				$('.box-alert').remove();
				$('.form-altera-senha-por-email').prepend('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');
				setTimeout(function(){
					window.location.href = include_path + 'perfil/'+data.user_login
				},3000);

			}else{
				$('.box-alert').remove();
				$('.form-altera-senha-por-email').prepend('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');

			}
		}
	})

	$('.link-esqueceu-senha').click(function(){
		$('.login-esqueceu-senha').fadeIn();
		return false;
	})

	$('.form-esqueceu-senha-pagina-login').ajaxForm({
		dataType:'json',
		data: {'acao':'alterando_senha_pagina_login'},
		beforeSend:function(){
			$('.loading').fadeIn();
		},
		success: function(data){
			if(data.sucesso){
				$('.loading').fadeOut();
				$('.box-alert').remove();
				$('.login-esqueceu-senha .box').prepend('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');

			}else{
				$('.loading').fadeOut();
				$('.box-alert').remove();
				$('.login-esqueceu-senha .box').prepend('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');

			}
		}
	})

	/*********** CADASTRAR EMPRESA ************/

	$('.section-cadastrar-empresa [name=categoria_1], .form-altera-categoria-empresa [name=categoria_1]').change(function(){
		var val = $(this).val();
		if(val == 'Nova Categoria'){
			$('[name=cadastrar_categoria1]').show();
		}
	})

	$('.section-cadastrar-empresa [name=categoria_2], .form-altera-categoria-empresa [name=categoria_2]').change(function(){
		var val = $(this).val();
		if(val == 'Nova Categoria'){
			$('[name=cadastrar_categoria2]').show();
		}
	})

	$('.section-cadastrar-empresa [name=categoria_3], .form-altera-categoria-empresa [name=categoria_3]').change(function(){
		var val = $(this).val();
		if(val == 'Nova Categoria'){
			$('[name=cadastrar_categoria3]').show();
		}
	})

	$('.section-cadastrar-empresa form').ajaxForm({
		dataType:'json',
        enctype: 'multipart/form-data',
		beforeSend:function(){
			$('.section-cadastrar-empresa form').animate({'opacity':'0.6'});
			$('.section-cadastrar-empresa form').find('input[type=submit]').attr('disabled','true');
		},
		success: function(data){
			$('.section-cadastrar-empresa form').animate({'opacity':'1'});
			$('.section-cadastrar-empresa form').find('input[type=submit]').removeAttr('disabled');
			$('.box-alert').remove();
			if(data.sucesso){
				$('.section-cadastrar-empresa form').prepend('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');

				setTimeout(function(){
					window.location.href = include_path + 'perfil_empresa/'+data.slug;
				},3000);
			} else{
				$('.section-cadastrar-empresa form').prepend('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');
			}
		}
	})

	/**************** EMPRESA *****************/

	$('section.section-perfil .boxleft .editar-empresa a').click( function(){
		$('.edita-login').fadeIn();
		return false;
	})

	$('.boxleft ul li').click(function(){
		if($(this)[0].hasAttribute("href")){
			window.location.href = $(this).attr('href');
		}
	})

	$('.tabela-wraper .tabela-solicitacoes a.excluir').click(function(){
		var r = confirm("Você tem certeza que deseja excluir?");
		if(r == true){
			return true;
		}
		else{
			return false;
		}		
	})

	$('.tabela-wraper .tabela-solicitacoes a.tornar-proprietario').click(function(){
		var user_empresa_dados_nome = $(this).attr('user_empresa_dados_nome');
		var r = confirm("Você tem certeza que deseja tornar o(a) " + user_empresa_dados_nome + " o(a) proprietário(a) desta empresa?");
		if(r == true){
			return true;
		}
		else{
			return false;
		}		
	})

	mostraFormEditaEmpresa();

	function mostraFormEditaEmpresa(){
		if($('.edita-login').hasClass('mostra-edicao')){
			$('.edita-login').fadeIn();
		}
	}

	$('.tabela-solicitacoes select').change(function(){
		var user_id = $(this).attr('user_id');
		var empresa_id = $(this).attr('empresa_id');
		var slug = $(this).attr('slug');
		var permissao_id = $(this).val();
		$.ajax({
				dataType:'json',
				url:include_path+'ajax/atualiza_empresa.php',
				method:'post',
				data: {'acao':'editar-permissao-empresa',
					'user_id':user_id,
					'empresa_id':empresa_id,
					'permissao_id':permissao_id,
					'slug':slug}
			}).done(function(data){
				$('.box-alert').remove();
				$('<div class="box-alert sucesso"><img src="'+include_path+'images/check01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>').insertBefore('.tabela-solicitacoes');
			}).fail(function(xhr, status, error){
		         var errorMessage = xhr.status + ': ' + xhr.statusText
		         console.log('Error - ' + errorMessage);
			})
	})

	$('#editar-imagem-empresa').change(function(){
		$('.form-altera-imagem-empresa').submit();
	})

	$('.form-altera-imagem-empresa').ajaxForm({
		dataType:'json',
		data: {'acao':'editar-imagem-empresa'},
		success: function(data){
			window.location.href = include_path + 'perfil_empresa/' + data.slug;
		}
	})

	$('.form-altera-descricao-empresa textarea').change(function(){
		$('.form-altera-descricao-empresa').submit();
	})

	$('.form-altera-descricao-empresa').ajaxForm({
		dataType:'json',
		data: {'acao':'editar-descricao-empresa'},
		success: function(data){
			window.location.href = include_path + 'perfil_empresa/' + data.slug;
		}
	})

	$('.form-altera-categoria-empresa').ajaxForm({
		dataType:'json',
		data: {'acao':'editar-categoria-empresa',
			'categoria_1_id':$('.form-altera-categoria-empresa [name=categoria_1]').attr('categoria_id'),
			'categoria_2_id':$('.form-altera-categoria-empresa [name=categoria_2]').attr('categoria_id'),
			'categoria_3_id':$('.form-altera-categoria-empresa [name=categoria_3]').attr('categoria_id')},
		success: function(data){
			window.location.href = include_path + 'perfil_empresa/' + data.slug;
		}
	})

	$('.boxleft ul li.categoria_empresa').click(function(){
		var categoria_id = $(this).attr('categoria_id');
		window.location.href = include_path + 'empresas?pesquisa_categoria=' + categoria_id;	
	})

	$('a.excluir-empresa-btn').click(function(){
		var empresa_nome = $(this).attr('empresa_nome');
		var r = confirm("Você tem certeza que excluir a empresa " + empresa_nome + "?");
		if(r == true){
			return true;
		}
		else{
			return false;
		}		
	})

	$('a.sair-empresa').click(function(){
		var empresa_nome = $(this).attr('empresa_nome');
		var r = confirm("Você tem certeza que deseja sair da empresa " + empresa_nome + "?");
		if(r == true){
			return true;
		}
		else{
			return false;
		}		
	})

	/*********** EMPRESAS - HOME *********/

	$('.empresa-single .box').click(function(){
		var href = $(this).attr('href');
		window.location.href = href;
	})

	$('.section-empresas form.categorias [type=checkbox]').change(function(){
		$('.section-empresas form').submit();
	});

	$('.section-empresas form').ajaxForm({
		data: {'acao':'listar-empresas-por-categoria'},
		success: function(data){
			$('.section-empresas .section-center').empty();
			$('.section-empresas .section-center').append(data);	
		},
		error: function(xhr, status, error){
	         var errorMessage = xhr.status + ': ' + xhr.statusText
	         console.log('Error - ' + errorMessage);
		}
	});

	/************** CLIENTES HOME ************/

	$('.section-clientes form.filiais [type=checkbox]').change(function(){
		$('.section-clientes form.filiais').submit();
	});

	$('.section-clientes form.filiais').ajaxForm({
		data: {'acao':'listar-clientes-por-filial'},
		success: function(data){
			$('.section-clientes .section-center').empty();
			$('.section-clientes .section-center').append(data);
		},
		error: function(data){
			console.log(data);
		}
	});

	$('.section-clientes form.categorias-cliente [type=checkbox]').change(function(){
		$('.section-clientes form.categorias-cliente').submit();
	});

	$('.section-clientes form.categorias-cliente').ajaxForm({
		data: {'acao':'listar-clientes-por-categoria'},
		success: function(data){
			$('.section-clientes .section-center').empty();
			$('.section-clientes .section-center').append(data);
		},
		error: function(data){
			console.log(data);
		}
	});

	/************** HOME ************/
	var menu_escolher_show = true;

	$('.form-post img.img-postar-close').click(function(e){
		$('#file').val('');
		$('.div-img-postar').hide();
	})

	$('#file').change(function(){
		$('.div-img-postar').show();
	})

	$('.post-postador-div1').click(function(e){
		e.stopPropagation();				
		if(menu_escolher_show){
			$('.menu-escolher-postador').show();
			menu_escolher_show = false;
		}else{
			$('.menu-escolher-postador').hide();
			menu_escolher_show = true;			
		}
	})

	var resposta_menu_escolher = {};

	$('.resposta-post-postador-div1').click(function(e){
		e.stopPropagation();
		var post_id = $(this).attr('post_id');
		if(typeof(resposta_menu_escolher[post_id]) == 'undefined'){
			resposta_menu_escolher[post_id] = true;
		}
		if(resposta_menu_escolher[post_id]){
			$('.resposta-menu-escolher_post_'+post_id).show();
			resposta_menu_escolher[post_id] = false;
		}else{
			$('.resposta-menu-escolher_post_'+post_id).hide();
			resposta_menu_escolher[post_id] = true;			
		}
	})

	$('.post-resposta textarea').on('keydown',(function(e){
		if(!isMobile){
			if(e.keyCode == 13 && ($(this).val().length > 0)){
				e.preventDefault();
				post_id = $(this).attr('post_id');
				var form_post = $('.form-post_'+post_id).serializeArray();
				var data_post = {};
				$(form_post).each(function(index, obj){
				    data_post[obj.name] = obj.value;
				});
				$.ajax({
						dataType:'json',
						url:include_path+'ajax/home_post.ajax.php',
						method:'post',
						data: {'form_post':data_post}
					}).done(function(data){
						$('.box-alert').remove();
						location.reload();
					}).fail(function(xhr, status, error){
				         var errorMessage = xhr.status + ': ' + xhr.statusText
				         console.log('Error - ' + errorMessage);
					})

			}
			else if(e.keyCode == 13 && ($(this).val().length <= 0)){
				e.preventDefault();
			}
		}
	}))

	// PARA CELULAR
	$('.post-resposta textarea').on('input',(function(e){
		if(isMobile){
    		var key = e.which || this.value.substr(-1).charCodeAt(0);
			if(key == 10 && ($(this).val().length > 0)){
				e.preventDefault();
				post_id = $(this).attr('post_id');
				var data_post = {};
	    		var conteudo_mobile = $(this).val();
	    		// $(this).val('');
				var form_post = $('.form-post_'+post_id).serializeArray();
				$(form_post).each(function(index, obj){
				    data_post[obj.name] = obj.value;
				});
				$.ajax({
						dataType:'json',
						url:include_path+'ajax/home_post.ajax.php',
						method:'post',
						data: {'form_post':data_post,
						'conteudo_mobile': conteudo_mobile}
					}).done(function(data){
						$('.box-alert').remove();
						$('.form-post_'+post_id+' textarea').val('');
						location.reload();
					}).fail(function(xhr, status, error){
				         var errorMessage = xhr.status + ': ' + xhr.statusText
				         console.log('Error - ' + errorMessage);
					})

			}
			else if(e.keyCode == 13 && ($(this).val().length <= 0)){
				e.preventDefault();
			}			
		}
	}))

	$('body').click(function(){
		$.each(resposta_menu_escolher, function(key, value){
			$('.resposta-menu-escolher_post_'+key).hide();
			resposta_menu_escolher[key] = false;
		})
	})

	$('.post-resposta-lista-paginacao p').on('click',(function(e){
		var por_pagina = $(this).attr('por_pagina');
		var pagina_proxima = $(this).attr('pagina_proxima');
		var post_id = $(this).attr('post_id');
		var pagina_atual = $(this).attr('pagina_atual');
		var fim_respostas = $(this).attr('fim_respostas');

		if(fim_respostas == 'false'){
			pagina_atual++;
			pagina_proxima = (pagina_atual - 1) * por_pagina;

			$(this).attr('pagina_atual',pagina_atual);
			$(this).attr('pagina_proxima',pagina_proxima);
		}

		$.ajax({
				dataType:'json',
				url:include_path+'ajax/carrega_novos_posts.ajax.php',
				method:'post',
				data: {'acao':'carrega_novas_respostas',
				'por_pagina':por_pagina,
				'pagina_proxima':pagina_proxima,
				'post_id':post_id,
				'estalogado':estalogado,
				'user_id':esta_logado_user_id,
				'fim_respostas':fim_respostas}
			}).done(function(data){
				$('.post-resposta-lista-paginacao_p_'+data.post_id).attr('fim_respostas',data.fim_respostas);
				$('.post-resposta-lista_'+data.post_id).append(data.dados);
				if(data.fim_respostas == 'true'){
					$('.box-alert').remove();
					$('.post-resposta-lista_'+data.post_id).append('<div class="box-alert atencao"><img src="'+include_path+'images/atencao01_tamanho_01_white.png"> <p>Não há mais respostas</p></div>');
			
				}
			}).fail(function(xhr, status, error){
		         var errorMessage = xhr.status + ': ' + xhr.statusText
		         console.log('Error - ' + errorMessage);
			})
	}))

	$('.menu-escolher-postador').click(function(e){
		e.stopPropagation();				
	})

	$('.menu-escolher-postador a.escolha-postador').click(function(){
		if($(this)[0].hasAttribute("tipo")){
			var img_src = $(this).attr('img_src');
			if($(this).attr('tipo') == 0){
				$('.post-categoria').css('display','inline-block');
				$('.form-post [name=origem_tipo]').attr('value',$(this).attr('tipo'));
				$('.form-post [name=origem_id]').attr('value',$(this).attr('origem_id'));
				if(img_src == ''){
					$('.post-postador-img img').attr('src',include_path + 'images/clientevazio.JPG');
				}
				else{
					$('.post-postador-img img').attr('src',include_path + 'uploads/' + img_src);
				}
				$('.menu-escolher-postador').hide();
				menu_escolher_show = true;
			}
			else if($(this).attr('tipo') == 1){
				$('.post-categoria').css('display','none');
				$('.form-post [name=origem_tipo]').attr('value',$(this).attr('tipo'));
				$('.form-post [name=origem_id]').attr('value',$(this).attr('origem_id'));
				if(img_src == ''){
					$('.post-postador-img img').attr('src',include_path + 'images/empresa01.png');
				}
				else{
					$('.post-postador-img img').attr('src',include_path + 'uploads/' + img_src);
				}
				$('.menu-escolher-postador').hide();
				menu_escolher_show = true;
			}
		}
		return false;
	})

	$('.resposta-menu-escolher a.escolha-postador').click(function(){
		if($(this)[0].hasAttribute("tipo")){
			var img_src = $(this).attr('img_src');
			var post_id = $(this).attr('post_id');
			if($(this).attr('tipo') == 0){
				$('.form-post_'+post_id+' [name=origem_tipo]').attr('value',$(this).attr('tipo'));
				$('.form-post_'+post_id+' [name=origem_id]').attr('value',$(this).attr('origem_id'));
				if(img_src == ''){
					$('.post-postador-img_'+post_id+' img').attr('src',include_path + 'images/clientevazio.JPG');
				}
				else{
					$('.post-postador-img_'+post_id+' img').attr('src',include_path + 'uploads/' + img_src);
				}
				$('.resposta-menu-escolher_post_'+post_id).hide();
				resposta_menu_escolher[post_id] = true;
			}
			else if($(this).attr('tipo') == 1){
				$('.form-post_'+post_id+' [name=origem_tipo]').attr('value',$(this).attr('tipo'));
				$('.form-post_'+post_id+' [name=origem_id]').attr('value',$(this).attr('origem_id'));
				if(img_src == ''){
					$('.post-postador-img_'+post_id+' img').attr('src',include_path + 'images/empresa01.png');
				}
				else{
					$('.post-postador-img_'+post_id+' img').attr('src',include_path + 'uploads/' + img_src);
				}
				$('.resposta-menu-escolher_post_'+post_id).hide();
				resposta_menu_escolher[post_id] = true;
			}
		}
		return false;
	})

	$('.post-categoria select').change(function(){
		if($(this).val() != 'Outra Categoria'){
			$('.post-categoria [type=text]').hide();
		}
		else{
			$('.post-categoria [type=text]').show();
		}
	})


	$('.form-post textarea').keydown(function(e){


    	// var textarea = this;
    	// var kc = event.wich  || event.keyCode;

	    // if( !kc || kc == 229 ) {
	    //     var ss = textarea.selectionStart - 1;
	    //     var ssv = ss || 0;
	    //     var char = textarea.value.substr(ssv,1);
	    //     kc = char.charCodeAt(0);
	    // }

	    // alert(kc);

		if((e.keyCode == 8 || e.keyCode == 229) && ($(this).val().length == 1 || $(this).val().length == 0)){
			$('.form-post [type=submit]').prop('disabled',true);
			$('.form-post [type=submit]').css('opacity','0.4');
			$('.form-post [type=submit]').css('cursor','default');
		}
		else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || e.keyCode == 229){
			$('.form-post [type=submit]').removeAttr('disabled');	
			$('.form-post [type=submit]').css('opacity','1');
			$('.form-post [type=submit]').css('cursor','pointer');		
		}
	})


	// $('.form-post textarea').on('textInput', e => {
	//     var keyCode = e.originalEvent.data.charCodeAt(0);
	//     alert(keyCode);
	//     // keyCode is ASCII of character entered.
	// })

	$('.form-post').ajaxForm({
		dataType:'json',
		url:include_path+'ajax/home_post.ajax.php',
		success: function(data){
			if(data.sucesso){
				window.location.href = include_path;
				$('.box-alert').remove();
			} else {
				$('.box-alert').remove();
				$('.form-post').append('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');
			}
		},
		error: function(xhr, status, error){
	         var errorMessage = xhr.status + ': ' + xhr.statusText
	         console.log('Error - ' + errorMessage);
		}
	})

	var categoria_interesse_id = '';
	var categoria_interesse_query = '';
	var categoria_interesse_execute = [];
	var pgatual = $('listagem').attr('pgatual');
	$('listagem').attr('pgatual',pgatual);
	var porpg = $('listagem').attr('porpg');
	var estalogado = $('estalogado').attr('condicao');
	var esta_logado_user_id = $('estalogado').attr('user_id');

	if(estalogado == 1){
		estalogado = true;
	}else{
		estalogado = false;
	}

	$(window).scroll(function() {
	    if($(window).scrollTop() + $(window).height() == $(document).height()) {
	    	pgatual = $('listagem').attr('pgatual');
	    	pgatual++;
	    	$('listagem').attr('pgatual',pgatual);
	    	porpg = $('listagem').attr('porpg');
			$.ajax({
					dataType:'json',
					url:include_path+'ajax/carrega_novos_posts.ajax.php',
					method:'post',
					data: {'acao':'carrega_novos_posts',
					'pgatual':pgatual,
					'porpg':porpg,
					'query':categoria_interesse_query,
					'execute':categoria_interesse_execute,
					'estalogado':estalogado,
					'user_id':esta_logado_user_id}
				}).done(function(data){
					$('.section-home .section-center').append(data.dados);
				})	
	    }
	});


	$('.section-home form.categorias-interesse [type=checkbox]').change(function(){
		categoria_interesse_id = $(this).attr('name');
		$('form.categorias-interesse').submit();
	});

	$('form.categorias-interesse').ajaxForm({
		dataType:'json',
		data: {'acao':'home_categoria_interesse',
			'pgatual':pgatual,
			'porpg':porpg,
			'estalogado':estalogado,
			'user_id':esta_logado_user_id},
		url:include_path+'ajax/home_categoria_interesse.ajax.php',	
		success: function(data){
			$('.section-home .section-center > div:nth-of-type(2)').empty();
			$('.section-home .section-center > div:nth-of-type(2)').append(data.dados);
			categoria_interesse_query = data.query;
			categoria_interesse_execute = data.execute;
		}
	})

	$(document).on('click','.post-single-div-conteudo-nome-editar p.editar',function(){
		var post_id = $(this).attr('post_id');
		var post_textarea = $('.textarea_post_'+post_id+' textarea');
		$('.textarea_post_'+post_id).show();
	    post_textarea.css('height', (post_textarea[0].scrollHeight + 10) +'px');
		if($(this).parent().hasClass('post-single-div-conteudo-nome-editar-simbolo-menu')){
			$(this).parent().hide();
		}
		$('.p_post_'+post_id).hide();
        return false;
	})

	$(document).on('click','.post-single-div-conteudo-nome-editar-simbolo',function(){
		$(this).find('.post-single-div-conteudo-nome-editar-simbolo-menu').show();
	})

	$('body').click(function(){
		$('.post-single-div-conteudo-nome-editar-simbolo-menu').hide();		
	})

	$(document).on('click','.post-single-div-conteudo-nome-editar p.excluir',function(){
		var r = confirm("Você tem certeza que deseja excluir?");
		if(r == true){
			var post_id = $(this).attr('post_id');
			$.ajax({
					dataType:'json',
					url:include_path+'ajax/home_post.ajax.php',
					method:'post',
					data: {'acao':'acao_exclui_post',
						'post_id':post_id}
				}).done(function(data){	
						window.location.href = include_path;
				}).fail(function(xhr, status, error){
			         var errorMessage = xhr.status + ': ' + xhr.statusText
			         console.log('Error - ' + errorMessage);
				})	
		}
		else{
			return false;
		}		
	})

	$('[name=acao_cancela_post').click(function(){
		var post_id = $(this).attr('post_id');
		$(this).parent().parent().find('textarea').val($(this).parent().parent().find('textarea').attr('value_old'));
		$(this).parent().parent().parent().parent().find('.post-single-div-conteudo-nome-editar p.editar').show();
		$('.p_post_'+post_id).show();
		$(this).parent().parent().parent().hide();
		return false;
	})

	$('.post-single-div-conteudo-textarea textarea').on('keyup',(function(e){
		if(e.keyCode == 8 && ($(this).val().length == 1 || $(this).val().length == 0)){
			console.log('entrou aqui');
			$(this).parent().find('[name=acao_altera_post]').prop('disabled',true);
			$(this).parent().find(' [name=acao_altera_post]').css('opacity','0.4');
			$(this).parent().find(' [name=acao_altera_post]').css('cursor','default');
		}
		else if((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 65 && event.keyCode <= 90)){
			$(this).parent().find(' [name=acao_altera_post]').removeAttr('disabled');	
			$(this).parent().find(' [name=acao_altera_post]').css('opacity','1');
			$(this).parent().find(' [name=acao_altera_post]').css('cursor','pointer');		
		}
	}))

	$('.post-single-div-conteudo-textarea form').ajaxForm({
		dataType:'json',
		url:include_path+'ajax/home_post.ajax.php',
		success: function(data){
			if(data.sucesso){
				window.location.href = include_path;
				$('.box-alert').remove();
			} else {
				$('.box-alert').remove();
				$('.form-post').append('<div class="box-alert erro"><img src="'+include_path+'images/close01_tamanho_01_white.png"> <p>'+data.mensagem+'</p></div>');
			}
		},
		error: function(xhr, status, error){
	         var errorMessage = xhr.status + ': ' + xhr.statusText
	         console.log('Error - ' + errorMessage);
		}
	})

	/**************** SISTEMA DE PESQUISA *******************/

	$('.header-logo .section-left form input[type=text]').on('keyup',(function(e){
		if($(this).val().length > 2){
			$.ajax({
					dataType:'json',
					url:include_path+'ajax/pesquisa.ajax.php',
					method:'post',
					data: {'acao':'pesquisar',
						'dado_pesquisa':$(this).val()}
				}).done(function(data){	
					$('.pesquisa-janela').remove();	
					$('.header-logo .section-left form').append('<div class=" box pesquisa-janela"></div>');
					if(typeof(data.tb_user.login) != "undefined" && data.tb_user.login !== null){
						$('.pesquisa-janela').append('<div class="pesquisa-janela-titulo">Cliente - Login</div>' + data.tb_user.login);
					}			
					if(typeof(data.tb_user.nome) != "undefined" && data.tb_user.nome !== null){
						$('.pesquisa-janela').append('<div class="pesquisa-janela-titulo">Cliente - Nome</div>' + data.tb_user.nome);
					}			
					if(typeof(data.tb_empresa.nome) != "undefined" && data.tb_empresa.nome !== null){
						$('.pesquisa-janela').append('<div class="pesquisa-janela-titulo">Empresa</div>' + data.tb_empresa.nome);
					}		
					if(typeof(data.tb_categoria.nome) != "undefined" && data.tb_categoria.nome !== null){
						$('.pesquisa-janela').append('<div class="pesquisa-janela-titulo">Categoria</div>' + data.tb_categoria.nome);
					}		
					if(typeof(data.tb_filial.nome) != "undefined" && data.tb_filial.nome !== null){
						$('.pesquisa-janela').append('<div class="pesquisa-janela-titulo">Filial</div>' + data.tb_filial.nome);
					}
				}).fail(function(xhr, status, error){
			         var errorMessage = xhr.status + ': ' + xhr.statusText
			         console.log('Error - ' + errorMessage);
				})			
		}
		else if($(this).val().length <= 2){
			$('.pesquisa-janela').remove();
		}
	}))

	/**************** WINDOW RESIZE *******************/
	var isMobile = false; //initiate as false
	// device detection
	if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
	    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
	    isMobile = true;
	}

	var rtime;
	var timeout = false;
	var delta = 200;
	$(window).resize(function() {
	    rtime = new Date();
	    if (!isMobile && timeout === false) {
	        timeout = true;
	        setTimeout(resizeend, delta);
	    }
	});

	function resizeend() {
	    if (new Date() - rtime < delta) {
	        setTimeout(resizeend, delta);
	    } else {
	        timeout = false;
	        location.reload();
	    }               
}

	/**************** AUTO SIZE *******************/
	autosize();
	function autosize(){
	    var text = $('.autosize');

	    text.each(function(){
	        $(this).attr('rows',1);
	        resize($(this));
	    });

	    text.on('input', function(){
	        resize($(this));
	    });
	    
	    function resize ($text) {
	        $text.css('height', 'auto');
	        $text.css('height', ($text[0].scrollHeight + 10) +'px');
	    }
	}


	/******** HELPER MASK *********/

	$('[name=cpf]').mask('999.999.999-99');
	$('[name=cnpj').mask('99.999.999/9999-99');

	var behavior = function (val) {
		return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
	},
	options = {
	    onKeyPress: function (val, e, field, options) {
	        field.mask(behavior.apply({}, arguments), options);
	    }
	};

	$('[name=telefone]').mask(behavior, options);

	$('.cria-login [name=tipo_login]').change(function(){
		var val = $(this).val();
		if(val == 'fisico'){
			$('.cria-login [name=cpf]').show();
			$('.cria-login [name=cnpj').hide();
		}
		else if(val == 'juridico'){
			$('.cria-login [name=cpf]').hide();
			$('.cria-login [name=cnpj]').show();			
		}
	})

	$('.edita-perfil [name=tipo_login]').change(function(){
		var val = $(this).val();
		if(val == 'fisico'){
			$('.edita-perfil [name=cpf]').parent().show();
			$('.edita-perfil [name=cnpj').parent().hide();
		}
		else if(val == 'juridico'){
			$('.edita-perfil [name=cpf]').parent().hide();
			$('.edita-perfil [name=cnpj]').parent().show();			
		}
	})
})

// window.onbeforeunload = function() {
//   return false;
// }