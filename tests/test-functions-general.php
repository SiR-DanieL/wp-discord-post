<?php
/**
 * Class Test_Functions_General
 *
 * @author      Nicola Mustone
 * @license     GPL-2.0+
 * @package     WP Discord Post/Tests
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

	/**
	 * @covers wp_discord_post_is_embed_enabled
	 */
	public function test_wp_discord_post_is_embed_enabled() {
		add_option( 'wp_discord_post_disable_embed', 'yes' );

		$this->assertTrue( wp_discord_post_is_embed_enabled() );

		update_option( 'wp_discord_post_disable_embed', '' );

		$this->assertFalse( wp_discord_post_is_embed_enabled() );
	}
}
