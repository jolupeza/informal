<?php while($the_query->have_posts()) : $the_query->the_post(); ?>
	<div class="col-md-3">
		<article class="MainMenu-lastPost-item">
			<?php if(has_post_thumbnail()) : ?>
				<figure class="MainMenu-lastPost-figure">
					<?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
				</figure>
			<?php endif; ?>
			<h2 class="MainMenu-lastPost-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		</article><!-- end Main-content-item -->
	</div>
<?php endwhile; ?>