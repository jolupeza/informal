<div class="row">
	<div class="col-md-3">
			<ul class="Category-list">
				<li class="Category-item text-uppercase">
					<a href="<?php echo esc_url(get_category_link($currentCat->cat_ID)); ?>" title="<?php echo $currentCat->name; ?>"><?php echo $currentCat->name; ?></a>
				</li>

				<?php foreach($categories as $category) : ?>
					<?php $category_link = get_category_link($category->term_id); ?>
					<li class="Category-item text-uppercase">
						<a href="<?php echo esc_url($category_link); ?>" title="<?php echo $category->name; ?>"><?php echo $category->name; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
	</div>
	<div class="col-md-9">
	</div>
</div>


