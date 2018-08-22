<?php
/**
 * Class Test_Functions_General
 *
 * @author      Nicola Mustone
 * @license     GPL-2.0+
 */

/**
 * Tests the functions in functions-general.php
 */
class Test_Functions_General extends WP_UnitTestCase {

	/**
	 * @covers wp_discord_post_is_logging_enabled
	 */
	public function test_wp_discord_post_is_logging_enabled() {
		add_option( 'wp_discord_post_logging', 'yes' );

		$this->assertTrue( wp_discord_post_is_logging_enabled() );

		update_option( 'wp_discord_post_logging', '' );

		$this->assertFalse( wp_discord_post_is_logging_enabled() );
	}
}
