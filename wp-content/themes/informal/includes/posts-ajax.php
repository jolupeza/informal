<?php while($the_query->have_posts()) : $the_query->the_post(); ?>
	<article class="Main-content-item Main-content-item--second">
		<?php
			$categories = get_the_category();
			$catMeta = get_option("category_" . $categories[0]->cat_ID);
			$color = (isset($catMeta['mb_colour']) && !empty($catMeta['mb_colour'])) ? esc_attr($catMeta['mb_colour']) : '';
		?>
		<?php if(has_post_thumbnail()) : ?>
			<figure class="Main-content-figure">
				<?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
			</figure>
		<?php endif; ?>

		<div class="Main-content-info">
			<aside class="Main-content-category">
				<a href="<?php echo get_category_link($categories[0]->cat_ID); ?>" class="category-<?php echo $color; ?>"><?php echo $categories[0]->name; ?></a>
				Por <span class="Main-content-author"><?php the_author_posts_link(); ?></span>
			</aside>
			<h2 class="Main-content-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php the_content('[leer más]'); ?>
		</div><!-- end Main-content-info -->
	</article><!-- end Main-content-item -->
<?php endwhile; ?>