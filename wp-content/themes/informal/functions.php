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
            'name' => __('Home Page Center Sidebar', THEMEDOMAIN),
            'id' => 'center-home',
            'description' => __('Sidebar central homepage', THEMEDOMAIN),
            'before_widget' => '<div class="SidebarWidget">',
            'after_widget' => '</div> <!-- end SidebarWidget -->',
            'before_title' => '<h4>',
            'after_title' => '</h4>'
        )
    );
    register_sidebar(
        array(
            'name' => __('Home Page Right Sidebar', THEMEDOMAIN),
            'id' => 'right-home',
            'description' => __('Sidebar derecho homepage', THEMEDOMAIN),
            'before_widget' => '<div class="SidebarWidget">',
            'after_widget' => '</div> <!-- end SidebarWidget -->',
            'before_title' => '<h4>',
            'after_title' => '</h4>'
        )
    );
    register_sidebar(
        array(
            'name' => __('Publicidad Superior', THEMEDOMAIN),
            'id' => 'adv-top',
            'description' => __('Sidebar Publicidad Superior', THEMEDOMAIN),
            'before_widget' => '<aside class="Advertising text-right">',
            'after_widget' => '</aside>',
            'before_title' => '<h5>',
            'after_title' => '</h5>'
        )
    );
    register_sidebar(
        array(
            'name' => __('Publicidad Inferior', THEMEDOMAIN),
            'id' => 'adv-bottom',
            'description' => __('Sidebar Publicidad Inferior', THEMEDOMAIN),
            'before_widget' => '<aside class="Advertising">',
            'after_widget' => '</aside>',
            'before_title' => '<h5>',
            'after_title' => '</h5>'
        )
    );
    register_sidebar(
        array(
            'name' => __('Single Sidebar', THEMEDOMAIN),
            'id' => 'single',
            'description' => __('Sidebar a mostrar en el detalle del post.', THEMEDOMAIN),
            'before_widget' => '<div class="SidebarWidget">',
            'after_widget' => '</div> <!-- end SidebarWidget -->',
            'before_title' => '<h4>',
            'after_title' => '</h4>'
        )
    );
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

/***********************************************************/
/* Get posts via ajax */
/***********************************************************/
add_action('wp_ajax_get_posts', 'get_posts_callback');
add_action('wp_ajax_nopriv_get_posts', 'get_posts_callback');

function get_posts_callback()
{
    $nonce = $_POST['nonce'];
    $result = array('result' => false, 'paged' => true);

    if (!wp_verify_nonce($nonce, 'informalajax-nonce')) {
        die('¡Acceso denegado!');
    }

    $paged    = (int)$_POST['paged'];
    $author   = $_POST['author'];
    $category = $_POST['category'];
    $paged++;

    if ($paged > 0) {
        if(!$author && !$category) {
            $args =  array(
                'post__not_in'   => get_option('sticky_posts'),
                'meta_query' => array(
                    array(
                        'key'   => 'mb_subfeatured',
                        'value' => 'off'
                    )
                ),
                'paged' => $paged,
            );
        } elseif($author && !$category) {
            $args = array(
                'author' => $author,
                'paged'  => $paged,
            );
        } elseif($category && !$author) {
            $args = array(
                'cat'          => $category,
                'paged'        => $paged,
                'meta_query' => array(
                    array(
                        'key'   => 'mb_featured',
                        'value' => 'off'
                    )
                ),
            );
        }

        $the_query = new WP_Query($args);

        if ($the_query->have_posts()) {

            ob_start();

            include TEMPLATEPATH . '/includes/posts-ajax.php';

            $content = ob_get_contents();

            ob_get_clean();

            $result['result'] = true;
            $result['content'] = $content;
        }

        $result['paged'] = ($the_query->max_num_pages == $paged) ? false : $result['paged'];
    }

    wp_reset_postdata();
    echo json_encode($result);
    die();
}

