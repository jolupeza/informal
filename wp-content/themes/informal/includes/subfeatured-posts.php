<?php
	$args = array(
		'post__not_in'   => get_option('sticky_posts'),
		'posts_per_page' => 5,
		'meta_query' => array(
			array(
				'key'   => 'mb_subfeatured',
				'value' => 'on'
			)
		)
	);
	$the_query = new WP_Query($args);
	if($the_query->have_posts()) :
		$i = 0;
?>
<section class="Main-subfeatured">
	<?php while($the_query->have_posts()) : $the_query->the_post(); ?>
		<?php $item = ($i === 0) ? 'first' : 'second'; ?>
		<?php $first = ($i === 0) ? true : false; ?>

		<?php $format = (!empty(get_post_format())) ? get_post_format() : 'standar'; ?>

			<article class="Main-subfeatured-item Main-subfeatured-item--<?php echo $item; ?>">
				<?php if (!$first) : ?>
					<h2 class="Main-subfeatured-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php endif; ?>

				<?php if(has_post_thumbnail()) : ?>
					<figure class="Main-subfeatured-image">
						<?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
						<?php
							$categories = get_the_category();
							$catMeta = get_option("category_" . $categories[0]->cat_ID);
							$color = (isset($catMeta['mb_colour']) && !empty($catMeta['mb_colour'])) ? esc_attr($catMeta['mb_colour']) : '';
						?>
						<aside class="Main-subfeatured-category">
							<!-- <span class="Format Format-<?php echo $format; ?>">&nbsp;</span> -->
							<a href="<?php echo get_category_link($categories[0]->cat_ID); ?>" style="background-color: <?php echo $color; ?>"><?php echo $categories[0]->name; ?></a>
						</aside>
					</figure><!-- end Main-subfeatured-image -->
					<div class="Main-subfeatured-info">
						<?php /* if(!$first) : ?>
							<aside class="Main-subfeatured-category">
								<!-- <span class="Format Format-<?php echo $format; ?>">&nbsp;</span> -->
								<a href="<?php echo get_category_link($categories[0]->cat_ID); ?>" style="background-color: <?php echo $color; ?>"><?php echo $categories[0]->name; ?></a>
							</aside>
						<?php endif; */ ?>

						<?php if ($first) : ?>
							<h2 class="Main-subfeatured-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php endif; ?>

						<?php if (has_excerpt()) : the_excerpt(); endif; ?>
						<p class="Main-subfeatured-text">Por <span class="Main-subfeatured-author"><?php the_author_posts_link(); ?></span></p>
					</div>
				<?php endif; ?>
			</article><!-- end Main-subfeatured-item -->
		<?php $i++; ?>
	<?php endwhile; ?>
</section>
<?php endif; wp_reset_postdata(); ?>