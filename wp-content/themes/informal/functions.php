<?php
/****************************************************/
/* Define Constants */
/****************************************************/
define('THEMEROOT', get_stylesheet_directory_uri());
define('IMAGES', THEMEROOT . '/images');
define('THEMEDOMAIN', 'informal-framework');

remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

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
	add_theme_support('post-formats', array('link', 'quote', 'gallery', 'video', 'chat'));
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

/***********************************************************/
/* Menu Walker Main Menu */
/***********************************************************/
class Informal_menu_main_walker extends Walker_Nav_Menu
{
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
    {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"';

        $output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $class_names .'>';
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->title ) . ' ' . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';

        $content    = apply_filters( 'the_title', $item->title, $item->ID );
        $title      = apply_filters( 'the_title', $item->attr_title, $item->ID );

        $attributes .= ' href="' . $item->url . '"';

        $item_output = $args->before;

        $item_output .= '<a ' . $attributes . ' >';
        $item_output .= $args->link_before . $content . $args->link_after;
        $item_output .= '</a>';

        if('category' == $item->object) {
            $item_output .= '<span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>';
        }

        if ('category' == $item->object) {
            $idCategory = $item->object_id;

            $child_cats = wp_list_categories('title_li=&echo=0&hide_empty=0&child_of='.$item->object_id);
            if( $child_cats ){
                $item_output .= '<div class="MainMenu-subMenu">';
                $item_output .= '<ul class="sub-menu">' .$child_cats. '</ul>';
                $item_output .= '<div class="MainMenu-lastPost text-center">';
                $item_output .= '<div class="row">';
                $item_output .= getLastPostCategory($idCategory);
                $item_output .= '</div>';
                $item_output .= '</div>';
                $item_output .= '</div>';
            }
        }

        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

function getLastPostCategory($id) {
    $content = '';

    $args = array(
        'cat'            => $id,
        'posts_per_page' => 4
    );
    $the_query = new WP_Query($args);
    if($the_query->have_posts()) {
        ob_start();

        include TEMPLATEPATH . '/includes/lastPosts.php';

        $content = ob_get_contents();

        ob_get_clean();
    }

    wp_reset_postdata();
    return $content;
}

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
            'name' => __('Home Page Calendar', THEMEDOMAIN),
            'id' => 'calendar-home',
            'description' => __('Sidebar calendar homepage', THEMEDOMAIN),
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
    register_sidebar(
        array(
            'name' => __('Author Sidebar', THEMEDOMAIN),
            'id' => 'author',
            'description' => __('Sidebar a mostrar en la página de autores.', THEMEDOMAIN),
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
    $tag      = $_POST['tag'];
    $search   = $_POST['search'];
    $parent   = (int)$_POST['parent'];
    $paged++;

    if ($paged > 0) {
        if(!$author && !$category && !$tag && !$search) {
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
        } elseif($author && !$category && !$tag && !$search) {
            $args = array(
                'author' => $author,
                'paged'  => $paged,
            );
        } elseif($category && !$author && !$tag && !$search) {
            $args = array(
                'cat'          => $category,
                'paged'        => $paged,
                /*'meta_query' => array(
                    array(
                        'key'   => 'mb_featured',
                        'value' => 'off'
                    )
                ),*/
            );
            if ($parent === 0) {
                $args['meta_query']  = array(
                    array(
                        'key'   => 'mb_featured',
                        'value' => 'off'
                    )
                );
            }
        } elseif($search && !$author && !$tag && !$category) {
            $search = sanitize_text_field($search);
            $args = array(
                's'         => $search,
                'post_type' => 'post',
                'paged'     => $paged,
            );
        } elseif($tag && !$author && !$category && !$search) {
            $tag = sanitize_text_field($tag);
            $args = array(
                'tag'   => $tag,
                'paged' => $paged
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

/***********************************************************/
/* Get posts via ajax */
/***********************************************************/
add_action('wp_ajax_get_events', 'get_events_callback');
add_action('wp_ajax_nopriv_get_events', 'get_events_callback');

function get_events_callback()
{
    $nonce = $_POST['nonce'];
    $result = array('result' => false);

    if (!wp_verify_nonce($nonce, 'informalajax-nonce')) {
        die('¡Acceso denegado!');
    }

    $date = getdate();
    $dates = array();

    $date = $_POST['date'];
    $category = (int)$_POST['category'];
    $filter = (int)$_POST['filter'];

    $args = array(
        'cat'            => $category,
        'post_type'      => 'post',
        'posts_per_page' => -1,
    );
    if($filter === 1 || $filter === 2) {
        $meta = ($filter === 1) ? 'mb_ev_featured' : 'mb_ev_weekend';
        $args['meta_query'] = array(
            array(
                'key'     => 'mb_date',
                'value'   => array(date('Y-m-01', strtotime($date)), date('Y-m-t', strtotime($date))),
                'type'    => 'DATE',
                'compare' => 'BETWEEN'
            ),
            array(
                'key'     => $meta,
                'value'   => 'on',
            )
        );
    } else {
        $args['meta_query'] = array(
            array(
                'key'     => 'mb_date',
                'value'   => array(date('Y-m-01', strtotime($date)), date('Y-m-t', strtotime($date))),
                'type'    => 'DATE',
                'compare' => 'BETWEEN'
            )
        );
    }

    $the_query = new WP_Query($args);

    if($the_query->have_posts()) {
        while($the_query->have_posts()) {
            $the_query->the_post();
            $id = get_the_id();
            $values = get_post_custom($id);
            $dateEvent = (isset($values['mb_date'])) ? esc_attr($values['mb_date'][0]) : '';

            $dates[] = $dateEvent;
        }
    }

    if(count($dates)) {
        $result['result'] = true;
        $result['dates'] = $dates;
    }

    wp_reset_postdata();
    echo json_encode($result);
    die();
}

/***********************************************************/
/* Get events via ajax */
/***********************************************************/
add_action('wp_ajax_update_events', 'update_events_callback');
add_action('wp_ajax_nopriv_update_events', 'update_events_callback');

function update_events_callback()
{
    $nonce = $_POST['nonce'];
    $result = array('result' => false);

    if (!wp_verify_nonce($nonce, 'informalajax-nonce')) {
        die('¡Acceso denegado!');
    }

    $date     = $_POST['date'];
    $category = $_POST['category'];

    $args = array(
        'cat'            => $category,
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'meta_key'       => 'mb_date',
        'meta_type'      => 'DATE',
        'meta_query'     => array(
            array(
                'key'     => 'mb_date',
                'value'   => array(date('Y-m-01', strtotime($date)), date('Y-m-t', strtotime($date))),
                'type'    => 'DATE',
                'compare' => 'BETWEEN'
            )
        ),
        'order'          => 'DESC',
        'orderby'        => 'meta_value_date',
    );
    $the_query = new WP_Query($args);

    if($the_query->have_posts()) {
        ob_start();

        include TEMPLATEPATH . '/includes/events.php';

        $content = ob_get_contents();

        ob_get_clean();

        $result['result'] = true;
        $result['content'] = $content;
    }

    wp_reset_postdata();
    echo json_encode($result);
    die();
}

/***********************************************************/
/* Get events via ajax */
/***********************************************************/
add_action('wp_ajax_filter_events', 'filter_events_callback');
add_action('wp_ajax_nopriv_filter_events', 'filter_events_callback');

function filter_events_callback()
{
    $nonce = $_POST['nonce'];
    $result = array('result' => false);

    if (!wp_verify_nonce($nonce, 'informalajax-nonce')) {
        die('¡Acceso denegado!');
    }

    $filter = (int)$_POST['filter'];
    $category = $_POST['category'];
    $date     = $_POST['date'];

    if($filter === 1 || $filter === 2) {
        $meta = ($filter === 1) ? 'mb_ev_featured' : 'mb_ev_weekend';

        $args = array(
            'cat'            => $category,
            'post_type'      => 'post',
            'posts_per_page' => -1,
            'meta_key'       => 'mb_date',
            'meta_type'      => 'DATE',
            'meta_query'     => array(
                array(
                    'key'     => 'mb_date',
                    'value'   => array(date('Y-m-01', strtotime($date)), date('Y-m-t', strtotime($date))),
                    'type'    => 'DATE',
                    'compare' => 'BETWEEN'
                ),
                array(
                    'key'     => $meta,
                    'value'   => 'on',
                )
            ),
            'order'          => 'DESC',
            'orderby'        => 'meta_value_date',
        );
        $the_query = new WP_Query($args);

        if($the_query->have_posts()) {
            ob_start();

            include TEMPLATEPATH . '/includes/events.php';

            $content = ob_get_contents();

            ob_get_clean();

            $result['result'] = true;
            $result['content'] = $content;
        }

        wp_reset_postdata();
    }

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
require_once('functions/widget-events-calendar.php');
require_once('functions/widget-authors-related.php');

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


// Desactivar los errores de la página de Login
function login_errors_message() {
  return 'Ooooops!';
}
add_filter('login_errors', 'login_errors_message');

// Eliminar basura de la etiqueta <head>
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

// Desactivar el código HTML en los comentarios
add_filter('pre_comment_content', 'wp_specialchars');

/**********************************************************************************/
/* Change Logo and footer Area Administration */
/**********************************************************************************/
// Cambiamos el logo para el formulario de acceso al wordpress
function my_custom_login_logo() {
    echo '<style type="text/css">h1 a {background-image:url('.get_bloginfo('template_directory').'/images/logo.png) !important; background-size: contain !important; width: 100% !important; padding: 15px 0 !important; background-position: center center !important;}</style>';
}
add_action('login_head', 'my_custom_login_logo');


// Cambiar el pie de pagina del panel de Administración
function change_footer_admin() {
    echo 'Copyright © ' . date('Y') . ' Agencia Watson';
}
add_filter('admin_footer_text', 'change_footer_admin');
