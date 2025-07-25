<?php

/**
 * bbPress Theme Compatibility
 *
 * @package bbPress
 * @subpackage Core
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/** Theme Compat **************************************************************/

/**
 * What follows is an attempt at intercepting the natural page load process
 * to replace the_content() with the appropriate bbPress content.
 *
 * To do this, bbPress does several direct manipulations of global variables
 * and forces them to do what they are not supposed to be doing.
 *
 * Don't try anything you're about to witness here, at home. Ever.
 */

/** Base Class ****************************************************************/

/**
 * Theme Compatibility base class
 *
 * This is only intended to be extended, and is included here as a basic guide
 * for future Template Packs to use. @link bbp_setup_theme_compat()
 *
 * @since 2.0.0 bbPress (r3506)
 */
class BBP_Theme_Compat {

	/**
	 * Should be like:
	 *
	 * array(
	 *     'id'      => ID of the theme (should be unique)
	 *     'name'    => Name of the theme (should match style.css)
	 *     'version' => Theme version for cache busting scripts and styling
	 *     'dir'     => Path to theme
	 *     'url'     => URL to theme
	 * );
	 * @var array
	 */
	private $_data = array();

	/**
	 * Pass the $properties to the object on creation.
	 *
	 * @since 2.1.0 bbPress (r3926)
	 *
	 * @param array $properties
	 */
	public function __construct( Array $properties = array() ) {
		$this->_data = $properties;
	}

	/**
	 * Set a theme's property.
	 *
	 * @since 2.1.0 bbPress (r3926)
	 *
	 * @param string $property
	 * @param mixed $value
	 * @return mixed
	 */
	public function __set( $property, $value ) {
		return $this->_data[ $property ] = $value;
	}

	/**
	 * Get a theme's property.
	 *
	 * @since 2.1.0 bbPress (r3926)
	 *
	 * @param string $property
	 * @param mixed $value
	 * @return mixed
	 */
	public function __get( $property ) {
		return array_key_exists( $property, $this->_data )
			? $this->_data[ $property ]
			: '';
	}

	/**
	 * Return the template directory.
	 *
	 * @since 2.6.0 bbPress (r6548)
	 *
	 * @return string
	 */
	public function get_dir() {
		return $this->dir;
	}
}

/** Functions *****************************************************************/

/**
 * Setup the active template pack and register it's directory in the stack.
 *
 * @since 2.0.0 bbPress (r3311)
 *
 * @param BBP_Theme_Compat $theme
 */
function bbp_setup_theme_compat( $theme = 'default' ) {
	$bbp = bbpress();

	// Bail if something already has this under control
	if ( ! empty( $bbp->theme_compat->theme ) ) {
		return;
	}

	// Fallback for empty theme
	if ( empty( $theme ) ) {
		$theme = 'default';
	}

	// If the theme is registered, use it and add it to the stack
	if ( isset( $bbp->theme_compat->packages[ $theme ] ) ) {
		$bbp->theme_compat->theme = $bbp->theme_compat->packages[ $theme ];

		// Setup the template stack for the active template pack
		bbp_register_template_stack( array( $bbp->theme_compat->theme, 'get_dir' ) );
	}
}

/**
 * Get the current template pack package.
 *
 * @since 2.6.0 bbPress (r6548)
 *
 * @return BBP_Theme_Compat
 */
function bbp_get_current_template_pack() {
	$bbp = bbpress();

	// Theme was not setup, so fallback to an empty object
	if ( empty( $bbp->theme_compat->theme ) ) {
		$bbp->theme_compat->theme = new BBP_Theme_Compat();
	}

	// Filter & return
	return apply_filters( 'bbp_get_current_template_pack', $bbp->theme_compat->theme );
}

/**
 * Gets the id of the bbPress compatible theme used, in the event the
 * currently active WordPress theme does not explicitly support bbPress.
 * This can be filtered or set manually. Tricky theme authors can override the
 * default and include their own bbPress compatibility layers for their themes.
 *
 * @since 2.0.0 bbPress (r3506)
 *
 * @return string
 */
