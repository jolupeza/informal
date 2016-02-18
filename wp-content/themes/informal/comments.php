<?php

/***********************************************************************************************/
/* Prevent the direct loading of comments.php */
/***********************************************************************************************/
if (!empty($_SERVER['SCRIPT-FILENAME']) && basename($_SERVER['SCRIPT-FILENAME']) == 'comments.php') {
	die(__('No se puede acceder a esta página directamente.', THEMEDOMAIN));
}

/***********************************************************************************************/
/* If the post is password protected then display text and return */
/***********************************************************************************************/
if (post_password_required()) : ?>
	<p>
		<?php
			_e( 'Este post está protegido con contraseña. Introduzca la contraseña para ver los comentarios.', THEMEDOMAIN);
			return;
		?>
	</p>

<?php endif;


/***********************************************************************************************/
/* Display the comment form */
/***********************************************************************************************/
	comment_form();


/***********************************************************************************************/
/* If we have comments to display, we display them */
/***********************************************************************************************/
	if (have_comments()) : ?>
		<!-- <a href="#respond" class="article-add-comment"></a> -->
		<!-- <h3><?php //comments_number(__('Ningún comentario', THEMEDOMAIN), __('1 comentario', THEMEDOMAIN), __('% comentarios', THEMEDOMAIN)); ?></h3> -->

		<ol class="commentslist">
			<?php wp_list_comments('callback=informal_comments'); ?>
		</ol>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>

			<div class="comment-nav-section clearfix">

				<p class="fl"><?php previous_comments_link(__( '&larr; Comentarios anteriores', THEMEDOMAIN)); ?></p>
				<p class="fr"><?php next_comments_link(__( 'Nuevos comentarios &rarr;', THEMEDOMAIN)); ?></p>

			</div> <!-- end comment-nav-section -->

		<?php endif; ?>

<?php
/***********************************************************************************************/
/* If we don't have comments and the comments are closed, display a text */
/***********************************************************************************************/

	elseif (!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) :

	endif;