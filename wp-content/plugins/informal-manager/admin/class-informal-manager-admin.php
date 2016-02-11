<?php

// require_once plugin_dir_path(__FILE__).'../../../../vendor/autoload.php';

/**
 * The Informal Manager Admin defines all functionality for the dashboard
 * of the plugin.
 */

/**
 * The Informal Manager Admin defines all functionality for the dashboard
 * of the plugin.
 *
 * This class defines the meta box used to display the post meta data and registers
 * the style sheet responsible for styling the content of the meta box.
 *
 * @since    1.0.0
 */
class Informal_Manager_Admin
{
    /**
     * A reference to the version of the plugin that is passed to this class from the caller.
     *
     * @var string The current version of the plugin.
     */
    private $version;

    /**
     * Labels indicate allowed in custom fields.
     *
     * @var array $allowed
     */
    private $allowed;

    private $domain;

    /**
     * Initializes this class and stores the current version of this plugin.
     *
     * @param string $version The current version of this plugin.
     */
    public function __construct($version)
    {
        $this->version = $version;
        $this->allowed = array(
            'p' => array(
                'style' => array(),
            ),
            'a' => array( // on allow a tags
                'href' => array(),
                'target' => array(),
            ),
            'ul' => array(
                'class' => array(),
            ),
            'ol' => array(),
            'li' => array(
                'style' => array(),
            ),
            'strong' => array(),
            'br' => array(),
            'span' => array(),
        );
        $this->domain = 'informal-framework';
    }