function bbp_get_theme_compat_id() {

	// Filter & return
	return apply_filters( 'bbp_get_theme_compat_id', bbp_get_current_template_pack()->id );
}

/**
 * Gets the name of the bbPress compatible theme used, in the event the
 * currently active WordPress theme does not explicitly support bbPress.
 * This can be filtered or set manually. Tricky theme authors can override the
 * default and include their own bbPress compatibility layers for their themes.
 *
 * @since 2.0.0 bbPress (r3506)
 *
 * @return string
 */
function bbp_get_theme_compat_name() {

	// Filter & return
	return apply_filters( 'bbp_get_theme_compat_name', bbp_get_current_template_pack()->name );
}

/**
 * Gets the version of the bbPress compatible theme used, in the event the
 * currently active WordPress theme does not explicitly support bbPress.
 * This can be filtered or set manually. Tricky theme authors can override the
 * default and include their own bbPress compatibility layers for their themes.
 *
 * @since 2.0.0 bbPress (r3506)
 *
 * @return string
 */
function bbp_get_theme_compat_version() {

	// Filter & return
	return apply_filters( 'bbp_get_theme_compat_version', bbp_get_current_template_pack()->version );
}

/**
 * Gets the bbPress compatible theme used in the event the currently active
 * WordPress theme does not explicitly support bbPress. This can be filtered,
 * or set manually. Tricky theme authors can override the default and include
 * their own bbPress compatibility layers for their themes.
 *
 * @since 2.0.0 bbPress (r3032)
 *
 * @return string
 */
function bbp_get_theme_compat_dir() {

	// Filter & return
	return apply_filters( 'bbp_get_theme_compat_dir', bbp_get_current_template_pack()->dir );
}

/**
 * Gets the bbPress compatible theme used in the event the currently active
 * WordPress theme does not explicitly support bbPress. This can be filtered,
 * or set manually. Tricky theme authors can override the default and include
 * their own bbPress compatibility layers for their themes.
 *
 * @since 2.0.0 bbPress (r3032)
 *
 * @return string
 */
function bbp_get_theme_compat_url() {

	// Filter & return
	return apply_filters( 'bbp_get_theme_compat_url', bbp_get_current_template_pack()->url );
}

/**
 * Gets true/false if page is currently inside theme compatibility
 *
 * @since 2.0.0 bbPress (r3265)
 *
 * @return bool
 */
function bbp_is_theme_compat_active() {
	$bbp = bbpress();

	if ( empty( $bbp->theme_compat->active ) ) {
		return false;
	}

	return $bbp->theme_compat->active;
}

/**
 * Sets true/false if page is currently inside theme compatibility
 *
 * @since 2.0.0 bbPress (r3265)
 *
 * @param bool $set
 * @return bool
 */
function bbp_set_theme_compat_active( $set = true ) {
	bbpress()->theme_compat->active = $set;

	return (bool) bbpress()->theme_compat->active;
}

/**
 * Set the theme compat templates global
 *
 * Stash possible template files for the current query. Useful if plugins want
 * to override them, or see what files are being scanned for inclusion.
 *
 * @since 2.0.0 bbPress (r3311)
 */
function bbp_set_theme_compat_templates( $templates = array() ) {
	bbpress()->theme_compat->templates = $templates;

	return bbpress()->theme_compat->templates;
}

/**
 * Set the theme compat template global
 *
 * Stash the template file for the current query. Useful if plugins want
 * to override it, or see what file is being included.
 *
 * @since 2.0.0 bbPress (r3311)
 */
function bbp_set_theme_compat_template( $template = '' ) {
	bbpress()->theme_compat->template = $template;

	return bbpress()->theme_compat->template;
}

/**
 * Set the theme compat original_template global
 *
 * Stash the original template file for the current query. Useful for checking
 * if bbPress was able to find a more appropriate template.
 *
 * @since 2.1.0 bbPress (r3926)
 */
function bbp_set_theme_compat_original_template( $template = '' ) {
	bbpress()->theme_compat->original_template = $template;

	return bbpress()->theme_compat->original_template;
}

