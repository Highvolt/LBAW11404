function start() {
	
	$.getJSON('API/utilizadores/obter.php', function(data) {
		if(data) {
			console.log("logged");
			$('#login_form').css("display", "none");
			$('#userbox').animate({
					'top' : '0%'
				}, 2000);
			$('#page').animate({
				'opacity' : 0,
				'top' : '100%'
			}, 2000, function() {
				initcal();
				$('#page').css({
					'background' : 'none',
					'top' : '14%'
				});
				$('#page').animate({
					'opacity' : '1'
				}, 2000);
				
			});
			
			$('#user_status').remove();
			$('<div>').attr('id', 'user_status').appendTo('#userbox');
			$('<x>').html('Olá, ' + data['nome'] + '!').appendTo('#user_status');
			$('<input>').css({'margin-left':'5%'}).attr({
				'type' : 'button',
				'value' : 'Sair'
			}).click(function() {
				$.getJSON('API/utilizadores/sair.php', function(data) {
					window.location.reload();
				});

			}).appendTo('#user_status');
			if(data['img'] != null) {
					$('<img>').attr({
						"id" : 'imagemPerfil',
						"src" : data['img'],
						"height" : '80%'
					}).css({
						'position' : 'absolute',
						'right' : '5%'
					}).prependTo('#user_status');
					$('#imagemPerfil').click(function() {
						//alert("CLICK");
						$.ajax({
							url : 'templates/perfil.html'
						}).done(function(msg) {
							//$('#user_status').remove();
							$('#user_menus').remove();
							$('#calendar_space2').remove();
							$('<div>').attr('id', 'user_status').html(msg).appendTo('#inner_info_box');
						});
					});
			}
			
			$.ajax({
				url : 'templates/menus.html'
			}).done(function(msg) {
				$('<div>').attr('id', 'user_menus').html(msg).appendTo('#inner_info_box');
			});
			//alert('AZUL');
		} else {
			console.log("not logged");

			$.ajax({
				url : 'templates/login.html',

			}).done(function(msg) {
				$('#user_status').remove();
				$('<div>').attr('id', 'user_status').html(msg).appendTo('#inner_info_box');
				login_button();
				registarb();
				$('#rec').click(function() {
				$.ajax({
					url : 'templates/recover_pass.html',

				}).done(function(msg) {
					$('#user_status').remove();
					$('<div>').attr('id', 'user_status').html(msg).appendTo('#inner_info_box');
					captcha();
				});
		
		});
			});

		}
	});
}

function registarb() {
	$('#reg').click(function() {
		$.ajax({
			url : 'templates/registar.html',

		}).done(function(msg) {
			$('#user_status').remove();
			$('<div>').attr('id', 'user_status').html(msg).appendTo('#inner_info_box');
			$('#regist_b').click(registbutton);
			$('#regist_c').click(function() {
				start();
			});
		});
	});

}

/*function criarReuniao() {
	$('#user_criar_reuniao').click(function() {
		$.ajax({
			url : 'API/re',
		});
	});
}*/

function login_button() {
	$("#login_b").click(function() {

		$.ajax({
			url : 'API/utilizadores/entrar.php',
			dataType : 'json',
			data : {
				'username' : $('#username').val(),
				'password' : $('#password').val()
			},
			type : 'POST'

		}).done(function(msg) {
			$.getJSON('API/utilizadores/update_google_calendar_data.php', function(msg) {
				console.log(msg);
			});
			console.log(msg);
			start();
		});
		$("#login_b").remove();
		$('<p>').html('A entrar...').appendTo('#login_form');

	});

}

var Params;

$(document).ready(function() {
	Params = getUrlVars();
	if( typeof Params['code'] !== "undefined" && Params['code']) {

		$.ajax({
			url : 'API/utilizadores/recuperar_password.php',
			dataType : 'json',
			data : {
				'code' : Params['code'],
				'ts' : Params['ts'],
				'username' : Params['username']
			}

		}).done(function(msg) {
			console.log(msg);
			if(msg == "ok") {
				$.ajax({
					url : 'templates/new_pass.html',

				}).done(function(msg) {
					$('#user_status').remove();
					$('<div>').attr('id', 'user_status').html(msg).appendTo('#inner_info_box');
				});
			}else{
				alert(msg);
				start();
			}
		});
	} else {
		start();
	}

});
