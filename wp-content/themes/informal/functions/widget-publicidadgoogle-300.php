<?php
/***********************************************************************************************/
/* Widget that displays plugin facebook page */
/***********************************************************************************************/

	class Informal_Google_Publicidad_300_Widget extends WP_Widget {

		public function __construct() {
			parent::__construct(
				'informal_goog_300_w',
				'Custom Widget: Publicidad Google 300',
				array('description' => __('Mostrar Publicidad de Google 300', THEMEDOMAIN))
			);
		}

		public function form($instance) {
			$defaults = array(
				'title' => '',
				'code' => '',
				'padding' => 'off'
			);

			$instance = wp_parse_args((array) $instance, $defaults);

			?>
			<!-- The Title -->
			<p>
				<label for="<?php echo $this->get_field_id('title') ?>"><?php _e('Título:', THEMEDOMAIN); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>

			<!-- Code -->
			<p>
				<label for="<?php echo $this->get_field_id('code') ?>"><?php _e('Código:', THEMEDOMAIN); ?></label>
				<textarea class="widefat" id="<?php echo $this->get_field_id('code'); ?>" name="<?php echo $this->get_field_name('code'); ?>"><?php echo $instance['code']; ?></textarea>
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
			$instance['code']    = $new_instance['code'];
			$instance['padding'] = $new_instance['padding'];

			return $instance;
		}

		public function widget($args, $instance) {
			extract($args);

			// Get the title and prepare it for display
			$title = apply_filters('widget_title', $instance['title']);

			// Get the ad
			$code = $instance['code'];
			$padding = (isset($instance['padding']) && $instance['padding'] === 'on') ? 'Ad-pd' : '';

			echo $before_widget;

			if ($title) {
				echo $before_title . $title . $after_title;
			}

			echo '<div class="Ad ' . $padding . '">';

			if ($code) {
				echo $code;
			}

			echo '</div>';
			echo $after_widget;
		}
	}

	register_widget('Informal_Google_Publicidad_300_Widget');

?>