/**
 * Is a template the original_template global
 *
 * Stash the original template file for the current query. Useful for checking
 * if bbPress was able to find a more appropriate template.
 *
 * @since 2.1.0 bbPress (r3926)
 */
function bbp_is_theme_compat_original_template( $template = '' ) {
	$bbp = bbpress();

	// Bail if no original template
	if ( empty( $bbp->theme_compat->original_template ) ) {
		return false;
	}

	return (bool) ( $bbp->theme_compat->original_template === $template );
}

/**
 * Register a new bbPress theme package to the active theme packages array
 *
 * @since 2.1.0 bbPress (r3829)
 *
 * @param array $theme
 */
function bbp_register_theme_package( $theme = array(), $override = true ) {

	// Create new BBP_Theme_Compat object from the $theme array
	if ( is_array( $theme ) ) {
		$theme = new BBP_Theme_Compat( $theme );
	}

	// Bail if $theme isn't a proper object
	if ( ! is_a( $theme, 'BBP_Theme_Compat' ) ) {
		return;
	}

	// Load up bbPress
	$bbp = bbpress();

	// Only override if the flag is set and not previously registered
	if ( empty( $bbp->theme_compat->packages[ $theme->id ] ) || ( true === $override ) ) {
		$bbp->theme_compat->packages[ $theme->id ] = $theme;
	}
}

/**
 * This fun little function fills up some WordPress globals with dummy data to
 * stop your average page template from complaining about it missing.
 *
 * @since 2.0.0 bbPress (r3108)
 *
 * @global WP_Query $wp_query
 * @global object $post
 * @param array $args
 */
function bbp_theme_compat_reset_post( $args = array() ) {
	global $wp_query, $post;

	// Switch defaults if post is set
	if ( isset( $wp_query->post ) ) {

		// Use primarily Post attributes
		$defaults = array(
			'ID'                    => $wp_query->post->ID,
			'post_status'           => $wp_query->post->post_status,
			'post_author'           => $wp_query->post->post_author,
			'post_parent'           => $wp_query->post->post_parent,
			'post_type'             => $wp_query->post->post_type,
			'post_date'             => $wp_query->post->post_date,
			'post_date_gmt'         => $wp_query->post->post_date_gmt,
			'post_modified'         => $wp_query->post->post_modified,
			'post_modified_gmt'     => $wp_query->post->post_modified_gmt,
			'post_content'          => $wp_query->post->post_content,
			'post_title'            => $wp_query->post->post_title,
			'post_excerpt'          => $wp_query->post->post_excerpt,
			'post_content_filtered' => $wp_query->post->post_content_filtered,
			'post_mime_type'        => $wp_query->post->post_mime_type,
			'post_password'         => $wp_query->post->post_password,
			'post_name'             => $wp_query->post->post_name,
			'guid'                  => $wp_query->post->guid,
			'menu_order'            => $wp_query->post->menu_order,
			'pinged'                => $wp_query->post->pinged,
			'to_ping'               => $wp_query->post->to_ping,
			'ping_status'           => $wp_query->post->ping_status,
			'comment_status'        => $wp_query->post->comment_status,
			'comment_count'         => $wp_query->post->comment_count,
			'filter'                => $wp_query->post->filter,

			'is_404'                => false,
			'is_page'               => false,
			'is_single'             => false,
			'is_archive'            => false,
			'is_tax'                => false
		);
	} else {

		// Get the default zero date value a single time
		$zero_date = bbp_get_empty_datetime();

		// Use primarily empty attributes
		$defaults = array(
			'ID'                    => -9999,
			'post_status'           => bbp_get_public_status_id(),
			'post_author'           => 0,
			'post_parent'           => 0,
			'post_type'             => 'page',
			'post_date'             => $zero_date,
			'post_date_gmt'         => $zero_date,
			'post_modified'         => $zero_date,
			'post_modified_gmt'     => $zero_date,
			'post_content'          => '',
			'post_title'            => '',
			'post_excerpt'          => '',
			'post_content_filtered' => '',
			'post_mime_type'        => '',
			'post_password'         => '',
			'post_name'             => '',
			'guid'                  => '',
			'menu_order'            => 0,
			'pinged'                => '',
			'to_ping'               => '',
			'ping_status'           => '',
			'comment_status'        => 'closed',
			'comment_count'         => 0,
			'filter'                => 'raw',

			'is_404'                => false,
			'is_page'               => false,
			'is_single'             => false,
			'is_archive'            => false,
			'is_tax'                => false
		);
	}

	// Parse & filter
	$dummy = bbp_parse_args( $args, $defaults, 'theme_compat_reset_post' );

	// Bail if dummy post is empty
	if ( empty( $dummy ) ) {
		return;
	}

	// Set the $post global
	$post = new WP_Post( (object) $dummy );

	// Copy the new post global into the main $wp_query
	$wp_query->post       = $post;
	$wp_query->posts      = array( $post );

	// Prevent comments form from appearing
	$wp_query->post_count = 1;
	$wp_query->is_404     = $dummy['is_404'];
	$wp_query->is_page    = $dummy['is_page'];
	$wp_query->is_single  = $dummy['is_single'];
	$wp_query->is_archive = $dummy['is_archive'];
	$wp_query->is_tax     = $dummy['is_tax'];

	// Reset is_singular based on page/single args
	// https://bbpress.trac.wordpress.org/ticket/2545
	$wp_query->is_singular = $wp_query->is_single;

	// Clean up the dummy post
	unset( $dummy );

	// If we are resetting a post, we are in theme compat
	bbp_set_theme_compat_active( true );
}

