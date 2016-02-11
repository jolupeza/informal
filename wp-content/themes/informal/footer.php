		<?php
			$options = get_option('informal_custom_settings');
			$logo = (!empty($options['logo_footer'])) ? $options['logo_footer'] : IMAGES . '/logo_footer.png';
		?>
		<footer class="Footer">
			<div class="container">
				<section class="Footer-wrapper">
					<article class="Footer-item">
						<h2 class="Footer-title"><a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>"><img src="<?php echo $logo; ?>" alt="<?php bloginfo('name'); ?> | <?php bloginfo('description'); ?>" /></a></h2>
						<?php if(!empty($options['about'])) : ?>
							<p class="Footer-about"><?php echo $options['about']; ?></p>
						<?php endif; ?>
					</article>
					<article class="Footer-item">
						<h2 class="Footer-title text-uppercase">Etiquetas</h2>
						<?php
							$tags = get_tags(array('orderby' => 'count', 'order' => 'DESC', 'number' => 8));
							if(count($tags)) {
						?>
							<ul class="Footer-tags list-inline">
								<?php foreach($tags as $tag) : ?>
									<?php $tag_link = get_tag_link($tag->term_id); ?>
									<li><a href="<?php echo $tag_link; ?>" title="<?php echo $tag->name; ?>"><?php echo $tag->name; ?></a></li>
								<?php endforeach; ?>
							</ul>
						<?php
							}
						?>
					</article>
					<article class="Footer-item">
						<h2 class="Footer-title text-uppercase text-center">Suscríbete</h2>
					</article>
					<article class="Footer-item">
						<?php
							$args = array(
								'theme_location' => 'footer-menu',
								'container' => 'nav',
								'container_class' => 'Footer-wrapperMenu',
								'menu_class' => 'Footer-menu'
							);
							wp_nav_menu($args);
						?>
					</article>
				</section><!-- end Footer-wrapper -->
			</div><!-- end container -->
		</footer><!-- end Footer -->
		<?php wp_footer(); ?>
	</body>
</html>