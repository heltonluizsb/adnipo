$(function(){

	/**************** MESSENGER *******************/

	$('.box-messenger textarea').on('keydown',(function(e){
		if(e.keyCode == 8 && ($(this).val().length == 1 || $(this).val().length == 0)){
			$('.form-post [type=submit]').prop('disabled',true);
			$('.form-post [type=submit]').css('opacity','0.4');
			$('.form-post [type=submit]').css('cursor','default');
		}else if((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 65 && event.keyCode <= 90)){
			$('.form-post [type=submit]').removeAttr('disabled');	
			$('.form-post [type=submit]').css('opacity','1');
			$('.form-post [type=submit]').css('cursor','pointer');		
		}else if((event.keyCode == 13) && ($(this).val().length > 0)){
			e.preventDefault();
			var user_id = $(this).attr('user_id');
			var remetente_id = $(this).attr('remetente_id');
			var user_img = $(this).attr('user_img');
			var mensagem = $(this).val();
			$.ajax({
					dataType:'json',
					url:include_path+'ajax/messenger.ajax.php',
					method:'post',
					data: {'acao':'insere_mensagem',
					'user_id':user_id,
					'remetente_id':remetente_id,
					'mensagem':mensagem}
				}).done(function(data){
					if(data.sucesso){
						$('.chat-area-wraper').append('<div class="chat-single-me"><div class="chat-single-text"><p>'+mensagem+'</p></div><div class="chat-single-img"><img src="'+user_img+'"></div></div>');
						$('.box-messenger textarea').val('');
					}else{
						$('.chat-area-wraper').append('<div class="chat-single-me"><div class="chat-single-text"><p> erro na mensagem </p></div><div class="chat-single-img"><img src="'+user_img+'"></div></div>');
						$('.box-messenger textarea').val('');
					}
				}).fail(function(xhr, status, error){
			        var errorMessage = xhr.status + ': ' + xhr.statusText
			        console.log('Error - ' + errorMessage);
				})
		} else if((event.keyCode == 13) && ($(this).val().length == 0)){
			e.preventDefault();			
		} else if((event.keyCode == 32) && ($(this).val().length == 0)){
			e.preventDefault();			
		}
	}))

	setInterval(function(){
		var user_id = $('.box-messenger textarea').attr('user_id');
		var remetente_id = $('.box-messenger textarea').attr('remetente_id');
		$.ajax({
			dataType:'json',
			url:include_path+'ajax/messenger.ajax.php',
			method:'post',
			data: {'acao':'pega_mensagens',
			'user_id':user_id,
			'remetente_id':remetente_id}
		}).done(function(data){
			if(data.sucesso){
				$('.chat-area-wraper').append(data.mensagem);
			}else{
				$('.chat-area-wraper').append('<div class="chat-single-me"><div class="chat-single-text"><p> erro na mensagem </p></div><div class="chat-single-img"><img src="'+user_img+'"></div></div>');
			}
		}).fail(function(xhr, status, error){
	        var errorMessage = xhr.status + ': ' + xhr.statusText
	        console.log('Error - ' + errorMessage);
		})
	},3000);

	$('.remetente-single').click(function(){
		$('.remetente-single').each(function(){
			$(this).removeClass('remetente-single-selected');
		})
		$(this).addClass('remetente-single-selected');
		$('.chat-area-wraper').empty();

		var user_id = $(this).attr('user_id');
		var remetente_id = $(this).attr('remetente_id');
		$('.box-messenger textarea').attr('remetente_id',remetente_id);
		$.ajax({
			dataType:'json',
			url:include_path+'ajax/messenger.ajax.php',
			method:'post',
			data: {'acao':'pega_todas_mensagens_por_user',
			'user_id':user_id,
			'remetente_id':remetente_id}
		}).done(function(data){
			if(data.sucesso){
				$('.chat-area-wraper').append(data.mensagem);
			}else{
				$('.chat-area-wraper').append('<div class="chat-single-me"><div class="chat-single-text"><p> erro na mensagem </p></div><div class="chat-single-img"><img src="'+user_img+'"></div></div>');
				$('.box-messenger textarea').val('');
			}
		}).fail(function(xhr, status, error){
	        var errorMessage = xhr.status + ': ' + xhr.statusText
	        console.log('Error - ' + errorMessage);
		})
	})

	MensagemInicio();

	function MensagemInicio(){
		if($('semget').length > 0){
			$('.remetente-single:first-of-type').click();
		}
	}
})

	