<?php
/***********************************************************************************************/
/* Add a menu option to link to the customizer */
/***********************************************************************************************/
add_action('admin_menu', 'display_custom_options_link');
function display_custom_options_link() {
	add_theme_page('Theme Informal Opciones', 'Theme Informal Opciones', 'edit_theme_options', 'customize.php');
}

/***********************************************************************************************/
/* Add options in the theme customizer page */
/***********************************************************************************************/
add_action('customize_register', 'informal_customize_register');
function informal_customize_register($wp_customize) {
	// Logo
	$wp_customize->add_section('informal_logo', array(
		'title' => __('Logo', THEMEDOMAIN),
		'description' => __('Le permite cargar un logo personalizado.', THEMEDOMAIN),
		'priority' => 35
	));

	$wp_customize->add_setting('informal_custom_settings[logo]', array(
		'default' => IMAGES . '/logo.png',
		'type' => 'option'
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'logo', array(
		'label' => __('Sube tu logo', THEMEDOMAIN),
		'section' => 'informal_logo',
		'settings' => 'informal_custom_settings[logo]'
	)));

	$wp_customize->add_setting('informal_custom_settings[logo_footer]', array(
		'default' => IMAGES . '/logo_footer.png',
		'type' => 'option'
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'logo_footer', array(
		'label' => __('Logo en el footer', THEMEDOMAIN),
		'section' => 'informal_logo',
		'settings' => 'informal_custom_settings[logo_footer]'
	)));

	// Links Social Media
	$wp_customize->add_section('informal_social', array(
		'title' => __( 'Links Redes Sociales', THEMEDOMAIN),
		'description' => __('Mostrar links a redes sociales', THEMEDOMAIN),
		'priority' => 36
	));

	$wp_customize->add_setting('informal_custom_settings[display_social_link]', array(
		'default' => 0,
		'type' => 'option'
	));

	$wp_customize->add_control('informal_custom_settings[display_social_link]', array(
		'label' => __('¿Mostrar links?', THEMEDOMAIN),
		'section' => 'informal_social',
		'settings' => 'informal_custom_settings[display_social_link]',
		'type' => 'checkbox'
	));

	// Facebook
	$wp_customize->add_setting('informal_custom_settings[facebook]', array(
		'default' => '',
		'type'    => 'option'
	));

	$wp_customize->add_control('informal_custom_settings[facebook]', array(
		'label'    => __('Facebook', THEMEDOMAIN),
		'section'  => 'informal_social',
		'settings' => 'informal_custom_settings[facebook]',
		'type'     => 'text'
	));

	// Twitter
	$wp_customize->add_setting('informal_custom_settings[twitter]', array(
		'default' => '',
		'type'    => 'option'
	));

	$wp_customize->add_control('informal_custom_settings[twitter]', array(
		'label'    => __('Twitter', THEMEDOMAIN),
		'section'  => 'informal_social',
		'settings' => 'informal_custom_settings[twitter]',
		'type'     => 'text'
	));

	// Information
	$wp_customize->add_section('informal_info', array(
		'title' => __( 'Datos de la empresa', THEMEDOMAIN),
		'description' => __('Configurar información sobre la empresa', THEMEDOMAIN),
		'priority' => 37
	));

	// About
	$wp_customize->add_setting('informal_custom_settings[about]', array(
		'default' => '',
		'type'    => 'option'
	));

	$wp_customize->add_control('informal_custom_settings[about]', array(
		'label'    => __('Acerca Footer', THEMEDOMAIN),
		'section'  => 'informal_info',
		'settings' => 'informal_custom_settings[about]',
		'type'     => 'textarea'
	));

	// Email Contact
	$wp_customize->add_setting('informal_custom_settings[email_contact]', array(
		'default' => '',
		'type'    => 'option'
	));

	$wp_customize->add_control('informal_custom_settings[email_contact]', array(
		'label'    => __('Email de contacto', THEMEDOMAIN),
		'section'  => 'informal_info',
		'settings' => 'informal_custom_settings[email_contact]',
		'type'     => 'text'
	));

	/*
	// Phone
	$wp_customize->add_setting('aduni_custom_settings[phone]', array(
		'default' => '',
		'type'    => 'option'
	));

	$wp_customize->add_control('aduni_custom_settings[phone]', array(
		'label'    => __('Teléfono', THEMEDOMAIN),
		'section'  => 'aduni_info',
		'settings' => 'aduni_custom_settings[phone]',
		'type'     => 'text'
	));

	$wp_customize->add_setting('aduni_custom_settings[whatsapp]', array(
		'default' => '',
		'type'    => 'option'
	));

	$wp_customize->add_control('aduni_custom_settings[whatsapp]', array(
		'label'    => __('Whatsapp', THEMEDOMAIN),
		'section'  => 'aduni_info',
		'settings' => 'aduni_custom_settings[whatsapp]',
		'type'     => 'text'
	));

	// Parallax Home Section
	$wp_customize->add_section('aduni_parallax', array(
		'title' => __('Imagen Parallax', THEMEDOMAIN),
		'description' => __('Le permite cargar imagen parallax del home.', THEMEDOMAIN),
		'priority' => 38
	));

	$wp_customize->add_setting('aduni_custom_settings[parallax]', array(
		'default' => '',
		'type' => 'option'
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'parallax', array(
		'label' => __('Sube tu imagen', THEMEDOMAIN),
		'section' => 'aduni_parallax',
		'settings' => 'aduni_custom_settings[parallax]'
	)));

	// Slogan Parallax Home
	$wp_customize->add_setting('aduni_custom_settings[parallax_slogan]', array(
		'default' => '',
		'type'    => 'option'
	));

	$wp_customize->add_control('aduni_custom_settings[parallax_slogan]', array(
		'label'    => __('Slogan Parallax Home', THEMEDOMAIN),
		'section'  => 'aduni_parallax',
		'settings' => 'aduni_custom_settings[parallax_slogan]',
		'type'     => 'text'
	));

	// Precio Curso
	$wp_customize->add_section('aduni_curso', array(
		'title' => __( 'Precio de Curso Libre', THEMEDOMAIN),
		'description' => __('Configurar el precio de los cursos libres', THEMEDOMAIN),
		'priority' => 39
	));

	// Phone
	$wp_customize->add_setting('aduni_custom_settings[course]', array(
		'default' => '',
		'type'    => 'option'
	));

	$wp_customize->add_control('aduni_custom_settings[course]', array(
		'label'    => __('Precio', THEMEDOMAIN),
		'section'  => 'aduni_curso',
		'settings' => 'aduni_custom_settings[course]',
		'type'     => 'text'
	));

	// Image Class Shared
	$wp_customize->add_section('aduni_classshared', array(
		'title' => __('Imagen Clase Compartida', THEMEDOMAIN),
		'description' => __('Imagen a mostrar en Clases Compartidas del home.', THEMEDOMAIN),
		'priority' => 40
	));

	$wp_customize->add_setting('aduni_custom_settings[classshared_big]', array(
		'default' => IMAGES . '/classshared.jpg',
		'type' => 'option'
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'classshared_big', array(
		'label' => __('Imagen resoluciones mayores a 992', THEMEDOMAIN),
		'section' => 'aduni_classshared',
		'settings' => 'aduni_custom_settings[classshared_big]'
	)));

	$wp_customize->add_setting('aduni_custom_settings[classshared_small]', array(
		'default' => IMAGES . '/classshared-movil.jpg',
		'type' => 'option'
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'classshared_small', array(
		'label' => __('Imagen resoluciones menores a 992', THEMEDOMAIN),
		'section' => 'aduni_classshared',
		'settings' => 'aduni_custom_settings[classshared_small]'
	)));

	// Image Phone and Whatsapp
	$wp_customize->add_section('aduni_whatsapp_phone', array(
		'title' => __('Imágenes Central Telefónica y Whatsapp', THEMEDOMAIN),
		'description' => __('Asignar las imágenes de Central Teléfonica y Whatsapp', THEMEDOMAIN),
		'priority' => 41
	));

	$wp_customize->add_setting('aduni_custom_settings[whatsapp_contact]', array(
		'default' => IMAGES . '/whatsapp_hover.png',
		'type' => 'option'
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'whatsapp_contact', array(
		'label' => __('Imágen Whatsapp en Sección de Contacto', THEMEDOMAIN),
		'section' => 'aduni_whatsapp_phone',
		'settings' => 'aduni_custom_settings[whatsapp_contact]'
	)));

	/*
	$wp_customize->add_setting('aduni_custom_settings[logo_scroll]', array(
		'default' => IMAGES . '/logo-scroll.png',
		'type' => 'option'
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'logo_scroll', array(
		'label' => __('Sube tu logo a utilizar cuando se haga scroll en la página', THEMEDOMAIN),
		'section' => 'aduni_logo',
		'settings' => 'aduni_custom_settings[logo_scroll]'
	)));

	$wp_customize->add_setting('aduni_custom_settings[logo_footer]', array(
		'default' => IMAGES . '/logo-footer.png',
		'type' => 'option'
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'logo_footer', array(
		'label' => __('Sube tu logo a utilizar en el footer', THEMEDOMAIN),
		'section' => 'aduni_logo',
		'settings' => 'aduni_custom_settings[logo_footer]'
	)));

	$wp_customize->add_setting('aduni_custom_settings[logo_movil]', array(
		'default' => IMAGES . '/logo-movil.png',
		'type' => 'option'
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'logo_movil', array(
		'label' => __('Sube tu logo a utilizar en el Menú para móviles', THEMEDOMAIN),
		'section' => 'aduni_logo',
		'settings' => 'aduni_custom_settings[logo_movil]'
	)));

	$wp_customize->add_setting('aduni_custom_settings[logo_movil_header]', array(
		'default' => IMAGES . '/logo_movil_header.png',
		'type' => 'option'
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'logo_movil_header', array(
		'label' => __('Sube tu logo a utilizar en la cabecera para móviles', THEMEDOMAIN),
		'section' => 'aduni_logo',
		'settings' => 'aduni_custom_settings[logo_movil_header]'
	))); */

	/*
	// Top Ad
	$wp_customize->add_section('adaptive_ad', array(
		'title' => __('Top Ad', 'adaptive-framework'),
		'description' => __('Allows you to upload an ad banner to display on the top of the page.', 'adaptive-framework'),
		'priority' => 36
	));

	$wp_customize->add_setting('adaptive_custom_settings[display_top_ad]', array(
		'default' => 0,
		'type' => 'option'
	));

	$wp_customize->add_control('adaptive_custom_settings[display_top_ad]', array(
		'label' => __('Display the Top Ad?', 'adaptive-framework'),
		'section' => 'adaptive_ad',
		'settings' => 'adaptive_custom_settings[display_top_ad]',
		'type' => 'checkbox'
	));

	$wp_customize->add_setting('adaptive_custom_settings[top_ad]', array(
		'default' => IMAGES . '/demo/ad-468x60.gif',
		'type' => 'option'
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'top_ad', array(
		'label' => __('Upload the Top Banner Image', 'adaptive-framework'),
		'section' => 'adaptive_ad',
		'settings' => 'adaptive_custom_settings[top_ad]'
	)));

	$wp_customize->add_setting('adaptive_custom_settings[top_ad_link]', array(
		'default' => 'http://webdesign.tutsplus.com',
		'type' => 'option'
	));

	$wp_customize->add_control('adaptive_custom_settings[top_ad_link]', array(
		'label' => __('Link to the Target Website', 'adaptive-framework'),
		'section' => 'adaptive_ad',
		'settings' => 'adaptive_custom_settings[top_ad_link]',
		'type' => 'text'
	));

	// Contact Email
	$wp_customize->add_section('adaptive_contact_email', array(
		'title' => __('Contact Form Email', 'adaptive-framework'),
		'description' => __('Set the receiver email for the contact form.', 'adaptive-framework'),
		'priority' => 37
	));

	$wp_customize->add_setting('adaptive_custom_settings[contact_email]', array(
		'default' => '',
		'type' => 'option'
	));

	$wp_customize->add_control('adaptive_custom_settings[contact_email]', array(
		'label' => __('Contact Form Email Address', 'adaptive-framework'),
		'section' => 'adaptive_contact_email',
		'settings' => 'adaptive_custom_settings[contact_email]',
		'type' => 'text'
	));
	*/
}
