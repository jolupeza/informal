<?php
/****************************************************/
/* Define Constants */
/****************************************************/
define('THEMEROOT', get_stylesheet_directory_uri());
define('IMAGES', THEMEROOT . '/images');
define('THEMEDOMAIN', 'informal-framework');

/***********************************************************/
/* Load JS Files */
/***********************************************************/
function load_custom_scripts() {
	wp_enqueue_script('custom_script', THEMEROOT . '/js/main.min.js', array('jquery'), false, true);
    wp_localize_script('custom_script', 'InformalAjax', array('url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('informaljax-nonce')));
}

add_action('wp_enqueue_scripts', 'load_custom_scripts');

/********************************************************************************/
/* Add Theme Support for Post Formats, Post Thumbnails and Automatic Feed Links */
/********************************************************************************/
if(function_exists('add_theme_support')) {
	add_theme_support('post-formats', array('link', 'quote', 'gallery', 'video'));
	add_theme_support('post-thumbnails', array('post', 'page'));

	add_theme_support('automatic-feed-links');
}

/***********************************************************/
/* Add Menus */
/***********************************************************/
function register_my_menus()
{
    register_nav_menus(
        array(
            'main-menu' => __('Main Menu', THEMEDOMAIN),
            'footer-menu' => __('Footer Menu', THEMEDOMAIN),
        )
    );
}
add_action('init', 'register_my_menus');

/****************************************************/
/* Load Theme Options Page and Custom Widgets */
/****************************************************/
require_once('functions/informal-theme-customizer.php');

/*
 * Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
 * @author Joost van Veen
 * @version 1.0
 */
if (!function_exists('dump')) {
    function dump($var, $label = 'Dump', $echo = true)
    {
        // Store dump in variable
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", '] => ', $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">'.$label.' => '.$output.'</pre>';

        // Output
        if ($echo == true) {
            echo $output;
        } else {
            return $output;
        }
    }
}

if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = true)
    {
        dump($var, $label, $echo);
        exit;
    }
}


