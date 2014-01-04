<?php
/**
 * The default template/
 */

get_header();

if (have_posts()) {
	while (have_posts()) {
		the_post();
		get_template_part('content', get_post_format());
		comments_template();
	}
	acitpo_content_nav('nav-below');
} else {
	get_template_part('content', 'none');
}

get_footer();
