<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('single')) : ?>

	<div class="SidebarWidget">

		<h4><?php bloginfo('title'); ?></h4>
		<p><?php bloginfo('description'); ?></p>

	</div> <!-- end SidebarWidget -->

<?php endif; ?>