/**
 * Reset main query vars and filter 'the_content' to output a bbPress
 * template part as needed.
 *
 * @since 2.0.0 bbPress (r3032)
 *
 * @param string $template
 */
function bbp_template_include_theme_compat( $template = '' ) {

	/**
	 * Bail if a root template was already found. This prevents unintended
	 * recursive filtering of 'the_content'.
	 *
	 * @link https://bbpress.trac.wordpress.org/ticket/2429
	 */
	if ( bbp_is_template_included() ) {
		return $template;
	}

	/**
	 * If BuddyPress is activated at a network level, the action order is
	 * reversed, which causes the template integration to fail. If we're looking
	 * at a BuddyPress page here, bail to prevent the extra processing.
	 *
	 * This is a bit more brute-force than is probably necessary, but gets the
	 * job done while we work towards something more elegant.
	 */
	if ( function_exists( 'is_buddypress' ) && is_buddypress() ) {
		return $template;
	}

	// Define local variable(s)
	$bbp_shortcodes = bbpress()->shortcodes;

	// Bail if shortcodes are unset somehow
	if ( ! is_a( $bbp_shortcodes, 'BBP_Shortcodes' ) ) {
		return $template;
	}

	/** Users *************************************************************/

	if ( bbp_is_single_user_edit() || bbp_is_single_user() ) {

		// Reset post
		bbp_theme_compat_reset_post( array(
			'ID'             => 0,
			'post_author'    => 0,
			'post_date'      => bbp_get_empty_datetime(),
			'post_content'   => bbp_buffer_template_part( 'content', 'single-user', false ),
			'post_type'      => '',
			'post_title'     => bbp_get_displayed_user_field( 'display_name' ),
			'post_status'    => bbp_get_public_status_id(),
			'is_archive'     => false,
			'comment_status' => 'closed'
		) );

	/** Forums ************************************************************/

	// Forum archive
	} elseif ( bbp_is_forum_archive() ) {

		// Page exists where this archive should be
		$page = bbp_get_page_by_path( bbp_get_root_slug() );

		// Should we replace the content...
		if ( empty( $page->post_content ) ) {

			// Use the topics archive
			if ( 'topics' === bbp_show_on_root() ) {
				$new_content = $bbp_shortcodes->display_topic_index();

			// No page so show the archive
			} else {
				$new_content = $bbp_shortcodes->display_forum_index();
			}

		// ...or use the existing page content?
		} else {
			$new_content = apply_filters( 'the_content', $page->post_content );
		}

		// Should we replace the title...
		if ( empty( $page->post_title ) ) {

			// Use the topics archive
			if ( 'topics' === bbp_show_on_root() ) {
				$new_title = bbp_get_topic_archive_title();

			// No page so show the archive
			} else {
				$new_title = bbp_get_forum_archive_title();
			}

		// ...or use the existing page title?
		} else {
			$new_title = apply_filters( 'the_title', $page->post_title, $page->ID );
		}

		// Reset post
		bbp_theme_compat_reset_post( array(
			'ID'             => ! empty( $page->ID ) ? $page->ID : 0,
			'post_title'     => $new_title,
			'post_author'    => 0,
			'post_date'      => bbp_get_empty_datetime(),
			'post_content'   => $new_content,
			'post_type'      => bbp_get_forum_post_type(),
			'post_status'    => bbp_get_public_status_id(),
			'is_archive'     => true,
			'comment_status' => 'closed'
		) );

	// Single Forum
	} elseif ( bbp_is_forum_edit() ) {

		// Reset post
		bbp_theme_compat_reset_post( array(
			'ID'             => bbp_get_forum_id(),
			'post_title'     => bbp_get_forum_title(),
			'post_author'    => bbp_get_forum_author_id(),
			'post_date'      => bbp_get_empty_datetime(),
			'post_content'   => $bbp_shortcodes->display_forum_form(),
			'post_type'      => bbp_get_forum_post_type(),
			'post_status'    => bbp_get_forum_visibility(),
			'is_single'      => true,
			'comment_status' => 'closed'
		) );

		// Lock the forum from other edits
		bbp_set_post_lock( bbp_get_forum_id() );

	} elseif ( bbp_is_single_forum() ) {

		// Reset post
		bbp_theme_compat_reset_post( array(
			'ID'             => bbp_get_forum_id(),
			'post_title'     => bbp_get_forum_title(),
			'post_author'    => bbp_get_forum_author_id(),
			'post_date'      => bbp_get_empty_datetime(),
			'post_content'   => $bbp_shortcodes->display_forum( array( 'id' => bbp_get_forum_id() ) ),
			'post_type'      => bbp_get_forum_post_type(),
			'post_status'    => bbp_get_forum_visibility(),
			'is_single'      => true,
			'comment_status' => 'closed'
		) );

	/** Topics ************************************************************/

	// Topic archive
	} elseif ( bbp_is_topic_archive() ) {

		// Page exists where this archive should be
		$page = bbp_get_page_by_path( bbp_get_topic_archive_slug() );

		// Should we replace the content...
		if ( empty( $page->post_content ) ) {
			$new_content = $bbp_shortcodes->display_topic_index();

		// ...or use the existing page content?
		} else {
			$new_content = apply_filters( 'the_content', $page->post_content );
		}

		// Should we replace the title...
		if ( empty( $page->post_title ) ) {
			$new_title = bbp_get_topic_archive_title();

		// ...or use the existing page title?
		} else {
			$new_title = apply_filters( 'the_title',   $page->post_title   );
		}

		// Reset post
		bbp_theme_compat_reset_post( array(
			'ID'             => ! empty( $page->ID ) ? $page->ID : 0,
			'post_title'     => bbp_get_topic_archive_title(),
			'post_author'    => 0,
			'post_date'      => bbp_get_empty_datetime(),
			'post_content'   => $new_content,
			'post_type'      => bbp_get_topic_post_type(),
			'post_status'    => bbp_get_public_status_id(),
			'is_archive'     => true,
			'comment_status' => 'closed'
		) );

	// Single Topic
	} elseif ( bbp_is_topic_edit() || bbp_is_single_topic() ) {

		// Split
		if ( bbp_is_topic_split() ) {
			$new_content = bbp_buffer_template_part( 'form', 'topic-split', false );

		// Merge
		} elseif ( bbp_is_topic_merge() ) {
			$new_content = bbp_buffer_template_part( 'form', 'topic-merge', false );

		// Edit
		} elseif ( bbp_is_topic_edit() ) {
			$new_content = $bbp_shortcodes->display_topic_form();

			// Lock the topic from other edits
			bbp_set_post_lock( bbp_get_topic_id() );

		// Single
		} else {
			$new_content = $bbp_shortcodes->display_topic( array( 'id' => bbp_get_topic_id() ) );
		}

		// Reset post
		bbp_theme_compat_reset_post( array(
			'ID'             => bbp_get_topic_id(),
			'post_title'     => bbp_get_topic_title(),
			'post_author'    => bbp_get_topic_author_id(),
			'post_date'      => bbp_get_empty_datetime(),
			'post_content'   => $new_content,
			'post_type'      => bbp_get_topic_post_type(),
			'post_status'    => bbp_get_topic_status(),
			'is_single'      => true,
			'comment_status' => 'closed'
		) );

	/** Replies ***********************************************************/

	// Reply archive
	} elseif ( is_post_type_archive( bbp_get_reply_post_type() ) ) {

		// Reset post
		bbp_theme_compat_reset_post( array(
			'ID'             => 0,
			'post_title'     => esc_html__( 'Replies', 'bbpress' ),
			'post_author'    => 0,
			'post_date'      => bbp_get_empty_datetime(),
			'post_content'   => '',
			'post_type'      => bbp_get_reply_post_type(),
			'post_status'    => bbp_get_public_status_id(),
			'is_archive'     => true,
			'comment_status' => 'closed'
		) );

	// Single Reply
	} elseif ( bbp_is_reply_edit() || bbp_is_single_reply() ) {

		// Move
		if ( bbp_is_reply_move() ) {
			$new_content = bbp_buffer_template_part( 'form', 'reply-move', false );

		// Edit
		} elseif ( bbp_is_reply_edit() ) {
			$new_content = $bbp_shortcodes->display_reply_form();

			// Lock the reply from other edits
			bbp_set_post_lock( bbp_get_reply_id() );

		// Single
		} else {
			$new_content = $bbp_shortcodes->display_reply( array( 'id' => get_the_ID() ) );
		}

		// Reset post
		bbp_theme_compat_reset_post( array(
			'ID'             => bbp_get_reply_id(),
			'post_title'     => bbp_get_reply_title(),
			'post_author'    => bbp_get_reply_author_id(),
			'post_date'      => bbp_get_empty_datetime(),
			'post_content'   => $new_content,
			'post_type'      => bbp_get_reply_post_type(),
			'post_status'    => bbp_get_reply_status(),
			'is_single'      => true,
			'comment_status' => 'closed'
		) );

	/** Views *************************************************************/

	} elseif ( bbp_is_single_view() ) {

		// Reset post
		bbp_theme_compat_reset_post( array(
			'ID'             => 0,
			'post_title'     => bbp_get_view_title(),
			'post_author'    => 0,
			'post_date'      => bbp_get_empty_datetime(),
			'post_content'   => $bbp_shortcodes->display_view( array( 'id' => get_query_var( bbp_get_view_rewrite_id() ) ) ),
			'post_type'      => '',
			'post_status'    => bbp_get_public_status_id(),
			'is_archive'     => true,
			'comment_status' => 'closed'
		) );

	/** Search ************************************************************/

	} elseif ( bbp_is_search() ) {

		// Reset post
		bbp_theme_compat_reset_post( array(
			'ID'             => 0,
			'post_title'     => bbp_get_search_title(),
			'post_author'    => 0,
			'post_date'      => bbp_get_empty_datetime(),
			'post_content'   => $bbp_shortcodes->display_search( array( 'search' => get_query_var( bbp_get_search_rewrite_id() ) ) ),
			'post_type'      => '',
			'post_status'    => bbp_get_public_status_id(),
			'is_archive'     => true,
			'comment_status' => 'closed'
		) );

	/** Topic Tags ********************************************************/

	// Topic Tag Edit
	} elseif ( bbp_is_topic_tag_edit() || bbp_is_topic_tag() ) {

		// Stash the current term in a new var
		set_query_var( 'bbp_topic_tag', get_query_var( 'term' ) );

		// Show topics of tag
		if ( bbp_is_topic_tag() ) {
			$new_content = $bbp_shortcodes->display_topics_of_tag( array( 'id' => bbp_get_topic_tag_id() ) );

		// Edit topic tag
		} elseif ( bbp_is_topic_tag_edit() ) {
			$new_content = $bbp_shortcodes->display_topic_tag_form();
		}

		// Reset the post with our new title
		bbp_theme_compat_reset_post( array(
			'ID'             => 0,
			'post_author'    => 0,
			'post_date'      => bbp_get_empty_datetime(),
			'post_content'   => $new_content,
			'post_type'      => '',
			'post_title'     => sprintf( esc_html__( 'Topic Tag: %s', 'bbpress' ), bbp_get_topic_tag_name() ),
			'post_status'    => bbp_get_public_status_id(),
			'is_tax'         => true,
			'is_archive'     => true,
			'comment_status' => 'closed'
		) );
	}

	/**
	 * Bail if the template already matches a bbPress template. This includes
	 * archive-* and single-* WordPress post_type matches (allowing
	 * themes to use the expected format) as well as all bbPress-specific
	 * template files for users, topics, forums, etc...
	 *
	 * We do this after the above checks to prevent incorrect 404 body classes
	 * and header statuses, as well as to set the post global as needed.
	 *
	 * @see https://bbpress.trac.wordpress.org/ticket/1478/
	 */
	if ( bbp_is_template_included() ) {
		return $template;

	/**
	 * If we are relying on the built-in theme compatibility API to load
	 * the proper content, we need to intercept the_content, replace the
	 * output, and display ours instead.
	 *
	 * To do this, we first remove all filters from 'the_content' and hook
	 * our own function into it, which runs a series of checks to determine
	 * the context, and then uses the built in shortcodes to output the
	 * correct results from inside an output buffer.
	 *
	 * Uses bbp_get_theme_compat_templates() to provide fall-backs that
	 * should be coded without superfluous mark-up and logic (prev/next
	 * navigation, comments, date/time, etc...)
	 *
	 * Hook into the 'bbp_get_bbpress_template' to override the array of
	 * possible templates, or 'bbp_bbpress_template' to override the result.
	 */
	} elseif ( bbp_is_theme_compat_active() ) {
		bbp_remove_all_filters( 'the_content' );

		$template = bbp_get_theme_compat_templates();
	}

	// Filter & return
	return apply_filters( 'bbp_template_include_theme_compat', $template );
}

