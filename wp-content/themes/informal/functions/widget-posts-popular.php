<?php
/***********************************************************************************************/
/* Widget that displays posts popular */
/***********************************************************************************************/

	class Informal_Posts_Popular_Widget extends WP_Widget
	{
		public function __construct()
		{
			parent::__construct(
				'informal_posts_popular_w',
				'Custom Widget: Posts Populares',
				array('description' => __('Mostrar Posts Populares', THEMEDOMAIN))
			);
		}

		public function form($instance) {
			$defaults = array(
				'title' => '',
				'number' => 5
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
				<label for="<?php echo $this->get_field_id('number') ?>"><?php _e('Número de posts:', THEMEDOMAIN); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo esc_attr($instance['number']); ?>" />
			</p>
			<?php
		}

		public function update($new_instance, $old_instance) {
			$instance = $old_instance;

			// The Title
			$instance['title'] = strip_tags($new_instance['title']);

			// Number Posts
			$instance['number'] = $new_instance['number'];

			return $instance;
		}

		public function widget($args, $instance) {
			extract($args);

			// Get the title and prepare it for display
			$title = apply_filters('widget_title', $instance['title']);

			$number = (!empty($instance['number'])) ? (int)$instance['number'] : 5;

			echo $before_widget;

			if ($title) {
				echo $before_title . '<span class="Widget-titleBg">' . $title . '</span>' . $after_title;
			}

			global $wpdb;
			global $post;
			$result = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts "
				." INNER JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id "
				." WHERE $wpdb->postmeta.meta_key = 'wpb_post_views_count' AND "
				." $wpdb->posts.post_type = 'post' AND "
				." $wpdb->posts.post_status = 'publish' "
				." ORDER BY $wpdb->postmeta.meta_value DESC LIMIT 0, $number");


			if(count($result)) :
				foreach ($result as $post) :
					setup_postdata($post);
					$id = get_the_ID();
		?>
				<article class="PostsPopular">
					<?php if(has_post_thumbnail()) : ?>
						<figure class="PostsPopular-image">
							<?php the_post_thumbnail('full', array('class' => 'img-responsive center-block')); ?>
						</figure><!-- end PostsPopular-image -->
					<?php endif; ?>
					<div class="PostsPopular-info">
						<h3 class="PostsPopular-title"><a href="<?php echo get_permalink($id); ?>"><?php the_title(); ?></a></h3>
						<p class="PostsPopular-time">hace <span><?php echo human_time_diff( get_the_time('U'), current_time('timestamp')); ?></span></p>
					</div><!-- end PostsPopular-info -->
				</article><!-- end PostsPopular -->
		<?php
				endforeach;
			endif;

			wp_reset_postdata();
			echo $after_widget;
		}
	}

	register_widget('Informal_Posts_Popular_Widget');

?>