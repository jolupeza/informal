<?php
/**
 * Displays the user interface for the Ilc Manager meta box.
 *
 * This is a partial template that is included by the Ilc Manager
 * Admin class that is used to display all of the information that is related
 * to the page meta data for the given page.
 *
 * @package    SPMM
 */
?>
<div id="mb-banners-id">

	<?php
		$values = get_post_custom( get_the_ID() );
    	$link = isset( $values['mb_link'] ) ? esc_attr( $values['mb_link'][0] ) : '';
    	$target = isset( $values['mb_target'] ) ? esc_attr( $values['mb_target'][0] ) : '';
		$image = isset( $values['mb_image'] ) ? esc_attr($values['mb_image'][0]) : '';

		wp_nonce_field( 'banners_meta_box_nonce', 'meta_box_nonce' );
	?>

	<p class="content-mb">
		<label for="mb_link">Enlace:</label>
		<input type="text" name="mb_link" id="mb_link" value="<?php echo $link; ?>" />
	</p>

	<p class="content-mb">
		<label for="mb_target">Target:</label>
		<input type="checkbox" name="mb_target" id="mb_target" <?php checked($target, 'on'); ?> />
	</p>

	<fieldset class="GroupForm">
		<legend class="GroupForm-legend">Imagen</legend>

		<div class="container-upload-file GroupForm-wrapperImage">
			<p class="btn-add-file">
				<a title="Set Slider Image" href="javascript:;" class="set-file button button-primary">Añadir</a>
			</p>

			<div class="hidden media-container">
				<img src="<?php echo $image; ?>" alt="<?php //echo get_post_meta( $post->ID, 'slider-1-alt', true ); ?>" title="<?php //echo get_post_meta( $post->ID, 'slider-1-title', true ); ?>" />
			</div><!-- .media-container -->

			<p class="hidden">
				<a title="Qutar imagen" href="javascript:;" class="remove-file button button-secondary">Quitar</a>
			</p>

			<p class="media-info">
				<input class="hd-src" type="hidden" name="mb_image" value="<?php echo $image; ?>" />
			</p><!-- .media-info -->
		</div><!-- end container-upload-file -->

	</fieldset><!-- end GroupFrm -->
</div><!-- #single-post-meta-manager -->