<?php
/**
 * Donations Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it's parent block.
 *
 * @package GS_Acf_Donations_Block
 */

// global $post;
$donation_heading = get_field( 'donation_heading' );
$donation_goal = get_field( 'donation_goal' );
$donation_description = get_field( 'donation_description' );
$donation_amount = get_field( 'donation_amount' );
$block_wrapper_attr = ( ! $is_preview ) ? get_block_wrapper_attributes() : '';
?>
<section <?php echo $block_wrapper_attr; ?>>

		<h2><?php echo esc_html( $donation_heading ); ?></h2>
		<div class="donation-description" id="donation-description-sr"><?php echo wp_kses_post( $donation_description ); ?></div>
		<div class="donation-total-amount" id="donation-total-amount-sr"><?php echo esc_html( $donation_amount ); ?></div>

		<div class="wp-block-acf-donations-heading progress-info">
			<div id="current-donation" class="donation-counter">0</div>
			<h3 class="screen-reader-text" id="current-amount" data-amount="<?php echo esc_attr( get_total_donations() ); ?>"><?php echo esc_html( '$' . number_format( get_total_donations() ) ); ?></h3>
			<h4 id="target-amount" data-amount="<?php echo esc_attr( $donation_goal ); ?>"><?php echo esc_html( 'of $' . format_number_to_words( $donation_goal ) . ' raised' ); ?></h4>
		</div>
		<!-- /.wp-block-acf-carousel-heading -->

		
		<div class="progress-container">
			<div class="progress-bar" style="width: 0%;"></div>
		</div>
		<!-- /.progress-bar -->

		<?php
		if ( function_exists( 'acf_form' ) ) {
			acf_form(
				array(
					'post_id'            => 'new_post',
					'new_post'           => array(
						'post_type'   => 'donation',
						'post_status' => 'publish',
					),
					'submit_value'       => 'Donate Now',
					'fields'             => array( 'donation_amount', 'select_payment_method', 'personal_info' ), // List all fields except the one you want to exclude
					'html_before_fields' => '<input type="hidden" name="_post_title" value="donation">',
				)
			);
		}
		?>
</section>


