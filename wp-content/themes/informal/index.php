<?php get_header(); ?>

	<main class="container Main">
		<!-- Aquí iran las etiquetas -->
		<aside class="Main-tags">
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

		<!-- Post featured -->
		<?php include(TEMPLATEPATH . '/includes/featured-posts.php') ?>

		<div class="row">
			<div class="col-md-5">
				<!-- Posts SubFeatured -->
				<?php include(TEMPLATEPATH . '/includes/subfeatured-posts.php'); ?>
			</div><!-- end col-md-5 -->
			<div class="col-md-7">
				<!-- Aquí irá el calendario -->

				<aside class="Advertising Advertising-300-600">
					<img src="http://informal.local/public/images/publicidad-300-600.jpg" alt="" />
				</aside><!-- end Advertising -->

				<section class="Main-popular">
					<p>Aquí van los post populares</p>
				</section><!-- end Main-popular -->
			</div><!-- end col-md-7 -->
		</div><!-- end row -->
		<div class="row">
			<?php
				$categories = get_categories(array('hide_empty' => 0));
				foreach ($categories as $key => $category) {
					if ($category->category_parent !== 0 || $category->term_id === 1) {
						unset($categories[$key]);
					}
				}

				foreach($categories as $category) :
					$categories = get_the_category();
					$catMeta = get_option("category_" . $category->cat_ID);
					$color = (isset($catMeta['mb_colour']) && !empty($catMeta['mb_colour'])) ? esc_attr($catMeta['mb_colour']) : '';
			?>
				<div class="col-md-4">
					<h3 class="Main-subtitle Main-subtitle--<?php echo $color; ?>">#<?php echo $category->name; ?></h3>

					<?php
						$args = array(
							'posts_per_page' => 3,
							'cat' => $category->cat_ID
						);
						$the_query = new WP_Query($args);
						if($the_query->have_posts()) :
							while($the_query->have_posts()) : $the_query->the_post();
					?>
								<article class="Main-lastCategory">
									<?php if(has_post_thumbnail()) : ?>
										<figure class="Main-lastCategory-figure">
											<?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
										</figure><!-- end Main-lastCategory-figure -->
										<div class="Main-lastCategory-info">
											<h2 class="Main-lastCategory-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
										</div><!-- end Main-lastCategory-info -->
									<?php endif; ?>
								</article><!-- end Main-lastCategory -->
					<?php
							endwhile;
						endif;
						wp_reset_postdata();
					?>
				</div>
			<?php endforeach; ?>
		</div><!-- end row -->

		<section class="Main-content">
			<?php
				$args = array(
					'post__not_in'   => get_option('sticky_posts'),
					'meta_query' => array(
						array(
							'key'   => 'mb_subfeatured',
							'value' => 'off'
						)
					)
				);
				$the_query = new WP_Query($args);
				if($the_query->have_posts()) :
					$i = 0;
					while($the_query->have_posts()) : $the_query->the_post();
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
										<a href="<?php echo get_category_link($categories[0]->cat_ID); ?>" class="category-<?php echo $color; ?>"><?php echo $categories[0]->name; ?></a>
									</aside>
								<?php endif; ?>
							</figure>
						<?php endif; ?>

						<div class="Main-content-info">
							<?php if(!$first) : ?>
								<aside class="Main-content-category">
									<a href="<?php echo get_category_link($categories[0]->cat_ID); ?>" class="category-<?php echo $color; ?>"><?php echo $categories[0]->name; ?></a>
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
			<?php endif; wp_reset_postdata(); ?>
		</section><!-- end Main-content -->
		<aside class="Main-sidebar">
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Exercitationem earum delectus iure. Iure incidunt pariatur architecto consequuntur, non velit eum laboriosam? Reprehenderit perspiciatis, non beatae mollitia odit, inventore voluptatibus provident?
		</aside><!-- end Main-sidebar -->
	</main><!-- end container Main -->

<?php get_footer(); ?>