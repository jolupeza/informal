<?php get_header(); ?>

	<main class="container Main">
		<?php
			$currentCat = get_cat_id(single_cat_title("", false));
			$catMeta = get_option("category_" . $currentCat);
			$color = (isset($catMeta['mb_colour']) && !empty($catMeta['mb_colour'])) ? esc_attr($catMeta['mb_colour']) : '';
		?>

		<!-- Etiquetas -->
		<aside class="Main-tags hidden-xs hidden-sm">
			<?php
				$categories = get_categories(array('parent' => $currentCat, 'orderby' => 'count', 'order' => 'DESC', 'number' => 8));
				if(count($categories)) {
			?>
				<ul class="Main-tags-list list-inline">
					<li class="Main-tags-item Main-tags-item--first Main-tags-item--<?php echo $color; ?> text-uppercase">Categorías</li>
					<?php foreach($categories as $category) : ?>
						<?php $category_link = get_category_link($category->term_id); ?>
						<li class="Main-tags-item text-uppercase"><a href="<?php echo esc_url($category_link); ?>" title="<?php echo $category->name; ?>"><?php echo $category->name; ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php
				}
			?>
		</aside><!-- end Main-tags -->

		<!-- Post featured -->
		<?php include(TEMPLATEPATH . '/includes/featured-posts.php') ?>

		<section class="Main-content">
			<div class="Main-content-wrapper">
				<?php
					global $wp_query;
					$args = array(
						'meta_query' => array(
		                    array(
		                        'key'   => 'mb_featured',
		                        'value' => 'off'
		                    )
		                )
					);
					$args = array_merge($wp_query->query_vars, $args);
					query_posts($args);

					if(have_posts()) :
						$i = 0;
						while(have_posts()) : the_post();
							$item = ($i === 0) ? 'first' : 'second';
							$first = ($i === 0) ? true : false;
				?>
						<article class="Main-content-item Main-content-item--<?php echo $item; ?>">
							<?php
								$categories = get_the_category();
								$catMeta = get_option("category_" . $categories[0]->cat_ID);
								$color = (isset($catMeta['mb_colour']) && !empty($catMeta['mb_colour'])) ? esc_attr($catMeta['mb_colour']) : '';
							?>
							<?php if(has_post_thumbnail()) : ?>
								<figure class="Main-content-figure">
									<?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
									<?php if($first) : ?>
										<aside class="Main-content-figure-category">
											<a href="<?php echo get_category_link($categories[0]->cat_ID); ?>" style="background-color: <?php echo $color; ?>"><?php echo $categories[0]->name; ?></a>
										</aside>
									<?php endif; ?>
								</figure>
							<?php endif; ?>

							<div class="Main-content-info">
								<?php if(!$first) : ?>
									<aside class="Main-content-category">
										<a href="<?php echo get_category_link($categories[0]->cat_ID); ?>" style="background-color: <?php echo $color; ?>"><?php echo $categories[0]->name; ?></a>
										Por <span class="Main-content-author"><?php the_author_posts_link(); ?></span>
									</aside>
								<?php endif; ?>
								<h2 class="Main-content-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<?php the_content('[leer más]'); ?>

								<?php if($first) : ?>
									<p class="Main-content-text">Por <span class="Main-content-author"><?php the_author_posts_link(); ?></span></p>
								<?php endif; ?>
							</div>
						</article>
						<?php $i++; ?>
					<?php endwhile; ?>
				<?php endif; wp_reset_query(); ?>
			</div><!-- end Main-content-wrapper -->

			<?php
				$total = $wp_query->max_num_pages;
				if($total > 1) :
			?>
					<div class="Main-content-loader text-center hidden"><img src="<?php echo IMAGES; ?>/loading.gif" /></div>

					<div class="Main-content-readmore">
						<p class="text-center"><a href="" id="js-readmore-content" data-paged="1" data-author="0" data-category="<?php echo $currentCat; ?>">Ver más</a></p>
					</div><!-- end Main-content-readmore -->
			<?php endif; ?>
		</section><!-- end Main-content -->

		<aside class="Main-sidebar">
			<?php get_sidebar('single'); ?>
		</aside><!-- end Main-sidebar -->
	</main><!-- end container Main -->

	<div class="container">
		<div class="row"><?php get_sidebar('adv-bottom'); ?></div>
	</div>

<?php get_footer(); ?>