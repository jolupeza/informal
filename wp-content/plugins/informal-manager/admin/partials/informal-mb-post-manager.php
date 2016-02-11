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
<div id="mb-post-id">

	<?php
		$values = get_post_custom( get_the_ID() );
    	$subfeatured = isset( $values['mb_subfeatured'] ) ? esc_attr( $values['mb_subfeatured'][0] ) : '';

		wp_nonce_field( 'post_meta_box_nonce', 'meta_box_nonce' );
	?>

	<p class="content-mb">
		<label for="mb_subfeatured">Subdestacado:</label>
		<input type="checkbox" name="mb_subfeatured" id="mb_subfeatured" <?php checked($subfeatured, 'on'); ?> />
	</p>
</div><!-- #single-post-meta-manager -->