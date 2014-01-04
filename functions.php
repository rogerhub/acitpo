<?php
/**
 * Functions for theme
 */

/**
 * Sets the content width for this theme. This value is mostly irrelevant because CSS takes care of the rest.
 */
if (!isset($content_width)) $content_width = 502;

/**
 * Registers theme support abilities
 */
function acitpo_after_theme_setup() {
	add_theme_support('automatic-feed-links');
	add_theme_support('custom-header', array(
		'default-image' => acitpo_get_default_header_image(),
		'width' => 120,
		'height' => 120,
		'flex-width' => true,
		'flex-height' => true,
		'default-text-color' => '333',
		'header-text' => true,
		'uploads' => true,
		'wp-head-callback' => 'acitpo_custom_header_wp_head',
		'admin-head-callback' => 'acitpo_custom_header_admin_head',
		'admin-preview-callback' => 'acitpo_custom_header_admin_preview',
	));
	add_theme_support('post-formats', array('aside', 'image', 'link', 'quote', 'video'));
	add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'acitpo_after_theme_setup');

/**
 * A default header image
 *
 * Use the admin email's gravatar as the default header image. (Taken from RYU by Automattic, GPLv2)
 */
function acitpo_get_default_header_image() {

	// Get default from Discussion Settings.
	$default = get_option( 'avatar_default', 'mystery' ); // Mystery man default
	if ( 'mystery' == $default )
		$default = 'mm';
	elseif ( 'gravatar_default' == $default )
		$default = '';

	$protocol = ( is_ssl() ) ? 'https://secure.' : 'http://';
	$url = sprintf( '%1$sgravatar.com/avatar/%2$s/', $protocol, md5( get_option( 'admin_email' ) ) );
	$url = add_query_arg( array(
		's' => 120,
		'd' => urlencode( $default ),
	), $url );

	return esc_url_raw( $url );
}

/**
 * Prepares custom header text color
 */
function acitpo_custom_header_wp_head() {
	$header_text_color = get_header_textcolor();

	if ($header_text_color == 'ccc')
		return;
	?>
	<style type="text/css">
	<?php if ( ! display_header_text() ) : ?>
		.site-name,
		.site-description {
			position: absolute;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php else : ?>
		.site-name a,
		.site-description {
			color: #<?php echo $header_text_color; ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}

/**
 * Prepares admin styles for header image
 */
function acitpo_custom_header_admin_head() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
		text-align: center;
	}
	<?php if ( ! display_header_text() ) : ?>
	#headimg h1,
	#desc {
		display: none;
	}
	<?php endif; ?>
	#headimg h1 {
		font: 700 42px/1 'Helvetica Neue', Arial, Helvetica, sans-serif;
		margin: 15px auto;
	}
	#headimg h1 a {
		text-decoration: none;
	}
	#desc {
		font: 14px/1.3 'Helvetica Neue', Arial, Helvetica, sans-serif;
		opacity: 0.7;
	}
	#headimg img {
		margin: 30px auto 15px auto
	}
	#headimg img[src*="gravatar"] {
		border-radius: 50%;
	}
	</style>
<?php
}

/**
 * Prepares admin preview for header image
 */
function acitpo_custom_header_admin_preview() {
	$style = sprintf( ' style="color:#%s;"', get_header_textcolor() );
	$header_image = get_header_image();
?>
	<div id="headimg">
		<?php if ( ! empty( $header_image ) ) : ?>
		<img src="<?php echo esc_url( $header_image ); ?>" alt="">
		<?php endif; ?>
		<h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div class="displaying-header-text" id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
	</div>
<?php
}

/**
 * Gets page of archive view
 */
function acitpo_get_archive_page() {
	if (is_single() || is_page()) {
		return -1;
	}
	global $paged;
	if ($page = get_query_var('paged')) {
		return $page;
	} else {
		return $paged;
	}
}

/**
 * Enqueues required stylesheets and javascript
 */
function acitpo_wp_enqueue_script() {
	wp_register_style('acitpo-theme', get_stylesheet_uri(), filemtime(get_template_directory() . '/style.css'));
	wp_register_script('acipto-global', get_template_directory_uri() . '/js/global.js', array('jquery'), filemtime(get_template_directory() . '/js/global.js'));

	if (is_single() && comments_open()) {
		wp_enqueue_script('comment-reply');
	}

	if (!is_admin()) {
		wp_enqueue_style('acitpo-theme');
		wp_enqueue_script('acipto-global');
	}
}
add_action('wp_enqueue_scripts', 'acitpo_wp_enqueue_script');

