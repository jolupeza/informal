<?php get_header(); ?>

	<main class="container Main">

	<?php
		$currentTag = get_query_var('tag');
	?>

		<!-- Etiquetas -->
		<aside class="Main-tags hidden-xs hidden-sm">
			<?php
				$tags = get_tags(array('orderby' => 'count', 'order' => 'DESC', 'number' => 8));
				if(count($tags)) {
			?>
				<ul class="Main-tags-list list-inline">
					<li class="Main-tags-item Main-tags-item--first text-uppercase">#TRENDING</li>
					<?php foreach($tags as $tag) : ?>
						<?php $tag_link = get_tag_link($tag->term_id); ?>
						<li class="Main-tags-item text-uppercase"><a href="<?php echo $tag_link; ?>" title="<?php echo $tag->name; ?>"><?php echo $tag->name; ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php
				}
			?>
		</aside><!-- end Main-tags -->

		<section class="Main-content">
			<div class="Main-content-wrapper">
				<?php
					global $wp_query;

					if(have_posts()) :
				?>
						<div class="Search-results">
							<h4>Búsqueda de Resultados para: <span><?php echo single_tag_title(); ?></span></h4>
						</div><!-- end Search-results -->
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
								<?php if (has_excerpt()) : the_excerpt(); endif; ?>
							</div>
						</article>
					<?php endwhile; ?>
				<?php endif; ?>
			</div><!-- end Main-content-wrapper -->

			<?php
				$total = $wp_query->max_num_pages;
				if($total > 1) :
			?>
					<div class="Main-content-loader text-center hidden"><img src="<?php echo IMAGES; ?>/loading.gif" /></div>

					<div class="Main-content-readmore">
						<p class="text-center"><a href="" id="js-readmore-content" data-paged="1" data-author="0" data-category="<?php echo $currentCat; ?>" data-tag="<?php echo $currentTag; ?>" data-search="0">Ver más</a></p>
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