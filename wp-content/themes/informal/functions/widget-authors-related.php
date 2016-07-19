<?php
/***********************************************************************************************/
/* Widget that displays posts popular */
/***********************************************************************************************/

    class Informal_Authors_Related_Widget extends WP_Widget
    {
        public function __construct()
        {
            parent::__construct(
                'informal_authors_related_w',
                'Custom Widget: Blog Relacionados',
                array('description' => __('Mostrar Blog relacionados', THEMEDOMAIN))
            );
        }

        public function form($instance) {
            $defaults = array(
                'title' => '',
                'number' => 3
            );

            $instance = wp_parse_args((array) $instance, $defaults);

            ?>
            <!-- The Title -->
            <p>
                <label for="<?php echo $this->get_field_id('title') ?>"><?php _e('Título:', THEMEDOMAIN); ?></label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
            </p>

            <!-- Number posts -->
            <p>
                <label for="<?php echo $this->get_field_id('number') ?>"><?php _e('Número de blogs:', THEMEDOMAIN); ?></label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo esc_attr($instance['number']); ?>" />
            </p>
            <?php
        }

        public function update($new_instance, $old_instance) {
            $instance = $old_instance;

            // The Title
            $instance['title'] = strip_tags($new_instance['title']);

            // Number Blogs
            $instance['number'] = $new_instance['number'];

            return $instance;
        }

        public function widget($args, $instance) {
            extract($args);

            // Get the title and prepare it for display
            $title = apply_filters('widget_title', $instance['title']);

            $number = (!empty($instance['number'])) ? (int)$instance['number'] : 3;

            echo $before_widget;

            if ($title) {
                echo $before_title . '<span class="Widget-titleBg">' . $title . '</span>' . $after_title;
            }

            global $wpdb;
            $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
            $authorId = $curauth->ID;

            $result = $wpdb->get_results("SELECT ID FROM $wpdb->users "
                ." INNER JOIN $wpdb->usermeta ON $wpdb->users.ID = $wpdb->usermeta.user_id "
                ." WHERE $wpdb->users.ID <> $authorId AND "
                ." $wpdb->usermeta.meta_key = 'infodb_capabilities' AND "
                ." $wpdb->usermeta.meta_value like '%editor%' "
                ." ORDER BY RAND() DESC LIMIT 0, $number");

            if(count($result)) :
                foreach ($result as $author) :
                    $authorName = get_user_meta($author->ID, 'first_name', true);
                    $authorLastName = get_user_meta($author->ID, 'last_name', true);
                    $authorDescription = get_user_meta($author->ID, 'description', true);
                    $authorFacebook = get_user_meta($author->ID, 'facebook', true);
                    $authorTwitter = get_user_meta($author->ID, 'twitter', true);
                    $authorBlog = get_user_meta($author->ID, 'blog', true);
                    $authorBg = get_user_meta($author->ID, 'bg', true);
                    $authorAvatar = get_user_meta($author->ID, 'avatar', true);
        ?>
                <article class="PostsPopular">
                    <?php if(has_post_thumbnail()) : ?>
                        <figure class="PostsPopular-image PostsAuthor-image">
                            <img src="<?php echo $authorAvatar; ?>" alt="" class="img-responsive center-block img-circle" />
                            <?php //echo get_avatar($author->ID, 96, '', false, array('class' => 'img-circle center-block')) ?>
                        </figure><!-- end PostsPopular-image -->
                    <?php endif; ?>
                    <div class="PostsPopular-info">
                        <h3 class="PostsAuthor-title"><a href="<?php echo get_author_posts_url($author->ID); ?>"><?php echo $authorBlog; ?></a></h3>
                        <h5 class="PostsBlog-title"><?php echo "$authorName $authorLastName"; ?></h5>
                    </div><!-- end PostsPopular-info -->
                </article><!-- end PostsPopular -->
        <?php
                endforeach;
            endif;

            wp_reset_postdata();
            echo $after_widget;
        }
    }

    register_widget('Informal_Authors_Related_Widget');

?>