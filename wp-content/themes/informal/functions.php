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
    wp_localize_script('custom_script', 'InformalAjax', array('url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('informalajax-nonce')));
}

add_action('wp_enqueue_scripts', 'load_custom_scripts');

/********************************************************************************/
/* Add Theme Support for Post Formats, Post Thumbnails and Automatic Feed Links */
/********************************************************************************/
if(function_exists('add_theme_support')) {
	add_theme_support('post-formats', array('link', 'quote', 'gallery', 'video'));
	add_theme_support('post-thumbnails', array('post', 'page', 'banners'));

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

/***********************************************************************************************/
/* Add Sidebar Support */
/***********************************************************************************************/
if (function_exists('register_sidebar')) {
    register_sidebar(
        array(
            'name' => __('Main Sidebar', THEMEDOMAIN),
            'id' => 'main-sidebar',
            'description' => __('Sidebar principal homepage', THEMEDOMAIN),
            'before_widget' => '<div class="SidebarWidget">',
            'after_widget' => '</div> <!-- end SidebarWidget -->',
            'before_title' => '<h4>',
            'after_title' => '</h4>'
        )
    );
    register_sidebar(
        array(
            'name' => __('Home Page Sidebar', THEMEDOMAIN),
            'id' => 'center-home',
            'description' => __('Sidebar central homepage', THEMEDOMAIN),
            'before_widget' => '<div class="SidebarWidget">',
            'after_widget' => '</div> <!-- end SidebarWidget -->',
            'before_title' => '<h4>',
            'after_title' => '</h4>'
        )
    );
    /*register_sidebar(
        array(
            'name' => __('Right Footer', 'adaptive-framework'),
            'id' => 'right-footer',
            'description' => __('The right footer area', 'adaptive-framework'),
            'before_widget' => '<div class="footer-widget span6">',
            'after_widget' => '</div> <!-- end footer-widget -->',
            'before_title' => '<h5>',
            'after_title' => '</h5>'
        )
    );*/
}

// Check is registered email
add_action('wp_ajax_nopriv_check_email', 'check_email_callback');
add_action('wp_ajax_nopriv_check_email', 'check_email_callback');

function check_email_callback()
{
    $nonce = $_POST['nonce'];
    $isAvailable = array('valid' => false);

    if(!wp_verify_nonce( $nonce, 'informalajax-nonce')) {
        die('¡Acceso denegado!');
    }

    $email = trim($_POST['mb_email']);

    if (!empty($email)) {
        $email = sanitize_text_field($email);

        $args = array(
            'post_type'  => 'subscribers',
            'meta_query' => array(
                array(
                    'key'   => 'mb_email',
                    'value' => $email,
                )
            )
        );
        $the_query = new WP_Query($args);

        if(!$the_query->have_posts()) {
            $isAvailable['valid'] = true;
        }
        wp_reset_postdata();
    }

    echo json_encode($isAvailable);
    die();
}

/***********************************************************/
/* Register subscriber via ajax */
/***********************************************************/
add_action('wp_ajax_register_subscriber', 'register_subscriber_callback');
add_action('wp_ajax_nopriv_register_subscriber', 'register_subscriber_callback');

function register_subscriber_callback()
{
    $nonce = $_POST['nonce'];
    $result = array('result' => false);

    if (!wp_verify_nonce($nonce, 'informalajax-nonce')) {
        die('¡Acceso denegado!');
    }

    $email = trim($_POST['email']);

    if (!empty($email) && is_email($email)) {
        $email = sanitize_email($email);

        // Verify register previous of email
        $args = array(
            'post_type' => 'subscribers',
            'meta_query' => array(
                array(
                    'key' => 'mb_email',
                    'value' => $email
                )
            ),
        );
        $the_query = new WP_Query($args);
        if (!$the_query->have_posts()) {
            $post_id = wp_insert_post(array(
                'post_author' => 1,
                'post_status' => 'publish',
                'post_type' => 'subscribers',
            ));
            update_post_meta( $post_id, 'mb_email', $email);
            $result['result'] = true;
        }

        wp_reset_postdata();
    }

    echo json_encode($result);
    die();
}

/****************************************************/
/* Load Theme Options Page and Custom Widgets */
/****************************************************/
require_once('functions/informal-theme-customizer.php');
require_once('functions/widget-fb-page.php');
require_once('functions/widget-publicidadgoogle-300.php');
require_once('functions/widget-banner-300.php');

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


