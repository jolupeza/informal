<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('center-home')) : ?>
	<div class="SidebarWidget">

		<h4><?php bloginfo('title'); ?></h4>
		<p><?php bloginfo('description'); ?></p>

	</div> <!-- end SidebarWidget -->
<?php endif; ?>