/** Helpers *******************************************************************/

/**
 * Remove the canonical redirect to allow pretty pagination
 *
 * @since 2.0.0 bbPress (r2628)
 *
 * @param string $redirect_url Redirect url
 *
 * @return bool|string False if it's a topic/forum and their first page,
 *                      otherwise the redirect url
 */
function bbp_redirect_canonical( $redirect_url ) {

	// Canonical is for the beautiful
	if ( bbp_use_pretty_urls() ) {

		// If viewing beyond page 1 of several
		if ( 1 < bbp_get_paged() ) {

			// Only on single topics...
			if ( bbp_is_single_topic() ) {
				$redirect_url = false;

			// ...and single forums...
			} elseif ( bbp_is_single_forum() ) {
				$redirect_url = false;

			// ...and single replies...
			} elseif ( bbp_is_single_reply() ) {
				$redirect_url = false;

			// ...and any single anything else...
			//
			// @todo - Find a more accurate way to disable paged canonicals for
			//          paged shortcode usage within other posts.
			} elseif ( is_page() || is_singular() ) {
				$redirect_url = false;
			}

		// If editing a topic
		} elseif ( bbp_is_topic_edit() ) {
			$redirect_url = false;

		// If editing a reply
		} elseif ( bbp_is_reply_edit() ) {
			$redirect_url = false;
		}
	}

	return $redirect_url;
}

