<!DOCTYPE html>
<!--[if IE 8]> <html <?php language_attributes(); ?> class="ie8"> <![endif]-->
<!--[if !IE]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?php bloginfo('description'); ?>" />
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>

    <!-- Stylesheet -->
    <link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet" />

    <!-- Pingbacks -->
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<!-- Favicon and Apple Icons -->
	<link rel="shortcut icon" href="<?php print IMAGES; ?>/favicon.ico">
	<!-- <link rel="apple-touch-icon" href="<?php // print IMAGES; ?>/icons/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php // print IMAGES; ?>/icons/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php // print IMAGES; ?>/icons/apple-touch-icon-114x114.png"> -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- PictureFill -->
    <!-- <script src="https://cdn.rawgit.com/scottjehl/picturefill/master/dist/picturefill.min.js"></script> -->

    <!-- Script required for extra functionality on the comment form -->
	<?php if (is_singular()) wp_enqueue_script( 'comment-reply' ); ?>

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v2.5&appId=624813780917856";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>

	<?php
		$options = get_option('informal_custom_settings');
		$logo = (!empty($options['logo'])) ? $options['logo'] : IMAGES . '/logo.png';
	?>
	<header class="Header navbar navbar-fixed-top sb-slide" role="navigation">
		<div class="sb-toggle-left Header-bar hidden-md hidden-lg"><span class="Header-bar-icon"></span></div>

		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<h1 class="Header-logo text-right">
						<a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>"><img class="center-block" src="<?php echo $logo; ?>" alt="<?php bloginfo('name'); ?> | <?php bloginfo('description'); ?>" /></a>
					</h1>
				</div>
				<div class="col-md-6 hidden-xs hidden-sm">
					<?php
						$args = array(
							'theme_location' => 'main-menu',
							'container' => 'nav',
							'container_class' => 'Header-mainMenu',
							'menu_class' => 'MainMenu list-inline'
						);
						wp_nav_menu($args);
					?>
				</div>
				<div class="col-md-3 hidden-xs hidden-sm">
					<nav class="Header-social">
						<ul class="list-inline Header-social-list text-right">
							<?php if(isset($options['display_social_link']) && $options['display_social_link']) : ?>
								<?php if(!empty($options['facebook'])) : ?>
									<li class="Header-social-item Header-social-item--facebook">
										<a href="https://www.facebook.com/<?php echo $options['facebook']; ?>" class="text-hide" target="_blank" title="Síguenos en facebook">Facebook</a>
									</li>
								<?php endif; ?>
								<?php if(!empty($options['twitter'])) : ?>
									<li class="Header-social-item Header-social-item--twitter">
										<a href="https://www.twitter.com/<?php echo $options['twitter']; ?>" class="text-hide" target="_blank" title="Síguenos en twitter">Facebook</a>
									</li>
								<?php endif; ?>
							<?php endif; ?>
							<li class="Header-social-item Header-social-item--search"><a href="" class="text-hide">Buscar</a></li>
						</ul><!-- end Header-social-list -->
					</nav><!-- end Header-social -->
				</div>
			</div>
		</div>
	</header><!-- end Header -->

	<div class="sb-slidebar sb-left Slidebar">
		<h1 class="Header-logo text-right">
			<a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>"><img class="center-block" src="<?php echo $logo; ?>" alt="<?php bloginfo('name'); ?> | <?php bloginfo('description'); ?>" /></a>
		</h1><!-- end Header-logo -->
		<?php
			$args = array(
				'theme_location' => 'main-menu',
				'container' => 'nav',
				'container_class' => 'Header-mainMenu',
				'menu_class' => 'MainMenu'
			);
			wp_nav_menu($args);
		?>

		<nav class="Header-social">
			<ul class="list-inline Header-social-list text-center">
				<?php if(isset($options['display_social_link']) && $options['display_social_link']) : ?>
					<?php if(!empty($options['facebook'])) : ?>
						<li class="Header-social-item Header-social-item--facebook">
							<a href="https://www.facebook.com/<?php echo $options['facebook']; ?>" class="text-hide" target="_blank" title="Síguenos en facebook">Facebook</a>
						</li>
					<?php endif; ?>
					<?php if(!empty($options['twitter'])) : ?>
						<li class="Header-social-item Header-social-item--twitter">
							<a href="https://www.twitter.com/<?php echo $options['twitter']; ?>" class="text-hide" target="_blank" title="Síguenos en twitter">Facebook</a>
						</li>
					<?php endif; ?>
				<?php endif; ?>
			</ul><!-- end Header-social-list -->
		</nav><!-- end Header-social -->
	</div><!-- end Slidebar -->

	<div id="sb-site" class="Slidebar-content">