/**
 * Prints out post tags
 */
function acitpo_entry_tags() {
	$tags = get_the_tag_list('', __('', 'acitpo'));
	if ($tags) {
		printf('<div class="entry-tags">%s</div>', $tags);
	}
}

/**
 * Prints out post meta
 */
function acitpo_entry_meta() {
	global $post;
	if (is_page()) { return; }
	$categories = get_the_category_list(__(', ', 'acitpo'));

	printf('<span class="date-link"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>', esc_attr(get_permalink()), esc_attr(get_the_time()), esc_attr(get_the_time('c')), esc_html(get_the_date()));
	if ($categories) {
		printf('<span class="category-links">%s</span>', $categories);
	}
	printf('<span class="author-link vcard"><a href="%1$s" title="%2$s" rel="author">%3$s</a></span>', esc_attr(get_author_posts_url(get_the_author_meta('ID'))), esc_attr(sprintf(__('View all posts by %s', 'acitpo'), get_the_author())), esc_html(get_the_author()));
}

/**
 * Prints out older/newer navigation in The Loop
 */
function acitpo_content_nav($html_id) {
	global $wp_query;

	if ($wp_query->max_num_pages > 1) {
		printf('<nav id="%s" class="navigation nav-below" role="navigation">', esc_attr($html_id));
		printf('<h3 class="assistive-text">%s</h3>', __('Post navigation', 'acitpo'));
		echo '<div class="nav-next alignleft">';
		previous_posts_link(__('Previous', 'acitpo'));
		echo '</div>';
		echo '<div class="nav-previous alignright">';
		next_posts_link(__('Next Page', 'acitpo'));
		echo '</div>';
		echo '<div class="clear-fix"></div>';
		echo '</nav>';
	}
}

/**
 * Creates a nicely formatted title element text
 */
function acitpo_wp_title($title, $sep) {
	global $paged, $page;

	if (is_feed()) {
		return $title;
	}

	$title .= get_bloginfo('name');
	if (($site_description = get_bloginfo('description', 'display')) && (is_home() || is_front_page())) {
			$title .= " $sep $site_description";
	}

	if ($paged >= 2 || $page >= 2) {
		$title .= " $sep " . sprintf(__('Page %s', 'acitpo'), max($paged, $page));
	}

	return $title;
}
add_filter('wp_title', 'acitpo_wp_title', 10, 2);

/**
 * Prints out comments
 */
function acitpo_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
	case 'pingback':
	case 'trackback': ?>
		<li <?php comment_class(); ?>>
			<p><?php _e( 'Pingback:', 'acitpo' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __('(Edit)', 'acitpo'), '<span class="comment-edit-link">', '</span>' ); ?></p>
		</li>
		<?php break;
	default: global $post; ?>		
		<li <?php comment_class(); ?> id="comment-<?php comment_id(); ?>">
			<header class="comment-meta vcard">
				<?php echo get_avatar( $comment, 48); ?>
			</header>
			<section class="comment-body">
				<?php printf('<cite class="fn comment-author">%1$s%2$s</cite>', get_comment_author_link(), 
				( $comment->user_id === $post->post_author ) ? '<span class="bypostauthor"> ' . __( 'Post author', 'acitpo' ) . '</span>' : '' ); ?>
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation">Your comment is awaiting moderation</p>
				<?php endif; ?>
				<section class="comment-content">
					<?php comment_text(); ?>
				</section>
				<?php printf('<a href="%1$s"><time datetime="%2$s" class="comment-time">%3$s</time></a>',
					esc_url( get_comment_link( $comment->comment_ID ) ),
					get_comment_time( 'c' ),
					sprintf( __( '%1$s at %2$s', 'acitpo' ), get_comment_date(), get_comment_time() )
				); ?>
				<p class="comment-reply">
					<?php edit_comment_link( __( 'Edit', 'acitpo' ), '', ' ' ); ?>
					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'acitpo' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</p>
			</section>
		</li>
		<?php break;
	endswitch;

}