/** Filters *******************************************************************/

/**
 * Removes all filters from a WordPress filter, and stashes them in the $bbp
 * global in the event they need to be restored later.
 *
 * @since 2.0.0 bbPress (r3251)
 *
 * @global WP_filter $wp_filter
 * @global array $merged_filters
 * @param string $tag
 * @param int $priority
 * @return bool
 */
function bbp_remove_all_filters( $tag, $priority = false ) {
	global $wp_filter, $merged_filters;

	$bbp = bbpress();

	// Filters exist
	if ( isset( $wp_filter[ $tag ] ) ) {

		// Filters exist in this priority
		if ( ! empty( $priority ) && isset( $wp_filter[ $tag ][ $priority ] ) ) {

			// Store filters in a backup
			$bbp->filters->wp_filter[ $tag ][ $priority ] = $wp_filter[ $tag ][ $priority ];

			// Unset the filters
			unset( $wp_filter[ $tag ][ $priority ] );

		// Priority is empty
		} else {

			// Store filters in a backup
			$bbp->filters->wp_filter[ $tag ] = $wp_filter[ $tag ];

			// Unset the filters
			unset( $wp_filter[ $tag ] );
		}
	}

	// Check merged filters
	if ( isset( $merged_filters[ $tag ] ) ) {

		// Store filters in a backup
		$bbp->filters->merged_filters[ $tag ] = $merged_filters[ $tag ];

		// Unset the filters
		unset( $merged_filters[ $tag ] );
	}

	return true;
}