/***********************************************************************************************/
/* Custom Function for Displaying Comments */
/***********************************************************************************************/
function informal_comments($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;

    if (get_comment_type() == 'pingback' || get_comment_type() == 'trackback') : ?>

        <li class="pingback" id="comment-<?php comment_ID(); ?>">

            <article <?php comment_class('clearfix'); ?>>

                <header>

                    <h4><?php _e('Pingback:', THEMEDOMAIN); ?></h4>
                    <p><?php edit_comment_link(); ?></p>

                </header>

                <?php comment_author_link(); ?>

            </article>

    <?php endif; ?>

    <?php if (get_comment_type() == 'comment') : ?>
        <li id="comment-<?php comment_ID(); ?>">
            <article <?php comment_class('clearfix'); ?>>
                <figure class="comment-avatar">
                    <?php
                        $avatar_size = 80;
                        if ($comment->comment_parent != 0) {
                            $avatar_size = 48;
                        }

                        echo get_avatar($comment, $avatar_size);
                    ?>
                </figure>

                <div class="comment-wrapper">
                    <div class="comment-info">
                        <h4><span><?php _e('AUTOR', THEMEDOMAIN); ?></span> <?php comment_author_link(); ?> <?php //comment_date(); ?> <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?></h4>
                        <!-- <p></p> -->
                        <p><span>Comentario</span></p>
                    </div><!-- end comment-info -->

                    <div class="comment-text">

                        <?php if ($comment->comment_approved == '0') : ?>

                            <p class="awaiting-moderation text-center"><?php _e('Tu comentario está esperando ser moderado.', THEMEDOMAIN); ?></p>

                        <?php endif; ?>

                        <?php comment_text(); ?>

                    </div><!-- end comment-text -->
                </div><!-- end comment-wrapper -->
            </article>
    <?php endif;
}

/***********************************************************************************************/
/* Custom Comment Form */
/***********************************************************************************************/
function informal_custom_comment_form($defaults)
{
    //var_dump($defaults);
    $comment_notes_after = '' .
        '<div class="allowed-tags">' .
        '<p><strong>' . __('Etiquetas permitidas', THEMEDOMAIN) . '</strong></p>' .
        '<code> ' . allowed_tags() . ' </code>' .
        '</div> <!-- end allowed-tags -->';

    $defaults['comment_notes_before'] = '';
    //$defaults['comment_notes_after'] = $comment_notes_after;
    $defaults['comment_notes_after'] = '';
    $defaults['title_reply'] = __('Comentarios', THEMEDOMAIN);
    $defaults['label_submit'] = __('enviar', THEMEDOMAIN);
    $defaults['id_form'] = 'comment-form';
    $defaults['comment_field'] = '<p><textarea name="comment" id="comment" rows="3" placeholder="' . __('Ingresa aquí tu comentario', THEMEDOMAIN) . '"></textarea></p>';

    return $defaults;
}

add_filter('comment_form_defaults', 'informal_custom_comment_form');

function informal_custom_comment_fields()
{
    $commenter = wp_get_current_commenter();
    $req       = get_option('require_name_email');
    $aria_req  = ($req ? " aria-required='true'" : '');

    $fields = array(
        'author' => '<p>' .
                        '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" placeholder="' . __('Nombre o seudónimo', THEMEDOMAIN) . '' . ($req ? __(' (requerido) ', THEMEDOMAIN) : '') . '" ' . $aria_req . ' />' .
                        '<label for="author">' . __('Name', THEMEDOMAIN) . '' . ($req ? __(' (required)', THEMEDOMAIN) : '') . '</label>' .
                    '</p>',
        'email' => '<p>' .
                        '<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" placeholder="' . __('Email', THEMEDOMAIN) . '' . ($req ? __(' (requerido)', THEMEDOMAIN) : '') . '" ' . $aria_req . ' />' .
                        '<label for="email">' . __('Email', THEMEDOMAIN) . '' . ($req ? __(' (required) (will not be published)', THEMEDOMAIN) : '') . '</label>' .
                   '</p>',
        //'url' => '<p>' .
        //              '<input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" />' .
        //              '<label for="url">' . __('Website', THEMEDOMAIN) . '</label>' .
        //            '</p>'
    );

    return $fields;
}

add_filter('comment_form_default_fields', 'informal_custom_comment_fields');

/****************************************************/
/* Load Theme Options Page and Custom Widgets */
/****************************************************/
require_once('functions/informal-theme-customizer.php');
require_once('functions/widget-fb-page.php');
require_once('functions/widget-publicidadgoogle-300.php');
require_once('functions/widget-banner-300.php');
require_once('functions/widget-posts-popular.php');

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


