<?php
/**
 * Custom post type and messages functions for 'Donations'.
 *
 * @package GS_Acf_Donations_Block
 */

/**
 * Registers the `donation` post type.
 */
function donation_init() {
	register_post_type(
		'donation',
		array(
			'labels'                => array(
				'name'                  => __( 'Donations', 'gsquared-theme' ),
				'singular_name'         => __( 'Donation', 'gsquared-theme' ),
				'all_items'             => __( 'All Donations', 'gsquared-theme' ),
				'archives'              => __( 'Donation Archives', 'gsquared-theme' ),
				'attributes'            => __( 'Donation Attributes', 'gsquared-theme' ),
				'insert_into_item'      => __( 'Insert into Donation', 'gsquared-theme' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Donation', 'gsquared-theme' ),
				'featured_image'        => _x( 'Featured Image', 'donation', 'gsquared-theme' ),
				'set_featured_image'    => _x( 'Set featured image', 'donation', 'gsquared-theme' ),
				'remove_featured_image' => _x( 'Remove featured image', 'donation', 'gsquared-theme' ),
				'use_featured_image'    => _x( 'Use as featured image', 'donation', 'gsquared-theme' ),
				'filter_items_list'     => __( 'Filter Donations list', 'gsquared-theme' ),
				'items_list_navigation' => __( 'Donations list navigation', 'gsquared-theme' ),
				'items_list'            => __( 'Donations list', 'gsquared-theme' ),
				'new_item'              => __( 'New Donation', 'gsquared-theme' ),
				'add_new'               => __( 'Add New', 'gsquared-theme' ),
				'add_new_item'          => __( 'Add New Donation', 'gsquared-theme' ),
				'edit_item'             => __( 'Edit Donation', 'gsquared-theme' ),
				'view_item'             => __( 'View Donation', 'gsquared-theme' ),
				'view_items'            => __( 'View Donations', 'gsquared-theme' ),
				'search_items'          => __( 'Search Donations', 'gsquared-theme' ),
				'not_found'             => __( 'No Donations found', 'gsquared-theme' ),
				'not_found_in_trash'    => __( 'No Donations found in trash', 'gsquared-theme' ),
				'parent_item_colon'     => __( 'Parent Donation:', 'gsquared-theme' ),
				'menu_name'             => __( 'Donations', 'gsquared-theme' ),
			),
			'public'                => true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => array( 'title', 'custom-fields' ),
			'has_archive'           => true,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-heart',
			'show_in_rest'          => true,
			'rest_base'             => 'donation',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		)
	);
}

add_action( 'init', 'donation_init' );

/**
 * Sets the post updated messages for the `donation` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `donation` post type.
 */
function donation_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['donation'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Donation updated. <a target="_blank" href="%s">View Donation</a>', 'gsquared-theme' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'gsquared-theme' ),
		3  => __( 'Custom field deleted.', 'gsquared-theme' ),
		4  => __( 'Donation updated.', 'gsquared-theme' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Donation restored to revision from %s', 'gsquared-theme' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Donation published. <a href="%s">View Donation</a>', 'gsquared-theme' ), esc_url( $permalink ) ),
		7  => __( 'Donation saved.', 'gsquared-theme' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Donation submitted. <a target="_blank" href="%s">Preview Donation</a>', 'gsquared-theme' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Donation scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Donation</a>', 'gsquared-theme' ), date_i18n( __( 'M j, Y @ G:i', 'gsquared-theme' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Donation draft updated. <a target="_blank" href="%s">Preview Donation</a>', 'gsquared-theme' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}

add_filter( 'post_updated_messages', 'donation_updated_messages' );

/**
 * Sets the bulk post updated messages for the `donation` post type.
 *
 * @param  array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
 *                              keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
 * @param  int[] $bulk_counts   Array of item counts for each message, used to build internationalized strings.
 * @return array Bulk messages for the `donation` post type.
 */
function donation_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
	global $post;

	$bulk_messages['donation'] = array(
		/* translators: %s: Number of Donations. */
		'updated'   => _n( '%s Donation updated.', '%s Donations updated.', $bulk_counts['updated'], 'gsquared-theme' ),
		'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 Donation not updated, somebody is editing it.', 'gsquared-theme' ) :
						/* translators: %s: Number of Donations. */
						_n( '%s Donation not updated, somebody is editing it.', '%s Donations not updated, somebody is editing them.', $bulk_counts['locked'], 'gsquared-theme' ),
		/* translators: %s: Number of Donations. */
		'deleted'   => _n( '%s Donation permanently deleted.', '%s Donations permanently deleted.', $bulk_counts['deleted'], 'gsquared-theme' ),
		/* translators: %s: Number of Donations. */
		'trashed'   => _n( '%s Donation moved to the Trash.', '%s Donations moved to the Trash.', $bulk_counts['trashed'], 'gsquared-theme' ),
		/* translators: %s: Number of Donations. */
		'untrashed' => _n( '%s Donation restored from the Trash.', '%s Donations restored from the Trash.', $bulk_counts['untrashed'], 'gsquared-theme' ),
	);

	return $bulk_messages;
}

add_filter( 'bulk_post_updated_messages', 'donation_bulk_updated_messages', 10, 2 );
