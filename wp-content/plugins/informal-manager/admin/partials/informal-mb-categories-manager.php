<?php
	$t_id = $tag->term_id;
    $catMeta = get_option( "category_$t_id");
    $colour = isset($catMeta['mb_colour']) ? esc_attr($catMeta['mb_colour']) : '';

    $colores = array(
		'rojo'    => 'f30632',
		'azul'    => '1568e3',
		'morado'  => '4906f3',
		'naranja' => 'e28e0a'
	);
    wp_nonce_field('cat_meta_box_nonce', 'meta_box_nonce');
?>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for="mb_colour"><?php _e('Color de la categoría', THEMEDOMAIN); ?></label>
	</th>
	<td>
	    <select name="mb_colour" id="mb_colour">
	    	<option value="">-- Indicar color --</option>
	    	<?php foreach($colores as $key => $value ) : ?>
				<option value="<?php echo $key; ?>" <?php selected( $colour, $key ); ?> style="background-color: #<?php echo $value; ?>; color: white;"><?php echo $key; ?></option>
			<?php endforeach ?>
	    </select>
        <span class="description"><?php _e('Seleccionar el color de la categoría', THEMEDOMAIN); ?></span>
	</td>
</tr>