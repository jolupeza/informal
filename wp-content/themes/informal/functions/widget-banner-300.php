<?php
/***********************************************************************************************/
/* Widget that displays plugin banner */
/***********************************************************************************************/

	class Informal_Banner_300_Widget extends WP_Widget
	{
		public function __construct()
		{
			parent::__construct(
				'informal_banner_300_w',
				'Custom Widget: Banner Publicitario 300',
				array('description' => __('Mostrar Banner Publicitario 300', THEMEDOMAIN))
			);
		}

		public function form($instance) {
			$defaults = array(
				'title' => '',
				'banner' => '',
				'padding' => 'off'
			);

			$instance = wp_parse_args((array) $instance, $defaults);

			?>
			<!-- The Title -->
			<p>
				<label for="<?php echo $this->get_field_id('title') ?>"><?php _e('Título:', THEMEDOMAIN); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>

			<!-- Banner -->
			<?php
				$args = array(
					'posts_per_page' => -1,
					'post_type' => 'banners',
				);
				$the_query = new WP_Query($args);
			?>
			<p>
				<label for="<?php echo $this->get_field_id('banner') ?>"><?php _e('Banner:', THEMEDOMAIN); ?></label>
				<?php if($the_query->have_posts()) : ?>
		        	<select id="<?php echo $this->get_field_id('banner') ?>" name="<?php echo $this->get_field_name('banner'); ?>">
		        		<option value="">-- Indica tu banner --</option>
						<?php while($the_query->have_posts()) : $the_query->the_post(); ?>
							<?php $id = get_the_ID(); ?>
							<option value="<?php echo $id; ?>" <?php selected( $instance['banner'], $id); ?>><?php the_title(); ?></option>
						<?php endwhile; ?>
		            </select>
				<?php endif; wp_reset_postdata(); ?>
			</p>

			<!-- Padding -->
			<p>
				<label for="<?php echo $this->get_field_id('padding') ?>"><?php _e('Con padding:', THEMEDOMAIN); ?></label>
				<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id('padding'); ?>" name="<?php echo $this->get_field_name('padding'); ?>" value="on" <?php checked( $instance['padding'], 'on' ); ?> />
			</p>
			<?php
		}

		public function update($new_instance, $old_instance) {
			$instance = $old_instance;

			// The Title
			$instance['title'] = strip_tags($new_instance['title']);

			// The Code
			$instance['banner']  = $new_instance['banner'];
			$instance['padding'] = $new_instance['padding'];

			return $instance;
		}

		public function widget($args, $instance) {
			extract($args);

			// Get the title and prepare it for display
			$title = apply_filters('widget_title', $instance['title']);

			// Get the ad
			$banner = (int)$instance['banner'];
			$padding = (isset($instance['padding']) && $instance['padding'] === 'on') ? 'Ad-pd' : '';

			echo $before_widget;

			if ($title) {
				echo $before_title . $title . $after_title;
			}

			echo '<div class="Ad ' . $padding . '">';

			if ($banner) {
				$args = array(
					'post_type' => 'banners',
					'p' => $banner
				);
				$the_query = new WP_Query($args);

				if($the_query->have_posts()) {
					while($the_query->have_posts()) {
						$the_query->the_post();
						$id = get_the_id();
						$values = get_post_custom($id);
						$link   = isset( $values['mb_link'] ) ? esc_attr( $values['mb_link'][0] ) : '';
						$target = isset( $values['mb_target'] ) ? esc_attr( $values['mb_target'][0] ) : '';
						$target = (!empty($target) && $target === 'on') ? 'target="_blank"' : '';

						echo (!empty($link)) ? '<a href="' . $link . '" title="' . get_the_title() . '" ' . $target . ' >' : '';
						if(has_post_thumbnail()) {
							the_post_thumbnail('full', array('class' => 'img-responsive center-block'));
						}
						echo (!empty($link)) ? '</a>' : '';
					}
				}
			}

			echo '</div>';
			echo $after_widget;
		}
	}

	register_widget('Informal_Banner_300_Widget');

?>