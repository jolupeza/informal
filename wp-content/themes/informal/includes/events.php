<?php while($the_query->have_posts()) : $the_query->the_post(); ?>
	<?php
		$id = get_the_id();
		$values = get_post_custom($id);
		$dateEvent = (isset($values['mb_date'])) ? esc_attr($values['mb_date'][0]) : '';

		if(!empty($dateEvent)) :
	?>
		<div class="WidgetCalendar-event">
			<div class="WidgetCalendar-event-day">
				<span class="WidgetCalendar-dayWeek"><?php echo date_i18n('D', strtotime($dateEvent)); ?></span>
				<span class="WidgetCalendar-day"><?php echo date_i18n('d', strtotime($dateEvent)); ?></span>
			</div><!-- end WidgetCalendar-event-day -->
			<div class="WidgetCalendar-event-info">
				<h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
				<?php the_excerpt(); ?>
			</div><!-- end WidgetCalendar-event-info -->
	</div><!-- end WidgetCalendar-event -->
	<?php endif; ?>
<?php endwhile; ?>