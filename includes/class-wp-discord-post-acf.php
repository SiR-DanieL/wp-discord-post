<?php
/**
 * WP Discord Advanced Custom Fields
 *
 * @author      Nicola Mustone
 * @license     GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class of the compatibility with ACF.
 */
class WP_Discord_Post_ACF {
	/**
	 * Adds the required hooks.
	 */
	public function __construct() {
		add_action( 'acf/create_field_options', array( $this, 'add_field_enable_option' ) );
	}

	public function add_field_enable_option( $field ) {
		$fake_name = $field['name'];

		?>
		<tr class="wp_discord_post_enabled">
			<td class="label">
				<label><?php esc_html_e( 'Send to Discord', 'wp-discord-post' ); ?></label>
				<p class="description"><?php esc_html_e( 'Should the value of this field appear in the Discord embed content?', 'wp-discord-post' ); ?></p>
			</td>
			<td>
				<?php
				do_action( 'acf/create_field', array(
					'type'	=>	'radio',
					'name'	=>	'fields[' .$fake_name . '][wp_discord_post]',
					'value'	=>	$field['wp_discord_post'],
					'choices'	=>	array(
						1	=>	esc_html__( 'Yes', 'acf' ),
						0	=>	esc_html__( 'No', 'acf' ),
					),
					'layout'	=>	'horizontal',
				) );
				?>
			</td>
		</tr>
		<?php
	}


}

return new WP_Discord_Post_ACF();