    /**
     * Enqueues the style sheet responsible for styling the contents of this
     * meta box.
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            'informal-manager-admin',
            plugin_dir_url(__FILE__).'css/informal-manager-admin.css',
            array(),
            $this->version,
            false
        );
    }

    /**
     * Enqueues the scripts responsible for functionality.
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            'informal-manager-admin',
            plugin_dir_url(__FILE__).'js/informal-manager-admin.js',
            array('jquery'),
            $this->version,
            true
        );
    }

    /**
     * Display custom field by categories
     * @param  obj $tag Object of category
     */
    public function extra_category_fields($tag)
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/informal-mb-categories-manager.php';
    }

    /**
     * Save extra fields categories
     * @param  int $term_id Id of category
     */
    public function save_extra_category_fields($term_id)
    {
        // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'cat_meta_box_nonce' ) ) return;

        // if our current user can't edit this post, bail
        if( !current_user_can( 'manage_categories', $term_id ) ) return;

        $catMeta = get_option( "category_$term_id");

        if (isset($_POST['mb_colour']) && !empty($_POST['mb_colour'])) {
            $catMeta['mb_colour'] = esc_attr($_POST['mb_colour']);

            update_option( "category_$term_id", $catMeta );
        }
    }

    /**
     * Registers the meta box that will be used to display all of the post meta data
     * associated with the current post.
     */
    public function cd_mb_post_add()
    {
        add_meta_box(
            'mb-post-id',
            'Campos Extras',
            array( $this, 'render_mb_post' ),
            'post',
            'normal',
            'core'
        );
    }

    public function cd_mb_post_save($post_id)
    {
        // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'post_meta_box_nonce' ) ) return;

        // if our current user can't edit this post, bail
        if( !current_user_can( 'edit_post', $post_id ) ) return;

        // Save meta custom mb_subfeatured
        $subfeatured = isset( $_POST['mb_subfeatured'] ) && $_POST['mb_subfeatured'] ? 'on' : 'off';
        update_post_meta( $post_id, 'mb_subfeatured', $subfeatured );
    }

    /**
     * Requires the file that is used to display the user interface of the post meta box.
     */
    public function render_mb_post()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/informal-mb-post-manager.php';
    }

    // Custom columns of POSTS
    public function custom_columns_post($columns)
    {
        $new_columns = array(
            'subfeatured' => __('Sub-destacado', $this->domain)
        );
        return array_merge($columns, $new_columns);
    }

    // Display custom field of post in admin
    public function custom_column_post($column)
    {
        global $post;
        $values = get_post_custom($post->ID);

        switch ($column) {
            case 'subfeatured':
                $subfeatured = (isset($values['mb_subfeatured']) && esc_attr($values['mb_subfeatured'][0]) === 'on' ) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no"></span>';
                echo $subfeatured;
                break;
        }
    }

    /**
     * Add custom content type.
     */
    public function add_post_type()
    {
        $labels = array(
            'name' => __('Postulantes', $this->domain),
            'singular_name' => __('Postulante', $this->domain),
            'add_new' => __('Nuevo postulante', $this->domain),
            'add_new_item' => __('Agregar nuevo postulante', $this->domain),
            'edit_item' => __('Editar postulante', $this->domain),
            'new_item' => __('Nuevo postulante', $this->domain),
            'view_item' => __('Ver postulante', $this->domain),
            'search_items' => __('Buscar postulante', $this->domain),
            'not_found' => __('Postulante no encontrado', $this->domain),
            'not_found_in_trash' => __('Postulante no encontrado en la papelera', $this->domain),
            'all_items' => __('Todos los postulantes', $this->domain),
            /*'archives' - String for use with archives in nav menus. Default is Post Archives/Page Archives.
            'insert_into_item' - String for the media frame button. Default is Insert into post/Insert into page.
            'uploaded_to_this_item' - String for the media frame filter. Default is Uploaded to this post/Uploaded to this page.
            'featured_image' - Default is Featured Image.
            'set_featured_image' - Default is Set featured image.
            'remove_featured_image' - Default is Remove featured image.
            'use_featured_image' - Default is Use as featured image.
            'menu_name' - Default is the same as `name`.
            'filter_items_list' - String for the table views hidden heading.
            'items_list_navigation' - String for the table pagination hidden heading.
            'items_list' - String for the table hidden heading.*/
        );

        $args = array(
            'labels' => $labels,
            'description' => 'Relación de Postulantes pre inscritos',
            // 'public'              => true,
            // 'exclude_from_search' => true,
            // 'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            // 'menu_position'          => null,
            'menu_icon' => 'dashicons-welcome-learn-more',
            // 'hierarchical'        => false,
            'supports' => array(
                'title',
                'editor',
                'custom-fields',
                // 'author'
                // 'thumbnail'
                // 'excerpt'
                // 'trackbacks'
                // 'comments',
                // 'revisions',
                // 'page-attributes',
                // 'post-formats'
            ),
            // 'taxonomies'  => array('post_tag', 'category'),
            // 'has_archive' => false,
            // 'rewrite'     => true
        );

        register_post_type('postulant', $args);

        $labels = array(
            'name' => __('Distritos', $this->domain),
            'singular_name' => __('Distrito', $this->domain),
            'add_new' => __('Nuevo distrito', $this->domain),
            'add_new_item' => __('Agregar nuevo distrito', $this->domain),
            'edit_item' => __('Editar distrito', $this->domain),
            'new_item' => __('Nuevo distrito', $this->domain),
            'view_item' => __('Ver distrito', $this->domain),
            'search_items' => __('Buscar distrito', $this->domain),
            'not_found' => __('Distrito no encontrado', $this->domain),
            'not_found_in_trash' => __('Distrito no encontrado en la papelera', $this->domain),
            'all_items' => __('Todos los distritos', $this->domain),
        );

        $args = array(
            'labels' => $labels,
            'description' => 'Lista de Distritos',
            // 'public'              => true,
            // 'exclude_from_search' => true,
            // 'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            // 'menu_position'          => null,
            'menu_icon' => 'dashicons-admin-site',
            // 'hierarchical'        => false,
            'supports' => array(
                'title',
                'editor',
                'page-attributes',
                // 'custom-fields',
                // 'author'
                // 'thumbnail'
                // 'excerpt'
                // 'trackbacks'
                // 'comments',
                // 'revisions',
                // 'post-formats'
            ),
            // 'taxonomies'  => array('post_tag', 'category'),
            // 'has_archive' => false,
            // 'rewrite'     => true
        );

        register_post_type('district', $args);

        $labels = array(
            'name' => __('Programas', $this->domain),
            'singular_name' => __('Programa', $this->domain),
            'add_new' => __('Nuevo programa', $this->domain),
            'add_new_item' => __('Agregar nuevo programa', $this->domain),
            'edit_item' => __('Editar programa', $this->domain),
            'new_item' => __('Nuevo programa', $this->domain),
            'view_item' => __('Ver programa', $this->domain),
            'search_items' => __('Buscar programa', $this->domain),
            'not_found' => __('Programa no encontrado', $this->domain),
            'not_found_in_trash' => __('Programa no encontrado en la papelera', $this->domain),
            'all_items' => __('Todos los programas', $this->domain),
        );

        $args = array(
            'labels' => $labels,
            'description' => 'Programa / Curso / Taller',
            // 'public'              => true,
            // 'exclude_from_search' => true,
            // 'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            // 'menu_position'          => null,
            'menu_icon' => 'dashicons-list-view',
            // 'hierarchical'        => false,
            'supports' => array(
                'title',
                'editor',
                'page-attributes',
                // 'custom-fields',
                // 'author'
                // 'thumbnail'
                // 'excerpt'
                // 'trackbacks'
                // 'comments',
                // 'revisions',
                // 'post-formats'
            ),
            // 'taxonomies'  => array('post_tag', 'category'),
            // 'has_archive' => false,
            // 'rewrite'     => true
        );

        register_post_type('programs', $args);
    }
}