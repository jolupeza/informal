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

    // Display Filter SubFeature Posts
    public function post_table_filtering()
    {
        global $wpdb;
        global $typenow;

        if ($typenow == 'post') {
            echo '<select name="mb_post" id="filter-by-post">';
            echo '<option value="0">' . __( 'Mostrar todos los posts', THEMEDOMAIN ) . '</option>';

            $selected = ( !empty($_GET['mb_post']) && $_GET['mb_post'] == 1 ) ? 'selected="selected"' : '';
            echo '<option value="1" ' . $selected . '>' . __( 'Destacados', THEMEDOMAIN) . '</option>';

            $selected = ( !empty($_GET['mb_post']) && $_GET['mb_post'] == 2 ) ? 'selected="selected"' : '';
            echo '<option value="2" ' . $selected . '>' . __( 'Subdestacados', THEMEDOMAIN) . '</option>';
            echo '</select>';
        }
    }

    // Filter posts y subfeatured
    public function post_table_filter($query)
    {
        if( !current_user_can('manage_options')) return;

        if(is_admin() && $query->query['post_type'] == 'post') {
            $qv = &$query->query_vars;
            $qv['meta_query'] = array();

            if(!empty($_GET['mb_post'])) {
                if ($_GET['mb_post'] == 1) {
                    $sticky = get_option('sticky_posts');
                    $qv['post__in'] = $sticky;
                }

                if ($_GET['mb_post'] == 2) {
                    $qv['meta_query'][] = array(
                        'field' => 'mb_subfeatured',
                        'value' => 'on',
                        'compare' => '=',
                        'type' => 'CHAR'
                    );
                }
            }
        }
    }

    /**
     * Registers the meta box that will be used to display all of the post meta data
     * associated with the current post.
     */
    public function cd_mb_banners_add()
    {
        add_meta_box(
            'mb-banners-id',
            'Campos Extras',
            array( $this, 'render_mb_banners' ),
            'banners',
            'normal',
            'core'
        );
    }

    public function cd_mb_banners_save($post_id)
    {
        // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'banners_meta_box_nonce' ) ) return;

        // if our current user can't edit this post, bail
        if( !current_user_can( 'edit_post', $post_id ) ) return;

        // Save meta custom mb_link
        if( isset( $_POST['mb_link'] ) && !empty($_POST['mb_link']) ) {
            update_post_meta( $post_id, 'mb_link', esc_attr( $_POST['mb_link'] ) );
        } else {
            delete_post_meta( $post_id, 'mb_link' );
        }

        // Save meta custom mb_target
        $target = isset( $_POST['mb_target'] ) && $_POST['mb_target'] ? 'on' : 'off';
        update_post_meta( $post_id, 'mb_target', $target );

        // Save meta custom mb_image
        if( isset( $_POST['mb_image'] ) && !empty($_POST['mb_image']) ) {
            update_post_meta( $post_id, 'mb_image', esc_attr( $_POST['mb_image'] ) );
        } else {
            delete_post_meta( $post_id, 'mb_image' );
        }
    }

    /**
     * Requires the file that is used to display the user interface of the post meta box.
     */
    public function render_mb_banners()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/informal-mb-banners-manager.php';
    }

    /**
     * Registers the meta box that will be used to display all of the post meta data
     * associated with the current post.
     */
    public function cd_mb_subscribers_add()
    {
        add_meta_box(
            'mb-subscribers-id',
            'Campos Extras',
            array( $this, 'render_mb_subscribers' ),
            'subscribers',
            'normal',
            'core'
        );
    }

    public function cd_mb_subscribers_save($post_id)
    {
        // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'subscribers_meta_box_nonce' ) ) return;

        // if our current user can't edit this post, bail
        if( !current_user_can( 'edit_post', $post_id ) ) return;

        // Save meta custom mb_link
        if( isset( $_POST['mb_email'] ) && !empty($_POST['mb_email']) ) {
            update_post_meta( $post_id, 'mb_email', esc_attr( $_POST['mb_email'] ) );
        } else {
            delete_post_meta( $post_id, 'mb_email' );
        }
    }

    /**
     * Requires the file that is used to display the user interface of the post meta box.
     */
    public function render_mb_subscribers()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/informal-mb-subscribers-manager.php';
    }

    // Custom columns by post type subscribers
    public function custom_columns_subscribers($columns)
    {
        unset($columns['title']);
        $columns = array(
            'cb'    => '<input type="checkbox" />',
            'email' => __('Email', $this->domain),
            'date'  => __('Fecha', $this->domain)
        );

        return $columns;
    }

    // Display custom field of subscriber in admin
    public function custom_column_subscribers($column)
    {
        global $post;

        // Setup some vars
        $edit_link = get_edit_post_link($post->ID);
        $post_type_object = get_post_type_object($post->post_type);
        $can_edit_post = current_user_can('edit_post', $post->ID);
        $values = get_post_custom($post->ID);

        switch ( $column )
        {
            case 'email':
                $email = isset($values['mb_email']) ? esc_attr( $values['mb_email'][0] ): '';

                // Display the email
                if (!empty($email)) {
                    if($can_edit_post && $post->post_status != 'trash') {
                        echo '<a class="row-title" href="' . $edit_link . '" title="' . esc_attr(__('Editar este elemento')) . '">' . $email . '</a>';
                    } else {
                        echo $email;
                    }
                }

                // Add admin actions
                $actions = array();
                if ($can_edit_post && 'trash' != $post->post_status) {
                    $actions['edit'] = '<a href="' . get_edit_post_link($post->ID, true) . '" title="' . esc_attr(__( 'Editar este elemento')) . '">' . __('Editar') . '</a>';
                }

                if (current_user_can('delete_post', $post->ID)) {
                    if ('trash' == $post->post_status) {
                        $actions['untrash'] = "<a title='" . esc_attr(__('Restaurar este elemento desde la papelera')) . "' href='" . wp_nonce_url(admin_url(sprintf($post_type_object->_edit_link . '&amp;action=untrash', $post->ID)), 'untrash-post_' . $post->ID) . "'>" . __('Restaurar') . "</a>";
                    } elseif(EMPTY_TRASH_DAYS) {
                        $actions['trash'] = "<a class='submitdelete' title='" . esc_attr(__('Mover este elemento a la papelera')) . "' href='" . get_delete_post_link($post->ID) . "'>" . __('Papelera') . "</a>";
                    }

                    if ('trash' == $post->post_status || !EMPTY_TRASH_DAYS) {
                        $actions['delete'] = "<a class='submitdelete' title='" . esc_attr(__('Borrar este elemento permanentemente')) . "' href='" . get_delete_post_link($post->ID, '', true) . "'>" . __('Borrar permanentemente') . "</a>";
                    }
                }

                $html = '<div class="row-actions">';
                if (isset($actions['edit'])) {
                    $html .= '<span class="edit">' . $actions['edit'] . ' | </span>';
                }
                if (isset($actions['trash'])) {
                    $html .= '<span class="trash">' . $actions['trash'] . '</span>';
                }
                if (isset($actions['untrash'])) {
                    $html .= '<span class="untrash">' . $actions['untrash'] . ' | </span>';
                }
                if (isset($actions['delete'])) {
                    $html .= '<span class="delete">' . $actions['delete'] . '</span>';
                }
                $html .= '</div>';

                echo $html;
                break;
        }
    }

    /**
     * Modify user methods contact of user
     *
     * @param  arr $user_contact Array contain user method contact.
     * @return arr               Array with user method contact custom add.
     */
    public function modify_user_contact_methods( $user_contact )
    {
        // Add user contact methods
        $user_contact['facebook'] = __( 'Facebook Uername' , THEMEDOMAIN  );
        $user_contact['twitter']  = __( 'Twitter Username', THEMEDOMAIN );
        $user_contact['youtube']  = __( 'Youtube Username', THEMEDOMAIN );

        return $user_contact;
    }

    /**
     * Add custom content type.
     */
    public function add_post_type()
    {
        /*$labels = array(
            'name'               => __('Postulantes', $this->domain),
            'singular_name'      => __('Postulante', $this->domain),
            'add_new'            => __('Nuevo postulante', $this->domain),
            'add_new_item'       => __('Agregar nuevo postulante', $this->domain),
            'edit_item'          => __('Editar postulante', $this->domain),
            'new_item'           => __('Nuevo postulante', $this->domain),
            'view_item'          => __('Ver postulante', $this->domain),
            'search_items'       => __('Buscar postulante', $this->domain),
            'not_found'          => __('Postulante no encontrado', $this->domain),
            'not_found_in_trash' => __('Postulante no encontrado en la papelera', $this->domain),
            'all_items'          => __('Todos los postulantes', $this->domain),
            'archives' - String for use with archives in nav menus. Default is Post Archives/Page Archives.
            'insert_into_item' - String for the media frame button. Default is Insert into post/Insert into page.
            'uploaded_to_this_item' - String for the media frame filter. Default is Uploaded to this post/Uploaded to this page.
            'featured_image' - Default is Featured Image.
            'set_featured_image' - Default is Set featured image.
            'remove_featured_image' - Default is Remove featured image.
            'use_featured_image' - Default is Use as featured image.
            'menu_name' - Default is the same as `name`.
            'filter_items_list' - String for the table views hidden heading.
            'items_list_navigation' - String for the table pagination hidden heading.
            'items_list' - String for the table hidden heading.
        );
        $args = array(
            'labels' => $labels,
            'description' => 'Relación de Postulantes pre inscritos',
            // 'public'              => false,
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
        register_post_type('postulant', $args);*/

        $labels = array(
            'name'               => __('Banners', $this->domain),
            'singular_name'      => __('Banner', $this->domain),
            'add_new'            => __('Nuevo banner', $this->domain),
            'add_new_item'       => __('Agregar nuevo banner', $this->domain),
            'edit_item'          => __('Editar banner', $this->domain),
            'new_item'           => __('Nuevo banner', $this->domain),
            'view_item'          => __('Ver banner', $this->domain),
            'search_items'       => __('Buscar banner', $this->domain),
            'not_found'          => __('Banner no encontrado', $this->domain),
            'not_found_in_trash' => __('Banner no encontrado en la papelera', $this->domain),
            'all_items'          => __('Todos los banners', $this->domain)
        );

        $args = array(
            'labels' => $labels,
            'description' => 'Banners publicitarios',
            // 'public'              => false,
            // 'exclude_from_search' => true,
            // 'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            // 'menu_position'          => null,
            'menu_icon' => 'dashicons-media-interactive',
            // 'hierarchical'        => false,
            'supports' => array(
                'title',
                'editor',
                'custom-fields',
                'thumbnail'
                // 'author'
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

        register_post_type('banners', $args);

        $labels = array(
            'name'               => __('Suscriptores', $this->domain),
            'singular_name'      => __('Suscriptor', $this->domain),
            'add_new'            => __('Nuevo suscriptor', $this->domain),
            'add_new_item'       => __('Agregar nuevo suscriptor', $this->domain),
            'edit_item'          => __('Editar suscriptor', $this->domain),
            'new_item'           => __('Nuevo suscriptor', $this->domain),
            'view_item'          => __('Ver suscriptor', $this->domain),
            'search_items'       => __('Buscar suscriptor', $this->domain),
            'not_found'          => __('Suscriptor no encontrado', $this->domain),
            'not_found_in_trash' => __('Suscriptor no encontrado en la papelera', $this->domain),
            'all_items'          => __('Todos los suscriptores', $this->domain)
        );
        $args = array(
            'labels' => $labels,
            'description' => 'Relación de suscriptores',
            // 'public'              => false,
            // 'exclude_from_search' => true,
            // 'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            // 'menu_position'          => null,
            'menu_icon' => 'dashicons-groups',
            // 'hierarchical'        => false,
            'supports' => array(
                'custom-fields',
                // 'title',
                // 'editor',
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
        register_post_type('subscribers', $args);
    }
}