<?php
/**
 * Markup for quote posts
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php the_content(); ?>
	</div>
	<?php if (get_the_title()) : ?>
	<div class="entry-attribution">&mdash; <?php the_title(); ?></div>
	<?php endif; ?>
	<?php acitpo_entry_tags(); ?>
	<footer class="entry-meta">
		<?php acitpo_entry_meta(); ?>
	</footer>
</article>
