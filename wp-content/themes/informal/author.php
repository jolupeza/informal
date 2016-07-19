<?php get_header(); ?>
	<?php
		$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
		$authorId = $curauth->ID;
		// $usermeta = get_userdata($authorId);

		$authorName = get_user_meta($authorId, 'first_name', true);
		$authorLastName = get_user_meta($authorId, 'last_name', true);
		$authorDescription = get_user_meta($authorId, 'description', true);
		$authorFacebook = get_user_meta($authorId, 'facebook', true);
		$authorTwitter = get_user_meta($authorId, 'twitter', true);
		$authorBlog = get_user_meta($authorId, 'blog', true);
		$authorBg = get_user_meta($authorId, 'bg', true);
		$authorAvatar = get_user_meta($authorId, 'avatar', true);
		$authorColor = get_user_meta($authorId, 'color', true);
	?>

	<section class="Author-bg" style="background-color: <?php echo $authorColor ?>;">
		<h2 class="Author-blogTitle text-center text-uppercase">
			<img src="<?php echo $authorBg; ?>" alt="<?php echo $authorBlog; ?>" class="img-responsive center-block">
			<?php echo $authorBlog; ?>
		</h2>
	</section>

	<main class="container Main Author-main">
		<section class="Main-content Author-content">
			<?php if(have_posts()) : ?>
				<?php $i = 0; ?>
				<article class="Main-content-item Main-content-item--single">
					<div class="Main-content-wrapper">
						<?php while(have_posts()) : the_post(); ?>
							<article class="Main-content-item Main-content-item--second">
								<?php
									$categories = get_the_category();
									$catMeta = get_option("category_" . $categories[0]->cat_ID);
									$color = (isset($catMeta['mb_colour']) && !empty($catMeta['mb_colour'])) ? esc_attr($catMeta['mb_colour']) : '';
								?>

								<?php if ($i === 0) : ?>
									<figure class="Author-figure">
										<?php the_post_thumbnail('full', array('class' => 'img-responsive center-block')); ?>
										<div class="Author-info">
											<aside class="Main-content-category">
												<a href="<?php echo get_category_link($categories[0]->cat_ID); ?>" style="background-color: <?php echo $color; ?>"><?php echo $categories[0]->name; ?></a>
												<!-- Por <span class="Main-content-author"><?php // the_author_posts_link(); ?></span> -->
											</aside>

											<h2 class="Author-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
										</div>
									</figure>
								<?php else : ?>
									<?php if(has_post_thumbnail()) : ?>
										<figure class="Main-content-figure">
											<?php the_post_thumbnail('full', array('class' => 'img-responsive center-block')); ?>
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
								<?php endif; ?>
							</article>
							<?php $i++; ?>
						<?php endwhile; ?>
					</div><!-- end Main-content-wrapper -->
				</article><!-- end Main-content-item -->

				<?php
					global $wp_query;
					$total = $wp_query->max_num_pages;
					$paged = 1;
					if($total > 1) :
				?>
						<div class="Main-content-loader text-center hidden"><img src="<?php echo IMAGES; ?>/loading.gif" /></div>

						<div class="Main-content-readmore">
							<p class="text-center"><a href="" id="js-readmore-content" data-paged="<?php echo $paged; ?>" data-author="<?php echo $authorId; ?>" data-category="0" data-search="0" data-tag="0">Ver más</a></p>
						</div><!-- end Main-content-readmore -->
				<?php endif; ?>
			<?php endif; ?>
		</section><!-- end Main-content -->

		<aside class="Main-sidebar Author-sidebar">
			<aside class="Main-content-metaauthor">
				<figure class="Main-content-avatar">
					<img src="<?php echo $authorAvatar; ?>" alt="" class="img-responsive center-block img-circle" />
				</figure><!-- end Main-content-avatar -->
				<div class="Main-content-metaauthor-info">
					<h3 class="Main-content-metaauthor-name"><?php echo "$authorName $authorLastName"; ?></h3>
					<h4 class="Main-content-metaauthor-blog">Autor de: <span><?php echo $authorBlog; ?></span></h4>
					<p class="Main-content-metaauthor-desc"><?php echo $authorDescription; ?></p>

					<?php if(!empty($authorFacebook) || !empty($authorTwitter)) : ?>
						<ul class="list-inline Main-content-metaauthor-social">
							<?php if(!empty($authorFacebook)) : ?>
								<li class="Metaauthor-fb"><a class="text-hide" href="https://www.facebook.com/<?php echo $authorFacebook; ?>" title="Facebook" target="_blank">F</a></li>
							<?php endif; ?>

							<?php if(!empty($authorTwitter)) : ?>
								<li class="Metaauthor-tw"><a class="text-hide" href="https://twitter.com/<?php echo $authorTwitter; ?>" title="Twitter" target="_blank">T</a></li>
							<?php endif; ?>
						</ul><!-- end Main-content-metaauthor-social -->
					<?php endif; ?>
				</div><!-- end Main-content-author-info -->
			</aside><!-- end Main-content-author -->

			<?php get_sidebar('author'); ?>
		</aside><!-- end Main-sidebar -->
	</main><!-- end container Main -->

	<div class="container">
		<div class="row"><?php get_sidebar('adv-bottom'); ?></div>
	</div>

<?php get_footer(); ?>