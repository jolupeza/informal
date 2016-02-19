<?php
	$sticky = get_option('sticky_posts');
	if(count($sticky)) {
		if($currentCat) {
			$args = array(
				'cat' => $currentCat,
				'meta_query' => array(
				    array(
				        'key'   => 'mb_featured',
				        'value' => 'on'
				    )
				)
			);
		} else {
			$args = array(
				'posts_per_page' => 3,
				'post__in' => $sticky,
				'ignore_sticky_posts' => 1,
				'post_status' => 'publish'
			);
		}

		$the_query = new WP_Query($args);
		if($the_query->have_posts()) :
			$i = 0;
?>
			<section class="Main-featured">
				<?php while($the_query->have_posts()) : $the_query->the_post(); ?>
					<?php $item = ($i === 0) ? 'first' : 'second'; ?>
					<figure class="Main-featured-item Main-featured-item--<?php echo $item; ?>">
						<?php if (has_post_thumbnail()) : ?>
							<?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
						<?php endif; ?>
						<article class="Main-featured-info">
							<?php
								$categories = get_the_category();
								$catMeta = get_option("category_" . $categories[0]->cat_ID);
								$color = (isset($catMeta['mb_colour']) && !empty($catMeta['mb_colour'])) ? esc_attr($catMeta['mb_colour']) : '';
							?>
							<aside class="Main-featured-category">
								<a href="<?php echo get_category_link($categories[0]->cat_ID); ?>" class="category-<?php echo $color; ?>"><?php echo $categories[0]->name; ?></a>
							</aside>
							<h2 class="Main-featured-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						</article>
					</figure><!-- end Main-featured-item -->
					<?php $i++; ?>
				<?php endwhile; ?>
			</section><!-- end Main-featured -->
<?php
		endif;
		wp_reset_postdata();
	}
?>