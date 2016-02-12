var j = jQuery.noConflict();

(function($){
	var $body = j('body'),
	    $window = j(window);

	$window.scroll(function(event){
		if($window.scrollTop() > 150) {
			j('.scrollToTop').fadeIn();
		} else {
			j('.scrollToTop').fadeOut();
		}
	});

	j(document).on('ready', function(){
		// Functionality scroll to top
		j('.scrollToTop').on('click', function(ev){
			ev.preventDefault();
			j('html, body').animate({scrollTop: 0}, 800);
		});

		// Validation form subscribers
		j('.Frm').formValidation({
			locale: 'es_ES',
			framework: 'bootstrap',
			icon: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				mb_email: {
					validators: {
						remote: {
                            url: InformalAjax.url,
                            type: 'POST',
                            data: {
                            	nonce: InformalAjax.nonce,
                               	action: 'check_email',
                               	mb_email: j( "#mb_email" ).val()
                            },
                            message: 'Este correo ya se encuentra registrado'
                        }
					}
				}
			}
		}).on('err.field.fv', function(e, data){
			var field = e.target;
			j('small.help-block[data-bv-result="INVALID"]').addClass('hide');
		}).on('success.form.fv', function(e){
			e.preventDefault();

			var $form = j(e.target),
				fv    = j(e.target).data('formValidation');
			var email = j('#mb_email').val();

			var loader = $form.parent().find('.Footer-loader');
			var msg = $form.parent().find('.Footer-text--msg');

			loader.removeClass('hidden');
			j.post(InformalAjax.url, {
				nonce: InformalAjax.nonce,
				action: 'register_subscriber',
				email: email
			}, function(data){
				if(data.result) {
					msg.text('Se agregó correctamente a nuestra lista de suscriptores.');
					fv.resetForm(true);
				} else {
					msg.text('Por favor verifique que los datos ingresados son válidos o que el correo no se encuentre ya registrado.');
				}

				loader.addClass('hidden');
        		msg.fadeIn('slow');
        		setTimeout(function(){
        			msg.fadeOut('slow', function(){
        				j(this).text('');
        			});
        		}, 5000);
			}, 'json').fail(function(){
				loader.addClass('hidden');
				alert('No se pudo realizar la operación solicitada. Por favor vuelva a intentarlo.');
			});
		});
	});
})(jQuery);