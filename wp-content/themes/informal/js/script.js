var j = jQuery.noConflict();

(function($){
	var $body   = j('body'),
		$footer = j('.Footer'),
		$window = j(window);
	var monthsAbr = ['ene.', 'feb.', 'mar.', 'abr.', 'may.', 'jun.', 'jul.', 'ago.', 'sep.', 'oct.', 'nov.', 'dic.'];
	var months    = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

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
			var parent   = parseInt($this.data('parent'));
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
					search: search,
					parent: parent
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

	    j('#js-dtp-events').datetimepicker({
	        inline: true,
	        useCurrent: false,
	        format: 'DD/MM/YYYY'
	    });

		setDates(moment().format('YYYY-MM-DD'), 31, 0);
		// setDates(moment().format('YYYY-MM-DD'), 52, 0);

	    function setDates(date, category, filter)
	    {
	    	filter = parseInt(filter);

	    	j.post(InformalAjax.url, {
				nonce: InformalAjax.nonce,
				action: 'get_events',
				date: date,
				category: category,
				filter: filter
	    	}, function(data) {
	    		if(data.result) {
	    			var dates = data.dates;
	    			var numDates = [];

	    			j.each(dates, function(index, val) {
	    				numDates.push(moment(val));
	    			});

					j("#js-dtp-events").data('DateTimePicker').enabledDates(numDates);
	    		} else {
	    			j('td.day').addClass('disabled');
	    			var content = j('.WidgetCalendar-wrapper .mCSB_container');
    				var html = '<div class="WidgetCalendar-event"><h6 class="text-center">No se encontraron eventos para esta fecha</h6></div><!-- end WidgetCalendar-event -->';
    				content.html(html);
	    		}
	    	}, 'json').fail(function(){
				alert('No se pudo realizar la operación solicitada. Por favor vuelva a intentarlo.');
			});
	    }

	    j('.WidgetCalendar-wrapper').mCustomScrollbar({
	    	axis: "y",
	    	live: "true"
	    });

	    j('.next').on('click', function(ev){
	    	var $this = j(this);

	    	updateCalendar($this, 'next');
	    	j('#filter-event option[value="0"]').attr('selected', 'selected');
	    });

	    j('.prev').on('click', function(ev){
	    	var $this = j(this);

	    	updateCalendar($this, 'prev');
	    	j('#filter-event option[value="0"]').attr('selected', 'selected');
	    });

	    j('.month').on('click', function(ev){
	    	var $this = j(this);
	    	var wrapper = j('.datepicker-months');

	    	if(wrapper.css('display') == 'block') {
		    	updateCalendar($this, '');
	    		j('#filter-event option[value="0"]').attr('selected', 'selected');
	    	}
	    });

	    function updateCalendar($this, event)
	    {
	    	var month, year, nowDate, nextMonth, newDate;
	    	var content = j('.WidgetCalendar-wrapper .mCSB_container');
	    	var category = j('.WidgetCalendar-wrapper').data('category');
	    	var loader = j('#js-loading-events');

			loader.removeClass('hidden');
			j('.WidgetCalendar-wrapper').addClass('loader');

			if(event.length === 0) {
				var wrapper = j('.datepicker-months');
	    		month = $this.text();
		    	month = monthsAbr.indexOf(month) + 1;
		    	month = month.toString();

		    	if(month.length === 1) {
		    		nextMonth = '0' + month;
		    	}

		    	year = wrapper.find('.picker-switch').text();
			} else {
		    	if(event === 'next') {
					nowDate = $this.prev('.picker-switch').text();
		    	} else if(event === 'prev') {
		    		nowDate = $this.next('.picker-switch').text();
		    	}

				month = nowDate.split(' ')[0];
				year  = nowDate.split(' ')[1];

		    	if(event === 'next') {
		    		if(month === 'diciembre') {
		    			nextMonth = 1;
		    			year++;
		    		} else {
		    			nextMonth = months.indexOf(month) + 2;
		    		}
		    	} else if(event === 'prev') {
		    		if(month === 'enero') {
		    			nextMonth = 12;
		    			year--;
		    		} else {
		    			nextMonth = months.indexOf(month);
		    		}
		    	}
		    	nextMonth = nextMonth.toString();

		    	if(nextMonth.length === 1) {
		    		nextMonth = '0' + nextMonth;
		    	}
			}

	    	newDate = year + '-' + nextMonth + '-01';
	    	setDates(newDate, category, 0);

	    	j.post(InformalAjax.url, {
				nonce: InformalAjax.nonce,
				action: 'update_events',
				date: newDate,
				category: category,
			}, function(data){
				loader.addClass('hidden');
				content.html('');

				if(data.result) {
					content.html(data.content);
				} else {
					var html = '<div class="WidgetCalendar-event"><h6 class="text-center">No se encontraron eventos para esta fecha</h6></div><!-- end WidgetCalendar-event -->';
					content.html(html);
				}
				j('.WidgetCalendar-wrapper').removeClass('loader');

			}, 'json').fail(function(){
				alert('No se pudo realizar la operación solicitada. Por favor vuelva a intentarlo.');
			});
	    }

		j('#filter-event').on('change', function(ev){
			ev.preventDefault();
			var $this = j(this);
			var value = parseInt($this.val());
			var category = $this.data('category');
	    	var content = j('.WidgetCalendar-wrapper .mCSB_container');
	    	var loader = j('#js-loading-events');

	    	var dtpMonths = j('.datepicker-months');
	    	var dtpYears = j('.datepicker-years');

	    	if(dtpMonths.css('display') == 'block' || dtpYears.css('display') == 'block') {
	    		return false;
	    	}

			if(value === 0) {
				return false;
			}

			var month, year, newDate;
	    	var dtpDays = j('.datepicker-days');
			var nowDate = dtpDays.find('.picker-switch').text();
			var arrDate = nowDate.split(' ');

			month  = arrDate[0];
			year = arrDate[1];

	    	month = months.indexOf(month) + 1;
	    	month = month.toString();

	    	if(month.length === 1) {
	    		month = '0' + month;
	    	}

	    	newDate = year + '-' + month + '-01';

			if (value === 1 || value === 2) {
		    	j.post(InformalAjax.url, {
					nonce: InformalAjax.nonce,
					action: 'filter_events',
					filter: value,
					category: category,
					date: newDate,
				}, function(data){
					loader.addClass('hidden');
					content.html('');

					if(data.result) {
						setDates(newDate, category, value);
						content.html(data.content);
					} else {
						var html = '<div class="WidgetCalendar-event"><h6 class="text-center">No se encontraron eventos para esta fecha</h6></div><!-- end WidgetCalendar-event -->';
						content.html(html);
					}
					j('.WidgetCalendar-wrapper').removeClass('loader');

				}, 'json').fail(function(){
					alert('No se pudo realizar la operación solicitada. Por favor vuelva a intentarlo.');
				});
			} else {
				return false;
			}
		});
	});
})(jQuery);