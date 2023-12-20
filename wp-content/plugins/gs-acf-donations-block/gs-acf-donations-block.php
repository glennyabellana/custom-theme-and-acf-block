<?php
/**
 * Plugin Name:     GSquared Donations Block
 * Plugin URI:      https://gsquared.test/
 * Description:     Custom Donations Block Built with ACF Pro
 * Author:          Glenny Abellana
 * Author URI:      https://gsquared.test/
 * Text Domain:     gs-acf-donations-block
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         GS_Acf_Donations_Block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block's assets for the editor and the frontend.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#editor-scripts
 */
function gs_register_styles_and_scripts() {
	wp_register_style( 'gs-donations', plugin_dir_url( __FILE__ ) . 'blocks/donations/assets/style.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'blocks/donations/assets/style.css' ) );
	wp_register_script( 'wp-block-acf-donations', plugin_dir_url( __FILE__ ) . 'blocks/donations/assets/js/scripts.min.js', array( 'acf-input' ), filemtime( plugin_dir_path( __FILE__ ) . 'blocks/donations/assets/js/scripts.min.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'gs_register_styles_and_scripts' );
add_action( 'admin_enqueue_scripts', 'gs_register_styles_and_scripts' );


/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function gs_acf_donations_block() {

	register_block_type( __DIR__ . '/blocks/donations' );
}
add_action( 'init', 'gs_acf_donations_block' );

/**
 * Helper function to import the ACF field group if it doesn't exist.
 *
 * @return void
 */
function gs_import_acf_field_group() {
	if ( function_exists( 'acf_import_field_group' ) ) {

		// Get all json files from the /acf-field-groups directory.
		$files = glob( plugin_dir_path( __FILE__ ) . '/acf-field-groups/*.json' );

		// If no files, bail.
		if ( ! $files ) {
			return;
		}

		// Loop through each file.
		foreach ( $files as $file ) {
			// Grab the JSON file.
			$group = file_get_contents( $file );

			// Decode the JSON.
			$group = json_decode( $group, true );

			// If not empty, import it.
			if ( is_array( $group ) && ! empty( $group ) && ! acf_get_field_group( $group[0]['key'] ) ) {
				acf_import_field_group( $group [0] );
			}
		}
	}
}
register_activation_hook( __FILE__, 'gs_import_acf_field_group' );

/**
 * Calculates the total amount of donations from all 'donation' posts.
 *
 * This function queries all published posts of the 'donation' custom post type
 * and sums up the values of the 'donation_amount' custom field, which is assumed
 * to be associated with each 'donation' post. The function uses a WP_Query to
 * retrieve the posts and then iterates over each post to accumulate the total
 * donation amount.
 *
 * @return float The total amount of donations from all 'donation' posts.
 */
function get_total_donations() {
	$args = array(
		'post_type'      => 'donation', // Replace with your custom post type.
		'post_status'    => 'publish',
		'posts_per_page' => -1, // Retrieve all posts.
	);

	$the_query      = new WP_Query( $args );
	$total_donation = 0;

	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$donation_amount = get_field( 'donation_amount', get_the_ID() ); // Replace with your ACF field name.
			$total_donation += (float) $donation_amount;
		}
		wp_reset_postdata();
	}

	return $total_donation;
}

/**
 * Formats a numerical value into a human-readable string representation.
 *
 * This function takes a numerical value and converts it into a string
 * representing the number in words with appropriate units such as 'thousand',
 * 'million', or 'billion'. The function is capable of handling numbers up to
 * billions. For numbers less than 1000, it returns the number as is.
 *
 * The conversion is rounded to two decimal places for readability and ease of
 * understanding. For example, 1500 becomes '1.5 thousand', and 2500000 becomes
 * '2.5 million'.
 *
 * @param float|int $number The numerical value to be converted into words.
 * @return string|float|int The number in words if it's 1000 or greater, or the original number if less.
 */
function format_number_to_words( $number ) {
	if ( $number >= 1000000000 ) {
		return round( $number / 1000000000, 2 ) . ' billion';
	} elseif ( $number >= 1000000 ) {
		return round( $number / 1000000, 2 ) . ' million';
	} elseif ( $number >= 1000 ) {
		return round( $number / 1000, 2 ) . ' thousand';
	}
	return $number;
}

/**
 * Updates the post title of a donation post type on save.
 *
 * This function is hooked into the 'acf/save_post' action and is triggered after a post is saved.
 * It specifically targets posts of the 'donation' custom post type. The function updates the post's title
 * to 'Donation ' followed by the post's ID and also updates the post's slug accordingly.
 *
 * @param int $post_id The ID of the post being saved.
 */
function update_donation_post_title( $post_id ) {
	// Check if it's the correct post type and a new post.
	if ( 'donation' !== get_post_type( $post_id ) || 'new_post' === $post_id ) {
		return;
	}

	// Update the title.
	$new_title = 'Donation ' . $post_id;
	wp_update_post(
		array(
			'ID'         => $post_id,
			'post_title' => $new_title,
			'post_name'  => sanitize_title( $new_title ), // Update the slug as well.
		)
	);
}
add_action( 'acf/save_post', 'update_donation_post_title', 20 ); // Priority 20 to ensure it runs after ACF saves the post data.

/**
 * Custom post type "Donations"
 */
require __DIR__ . '/post-types/donation.php';
