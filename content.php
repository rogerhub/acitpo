<?php
/**
 * Markup for normal posts.
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if (has_post_thumbnail()) : ?>
	<div class="entry-feature"><?php the_post_thumbnail('large'); ?></div>
	<?php endif; ?>
	<?php if (get_the_title()): ?>
	<?php if (is_single()) : ?>
	<h1 class="entry-title"><?php the_title(); ?></h1>
	<?php else: ?>
	<h1 class="entry-title"><a href="<?php the_permalink(); ?>"
		title="<?php echo esc_attr(sprintf(__('Permalink to %s', 'acitpo'),
			the_title_attribute('echo=0'))); ?>" rel="bookmark"
			><?php the_title(); ?></a></h1>
	<?php endif; ?>
	<?php endif; ?>
	<div class="entry-content">
		<?php the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'acitpo')); ?>
	</div>
	<?php wp_link_pages(array('before' => '<div class="page-links">' . __('Pages:', 'acitpo'), 'after' => '</div>', 'link_before' => '<span class="page-link">', 'link_after' => '</span>')); ?>
	<?php acitpo_entry_tags(); ?>
	<footer class="entry-meta">
		<?php acitpo_entry_meta(); ?>
	</footer>
</article>
