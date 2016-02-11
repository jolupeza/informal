(function( $ ) {
	'use strict';

	$(function() {
		renderFeaturedImage( $ );

		$( '.set-file' ).on( 'click', function( evt ) {

			// Stop the anchor's default behavior
			evt.preventDefault();

			var container_media = $(this).parent().next();

			// Display the media uploader
			renderMediaUploader( $, container_media );

		});

		$( '.remove-file' ).on( 'click', function( evt ) {
			// Stop the anchor's default behavior
			evt.preventDefault();

			var container_media = $(this).parent().prev();

			// Remove the image, toggle the anchors
			resetUploadForm( $, container_media );

		});

		// Funcionalidad Generar PDF
		$('#js-pdf').on('click', function(ev){
			ev.preventDefault();
			var $this = $(this);
			var nonce = $this.data('nonce');
			var id    = $this.data('id');

			if ( nonce.length > 0 )
			{
				$.post(ajaxurl, {
			        nonce   : $.trim(nonce),
			        action  : 'generate_pdf',
			        id      : id
			    }, function(data) {
			        if (data.result)
			        {
				    	alert('Se generó correctamente el PDF. Enviamos el PDF al cliente.');
			        }
			        else
			        {
			        	alert('No se pudo generar el PDF. Por favor volver a intentarlo.');
			        }
			     }, 'json');
			}

		});

		verifyStatusPdf();

	});

	/**
	 * Check if entered observation and response date. If you have
	 * not entered the field button locks to generate pdf
	 *
	 * Verify observation and response date.
	 *
	 * @since	0.1.0
	 */
	function verifyStatusPdf()
	{
		var obs = $('#mb_obs').val();
		var date = $('#mb_date_response').val();
		var button = $('#js-pdf');

		if ( obs !== undefined && date !== undefined )
		{
			if ( obs.length === 0 || date.length === 0 )
			{
				button.attr('disabled', 'disabled');
			}
		}
	}

	/**
	 * Callback function for the 'click' event of the 'Set Footer Image'
	 * anchor in its meta box.
	 *
	 * Displays the media uploader for selecting an image.
	 *
	 * @param    object    $    A reference to the jQuery object
	 * @since    0.1.0
	 */
	function renderMediaUploader($, container_media ) {
		//'use strict';

		var file_frame, image_data, json;
		var container_data = container_media.next().next();

		//var container_media = $this.parent().next();

		/**
		 * If an instance of file_frame already exists, then we can open it
		 * rather than creating a new instance.
		 */
		if ( undefined !== file_frame ) {

			file_frame.open();
			return;

		}

		/**
		 * If we're this far, then an instance does not exist, so we need to
		 * create our own.
		 *
		 * Here, use the wp.media library to define the settings of the Media
		 * Uploader. We're opting to use the 'post' frame which is a template
		 * defined in WordPress core and are initializing the file frame
		 * with the 'insert' state.
		 *
		 * We're also not allowing the user to select more than one image.
		 */
		file_frame = wp.media.frames.file_frame = wp.media({
			frame:    'post',
			state:    'insert',
			multiple: false
		});

		/**
		 * Setup an event handler for what to do when an image has been
		 * selected.
		 *
		 * Since we're using the 'view' state when initializing
		 * the file_frame, we need to make sure that the handler is attached
		 * to the insert event.
		 */
		file_frame.on( 'insert', function() {

			// Read the JSON data returned from the Media Uploader
			json = file_frame.state().get( 'selection' ).first().toJSON();
			// First, make sure that we have the URL of an image to display
			if ( 0 > $.trim( json.url.length ) ) {
				return;
			}

			var name = json.url.split('/');
			name = name[ name.length - 1 ];

			// After that, set the properties of the image and display it
			container_media
				.children( 'p' )
					.find('.name')
					.text( name )
					.parent()
					.show()
				.parent()
				.removeClass( 'hidden' );

			container_media
				.children( 'img' )
					.attr( 'src', json.url )
					//.attr( 'alt', json.caption )
					//.attr( 'title', json.title )
					.show()
				.parent()
				.removeClass( 'hidden' );

			// Next, hide the anchor responsible for allowing the user to select an image
			container_media
				.prev()
				.hide();

			// Display the anchor for the removing the featured image
			container_media
				.next()
				.show();

			// Store the image's information into the meta data fields
			container_data.find('.hd-src').val( json.url );
			container_data.find('.hd-description').val( json.description );
			container_data.find('.hd-title').val( json.title );
			// /container_data.find('.hd-alt').val( json.alt );
			/*$( '#slider-' + num + '-src' ).val( json.url );
			$( '#slider-' + num + '-title' ).val( json.title );
			$( '#slider-' + num + '-alt' ).val( json.alt );*/

		});

		// Now display the actual file_frame
		file_frame.open();

	}

	/**
	 * Callback function for the 'click' event of the 'Remove Footer Image'
	 * anchor in its meta box.
	 *
	 * Resets the meta box by hiding the image and by hiding the 'Remove
	 * Footer Image' container.
	 *
	 * @param    object    $    A reference to the jQuery object
	 * @since    0.2.0
	 */
	function resetUploadForm( $, container_media ) {
		//'use strict';

		// First, we'll hide the image
		container_media
			.children( 'img' )
			.hide();

		container_media
			.children( 'p' )
			.hide();

		// Then display the previous container
		container_media
			.prev()
			.show();

		// We add the 'hidden' class back to this anchor's parent
		container_media
			.next()
			.hide()
			.addClass( 'hidden' );

		// Finally, we reset the meta data input fields
		container_media.next().next()
			.children()
			.val( '' );

	}

	/**
	 * Checks to see if the input field for the thumbnail source has a value.
	 * If so, then the image and the 'Remove featured image' anchor are displayed.
	 *
	 * Otherwise, the standard anchor is rendered.
	 *
	 * @param    object    $    A reference to the jQuery object
	 * @since    1.0.0
	 */
	function renderFeaturedImage( $ ) {
		/* If a thumbnail URL has been associated with this image
		 * Then we need to display the image and the reset link.
		 */

		$('.media-info').each(function(){
			if ( '' !== $.trim( $(this).find('.hd-src').val()) )
			{
				$(this).prev().prev().removeClass('hidden');

				$(this).prev().prev().prev().hide();

				$(this).prev().removeClass('hidden');
			}
		});

	}

})( jQuery );