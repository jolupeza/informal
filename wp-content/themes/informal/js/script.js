var j = jQuery.noConflict();

(function($){
	var $body = j('body'),
		$footer = j('.Footer'),
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

		// Mobile Slidebars
	    j.slidebars();

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

		// Load more posts
		$body.on('click', '#js-readmore-content', function(ev){
			ev.preventDefault();

			var $this    = j(this);
			var paged    = parseInt($this.data('paged'));
			var author   = $this.data('author');
			var category = $this.data('category');
			var tag      = $this.data('tag');
			var search   = $this.data('search');
			var loader   = j('.Main-content-loader');
			var content  = j('.Main-content-wrapper');

			if(paged > 0) {
				loader.removeClass('hidden');

				j.post(InformalAjax.url, {
					nonce: InformalAjax.nonce,
					action: 'get_posts',
					paged: paged,
					author: author,
					category: category,
					tag: tag,
					search: search
				}, function(data){
					loader.addClass('hidden');
					if(data.result) {
						content.append(data.content);
						if(data.paged) {
							paged++;
							$this.data('paged', paged);
						} else {
							$this.parent().parent().remove();
						}
					}
				}, 'json').fail(function(){
					alert('No se pudo realizar la operación solicitada. Por favor vuelva a intentarlo.');
				});
			}
		});

		// Show advertising
	    j('#js-view-banner').on('click', function(ev){
	    	ev.preventDefault();
	    	var $this = j(this);
	    	var imageShort = j("#js-banner-short");
	    	var imageLarge = j("#js-banner-large");

	    	if($this.hasClass('active')) {
	    		imageLarge.slideUp('200', function(){
	    			$this.text('ver publicidad');
	    			imageShort.fadeIn('slow', function() {
	    				$this.removeClass('active');
	    			});
	    		});
	    	} else {
	    		imageShort.fadeOut('fast', function() {
	    			$this.text('ocultar publicidad');
	    			imageLarge.slideDown('slow', function(){
	    				$this.addClass('active');
	    			});
	    		});
	    	}
	    });

	    // Mostrar publicidad si existe
	    setTimeout(function(){
	    	var $this = j('#js-view-banner');
	    	var imageShort = j("#js-banner-short");
	    	var imageLarge = j("#js-banner-large");

	    	imageLarge.slideUp('200', function(){
	    		$this.text('ver publicidad');
	    		imageShort.fadeIn('slow', function() {
	    			$this.removeClass('active');
	    		});
	    	});
	    }, 5000);

	    $body.on('click', '.CaretMenu', function(ev){
	    	ev.preventDefault();
	    	var $this = j(this);
	    	var menu = j('.Category-info');
	    	var wrapper = menu.find('.container');
	    	var id = parseInt($this.data('id'));
	    	var loader = j('.Category-loader');

	    	if(menu.hasClass('active')) {
	    		menu.fadeOut('slow').removeClass('active');
	    		wrapper.html('');
	    	} else {
	    		if (id > 0) {
	    			loader.removeClass('hidden');

		    		j.post(InformalAjax.url, {
		    			nonce: InformalAjax.nonce,
		    			action: 'get_menu',
		    			id: id
		    		}, function(data){
						if(data.result) {
							loader.addClass('hidden');
							wrapper.append(data.content);
						}
	    			}, 'json').fail(function(){
	    				loader.addClass('hidden');
	    				alert('No se pudo realizar la operación solicitada. Por favor vuelva a intentarlo.');
	    			});
	    		}

	    		menu.fadeIn('slow').addClass('active');
	    	}
	    });

	    j('.js-display-search').on('click', function(ev){
	    	ev.preventDefault();
	    	var $this = j(this);
	    	var search = j('.Search');
	    	var form = j('.Search-form');

	    	if(search.hasClass('active')) {
	    		search.fadeOut('slow', function() {
	    			search.removeClass('active');
	    		});
	    	} else {
	    		search.fadeIn('slow', function() {
	    			form.find('input:first').focus();
	    			search.addClass('active');
	    		});
	    	}
	    });
	});
})(jQuery);