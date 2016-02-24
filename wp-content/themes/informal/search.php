<?php get_header(); ?>

	<main class="container Main">
		<section class="Main-content">
			<?php
				global $wp_query;

				if(have_posts()) :
			?>
					<div class="Search-results">
						<h4>Búsqueda de Resultados para: <span><?php echo get_search_query(); ?></span></h4>
					</div><!-- end Search-results -->

					<div class="Main-content-wrapper">
						<?php while(have_posts()) : the_post(); ?>
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
											<a href="<?php echo get_category_link($categories[0]->cat_ID); ?>" style="background-color: <?php echo $color; ?>"><?php echo $categories[0]->name; ?></a>
											Por <span class="Main-content-author"><?php the_author_posts_link(); ?></span>
										</aside>

										<h2 class="Main-content-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
										<?php the_content('[leer más]'); ?>
									</div>
								</article>
						<?php endwhile; ?>
					</div><!-- end Main-content-wrapper -->
			<?php else : ?>
					<article class="NoPosts">
						<h1 class="text-center"><?php _e('No se han encontrado resultados coincidentes. Por favor, intente con otra palabra.', THEMEDOMAIN); ?></h1>
					</article><!-- end NoPosts -->
			<?php endif; ?>

			<?php
				$total = $wp_query->max_num_pages;
				if($total > 1) :
			?>
					<div class="Main-content-loader text-center hidden"><img src="<?php echo IMAGES; ?>/loading.gif" /></div>

					<div class="Main-content-readmore">
						<p class="text-center"><a href="" id="js-readmore-content" data-paged="1" data-author="0" data-category="0" data-search="<?php echo get_search_query() ?>">Ver más</a></p>
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