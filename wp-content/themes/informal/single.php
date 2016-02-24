<?php get_header(); ?>

	<main class="container Main">
		<section class="Main-content">
			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
					<?php $format = (!empty(get_post_format())) ? get_post_format() : 'standar'; ?>
					<article class="Main-content-item Main-content-item--single">
						<?php if(has_post_thumbnail()) : ?>
							<figure class="Main-single-figure">
								<?php the_post_thumbnail('full', array('class' => 'img-responsive center-block')); ?>
							</figure><!-- end Main-single-figure -->
						<?php endif; ?>
						<?php
							$categories = get_the_category();
							$catMeta = get_option("category_" . $categories[0]->cat_ID);
							$color = (isset($catMeta['mb_colour']) && !empty($catMeta['mb_colour'])) ? esc_attr($catMeta['mb_colour']) : '';
						?>
						<aside class="Main-content-category">
							<span class="Format Format-<?php echo $format; ?>" style="background-color: <?php echo $color; ?>">&nbsp;</span>
							<a href="<?php echo get_category_link($categories[0]->cat_ID); ?>" style="background-color: <?php echo $color; ?>"><?php echo $categories[0]->name; ?></a>
						</aside>

						<h2 class="Main-content-title"><?php the_title(); ?></h2>
						<p class="Main-content-text">Por <span class="Main-content-author"><?php the_author_posts_link(); ?></span></p>

						<?php the_content(); ?>

						<?php if(has_tag()) : ?>
							<aside class="Main-single-tags">
								<?php the_tags( 'Etiquetas: ', ' '); ?>
							</aside><!-- end Main-single-tags -->
						<?php endif; ?>

						<!-- Share Button Social Network -->

						<?php
							$authorId = $post->post_author;
							$usermeta = get_userdata($authorId);
							$authorDescription = get_user_meta($authorId, 'description', true);
							$authorFacebook = get_user_meta($authorId, 'facebook', true);
							$authorTwitter = get_user_meta($authorId, 'twitter', true);
							$authorBlog = get_user_meta($authorId, 'blog', true);
						?>
						<aside class="Main-content-metaauthor">
							<figure class="Main-content-avatar">
								<?php echo get_avatar($authorId, 128, '', false, array('class' => 'img-circle center-block')); ?>
							</figure><!-- end Main-content-avatar -->
							<div class="Main-content-metaauthor-info">
								<h4 class="Main-content-metaauthor-blog"><?php echo $authorBlog; ?></h4>
								<h3 class="Main-content-metaauthor-name"><?php the_author_posts_link(); ?></h3>
								<p class="Main-content-metaauthor-desc"><?php echo $authorDescription; ?></p>

								<?php if(!empty($authorFacebook) || !empty($authorTwitter)) : ?>
									<ul class="list-inline Main-content-metaauthor-social">
										<?php if(!empty($authorFacebook)) : ?>
											<li class="Metaauthor-fb"><a class="text-hide" href="https://www.facebook.com/profile.php?id=<?php echo $authorFacebook; ?>" title="Facebook" target="_blank">F</a></li>
										<?php endif; ?>

										<?php if(!empty($authorTwitter)) : ?>
											<li class="Metaauthor-tw"><a class="text-hide" href="https://twitter.com/<?php echo $authorTwitter; ?>" title="Twitter" target="_blank">T</a></li>
										<?php endif; ?>
									</ul><!-- end Main-content-metaauthor-social -->
								<?php endif; ?>
							</div><!-- end Main-content-author-info -->
						</aside><!-- end Main-content-author -->

						<aside class="comments-area" id="comments">

							<?php comments_template('', true); ?>

						</aside><!-- end comments -->

					</article><!-- end Main-content-item -->
				<?php endwhile; ?>
			<?php endif; ?>

			<!-- Posts Related -->
			<section class="Main-related">
				<?php
					global $post;
					$tags = wp_get_post_tags($post->ID);

					if ($tags) :
						$tagIds = array();
						foreach($tags as $tag) {
							$tagIds[] = $tag->term_id;
						}

						$args = array(
							'tag__in' => $tagIds,
							'post__not_in' => array($post->ID),
							'posts_per_page' => 3,
						);
						$the_query = new WP_Query($args);

						if($the_query->have_posts()) :
					?>
							<h3 class="Main-related-titleSection">Post Relacionados</h3>
						<?php while($the_query->have_posts()) : $the_query->the_post(); ?>
							<?php
								$categories = get_the_category();
								$catMeta = get_option("category_" . $categories[0]->cat_ID);
								$color = (isset($catMeta['mb_colour']) && !empty($catMeta['mb_colour'])) ? esc_attr($catMeta['mb_colour']) : '';
							?>
							<article class="Main-related-item">
								<figure class="Main-related-figure">
									<?php
										the_post_thumbnail('full', array('class' => 'img-responsive center-block'));
									?>
								</figure><!-- end Main-related-figure -->
								<div class="Main-related-info">
									<aside class="Main-related-category">
										<a href="<?php echo get_category_link($categories[0]->cat_ID); ?>" style="background-color: <?php echo $color; ?>"><?php echo $categories[0]->name; ?></a>
										Por <span class="Main-related-author"><?php the_author_posts_link(); ?></span>
									</aside>
									<h4 class="Main-related-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

									<?php the_content('[leer más]'); ?>
								</div><!-- end Main-related-info -->
							</article><!-- end Main-related -->
				<?php
							endwhile;
						endif;
						wp_reset_postdata();
					endif;
				?>

			</section><!-- end Main-related -->
		</section><!-- end Main-content -->

		<aside class="Main-sidebar">
			<?php get_sidebar('single'); ?>
		</aside><!-- end Main-sidebar -->
	</main><!-- end container Main -->

	<div class="container">
		<div class="row"><?php get_sidebar('adv-bottom'); ?></div>
	</div>

<?php get_footer(); ?>