<?php
/**
 * Displays the user interface for the Informal Manager meta box.
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
        $featured = isset( $values['mb_featured'] ) ? esc_attr( $values['mb_featured'][0] ) : '';
        $date = isset( $values['mb_date'] ) ? esc_attr( $values['mb_date'][0] ): '';
        
        $ev_featured = isset($values['mb_ev_featured']) ? esc_attr($values['mb_ev_featured'][0]) : '';
        $ev_weekend = isset($values['mb_ev_weekend']) ? esc_attr($values['mb_ev_weekend'][0]) : '';

        wp_nonce_field( 'post_meta_box_nonce', 'meta_box_nonce' );
    ?>

    <p class="content-mb">
        <label for="mb_featured">Destacado por Categoría:</label>
        <input type="checkbox" name="mb_featured" id="mb_featured" <?php checked($featured, 'on'); ?> />
    </p>

    <p class="content-mb">
        <label for="mb_subfeatured">Subdestacado:</label>
        <input type="checkbox" name="mb_subfeatured" id="mb_subfeatured" <?php checked($subfeatured, 'on'); ?> />
    </p>

    <p class="content-mb">
        <label for="mb_date">Fecha del evento:</label>
        <input type="date" name="mb_date" id="mb_date" value="<?php echo $date; ?>" />
    </p>
    
    <p class="content-mb">
        <label for="mb_ev_featured">Evento Destacado:</label>
        <input type="checkbox" name="mb_ev_featured" id="mb_ev_featured" <?php checked($ev_featured, 'on'); ?> />
    </p>
    
    <p class="content-mb">
        <label for="mb_ev_weekend">Evento Fin de semana:</label>
        <input type="checkbox" name="mb_ev_weekend" id="mb_ev_weekend" <?php checked($ev_weekend, 'on'); ?> />
    </p>
</div><!-- #single-post-meta-manager -->