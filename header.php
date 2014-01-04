<?php
/**
 * Markup for header
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title><?php wp_title('&raquo; ', true, 'right'); ?></title>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div id="page" class="hfeed site">
			<header class="site-header" role="banner">
				<?php if ($header_image = get_header_image()) : ?>
				<div class="site-logo">
					<a href="<?php echo esc_url(home_url('/')); ?>">
					<img class="site-logo-icon" src="<?php echo $header_image; ?>"
						title="<?php bloginfo('name'); ?>"
						alt="<?php bloginfo('name'); ?>" />
					</a>
				</div>
				<?php endif; ?>
				<?php if ($site_name = get_bloginfo('name')) : ?>
				<div class="site-name"><a href="<?php echo esc_url(home_url('/')); ?>">
					<?php echo $site_name; ?>
				</a></div>
				<?php endif; ?>
				<?php if ($site_description = get_bloginfo('description')) : ?>
				<div class="site-description"><?php echo $site_description; ?></div>
				<?php endif; ?>
			</header>
		<div class="main">
			<?php $paged = acitpo_get_archive_page(); ?>
			<?php if ($paged > 1) { ?><h2 class="site-page"><?php printf(__('Page %d', 'acitpo'), $paged); ?></h2><?php } ?>