/**
 * Restores filters from the $bbp global that were removed using
 * bbp_remove_all_filters()
 *
 * @since 2.0.0 bbPress (r3251)
 *
 * @global WP_filter $wp_filter
 * @global array $merged_filters
 * @param string $tag
 * @param int $priority
 * @return bool
 */
function bbp_restore_all_filters( $tag, $priority = false ) {
	global $wp_filter, $merged_filters;

	$bbp = bbpress();

	// Filters exist
	if ( isset( $bbp->filters->wp_filter[ $tag ] ) ) {

		// Filters exist in this priority
		if ( ! empty( $priority ) && isset( $bbp->filters->wp_filter[ $tag ][ $priority  ] ) ) {

			// Store filters in a backup
			$wp_filter[ $tag ][ $priority ] = $bbp->filters->wp_filter[ $tag ][ $priority ];

			// Unset the filters
			unset( $bbp->filters->wp_filter[ $tag ][ $priority ] );

		// Priority is empty
		} else {

			// Store filters in a backup
			$wp_filter[ $tag ] = $bbp->filters->wp_filter[ $tag ];

			// Unset the filters
			unset( $bbp->filters->wp_filter[ $tag ] );
		}
	}

	// Check merged filters
	if ( isset( $bbp->filters->merged_filters[ $tag ] ) ) {

		// Store filters in a backup
		$merged_filters[ $tag ] = $bbp->filters->merged_filters[ $tag ];

		// Unset the filters
		unset( $bbp->filters->merged_filters[ $tag ] );
	}

	return true;
}

