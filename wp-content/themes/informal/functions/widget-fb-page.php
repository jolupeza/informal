<?php
/***********************************************************************************************/
/* Widget that displays plugin facebook page */
/***********************************************************************************************/

	class Informal_Fb_Page_Widget extends WP_Widget {

		public function __construct() {
			parent::__construct(
				'informal_fbpage_w',
				'Custom Widget: Plugin Facebook Page',
				array('description' => __('Mostrar plugin Page de Facebook', THEMEDOMAIN))
			);
		}

		public function form($instance) {
			$defaults = array(
				'title' => __('#Facebook', THEMEDOMAIN),
				'code' => '',
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

			<?php
		}

		public function update($new_instance, $old_instance) {
			$instance = $old_instance;

			// The Title
			$instance['title'] = strip_tags($new_instance['title']);

			// The Code
			$instance['code'] = $new_instance['code'];

			return $instance;
		}

		public function widget($args, $instance) {
			extract($args);

			// Get the title and prepare it for display
			$title = apply_filters('widget_title', $instance['title']);

			// Get the ad
			$code = $instance['code'];

			echo $before_widget;

			if ($title) {
				echo $before_title . $title . $after_title;
			}

			echo '<div class="Fbpage-wrapper">';

			if ($code) {
				echo $code;
			}

			echo '</div>';
			echo $after_widget;
		}
	}

	register_widget('Informal_Fb_Page_Widget');

?>