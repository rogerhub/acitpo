<?php
/**
 * Markup for image posts.
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php the_content(); ?>
	</div>
	<?php acitpo_entry_tags(); ?>
	<footer class="entry-meta">
		<?php acitpo_entry_meta(); ?>
	</footer>
</article>