/**
 * Force comments_status to 'closed' for bbPress post types
 *
 * @since 2.1.0 bbPress (r3589)
 *
 * @param bool $open True if open, false if closed
 * @param int $post_id ID of the post to check
 * @return bool True if open, false if closed
 */
function bbp_force_comment_status( $open = false, $post_id = 0 ) {

	// Default return value is what is passed in $open
	$retval = (bool) $open;

	// Get the post type of the post ID
	$post_type = get_post_type( $post_id );

	// Only force for bbPress post types
	if ( in_array( $post_type, bbp_get_post_types(), true ) ) {
		$retval = false;
	}

	// Filter & return
	return (bool) apply_filters( 'bbp_force_comment_status', $retval, $open, $post_id, $post_type );
}

/**
 * Remove "prev" and "next" relational links from <head> on bbPress pages.
 *
 * WordPress automatically generates these relational links to the current
 * page, but bbPress does not use these links, nor would they work the same.
 *
 * In this function, we remove these links when on a bbPress page. This also
 * prevents additional, unnecessary queries from running.
 *
 * @since 2.6.0 bbPress (r7071)
 */
function bbp_remove_adjacent_posts() {

	// Bail if not a bbPress page
	if ( ! is_bbpress() ) {
		return;
	}

	// Remove the WordPress core action for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
}
