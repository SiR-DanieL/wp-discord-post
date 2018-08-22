<?php
/**
 * Class Test_WP_Discord_Post_HTTP
 *
 * @author      Nicola Mustone
 * @license     GPL-2.0+
 */

/**
 * Tests the class WP_Discord_Post_HTTP.
 */
class Test_WP_Discord_Post_HTTP extends WP_UnitTestCase {
    /**
     * Instance of WP_Discord_Post_HTTP.
     * @var WP_Discord_Post_HTTP
     */
    public $http;

    /**
     * Set up the WP_Discord_Post_HTTP instance.
     */
    public function setUp() {
        $this->http = new WP_Discord_Post_HTTP( 'post' );
    }

    /**
     * @covers WP_Discord_Post_HTTP::set_username
     * @covers WP_Discord_Post_HTTP::get_username
     */
    public function test_set_username_get_username() {
        $this->http->set_username( 'test' );
        $this->assertEquals( $this->http->get_username(), 'test' );

        $this->http->set_username( '<script>alert();</script>This is a test' );
        $this->assertEquals( $this->http->get_username(), 'This is a test' );
    }

    /**
     * @covers WP_Discord_Post_HTTP::set_avatar
     * @covers WP_Discord_Post_HTTP::get_avatar
     */
    public function test_set_avatar_get_avatar() {
        $this->http->set_avatar( 'https://this.site/image.jpg' );
        $this->assertEquals( $this->http->get_avatar(), 'https://this.site/image.jpg' );

        $this->http->set_avatar( 'http:/image.com/<script>alert()</script>.jpg' );
        $this->assertEquals( $this->http->get_avatar(), 'http:/image.com/scriptalert()/script.jpg' );
    }

    /**
     * @covers WP_Discord_Post_HTTP::set_token
     * @covers WP_Discord_Post_HTTP::get_token
     */
    public function test_set_token_get_token() {
        $this->http->set_token( 'abcdef' );
        $this->assertEquals( $this->http->get_token(), 'abcdef' );

        $this->http->set_token( 'Not a Token with-dashes' );
        $this->assertEquals( $this->http->get_token(), 'notatokenwith-dashes' );
    }

    /**
     * @covers WP_Discord_Post_HTTP::set_webhook_url
     * @covers WP_Discord_Post_HTTP::get_webhook_url
     * @uses   WP_Discord_Post_HTTP::set_context
     */
    public function test_set_webhook_url_get_webhook_url() {
        $this->http->set_webhook_url( 'https://test.domain/ab<script>alert()</script>cdef' );
        $this->assertEquals( $this->http->get_webhook_url(), 'https://test.domain/abscriptalert()/scriptcdef' );

        add_option( 'wp_discord_post_product_webhook_url', 'https://test.domain/product' );
        $this->http->set_context( 'product' );

        $this->http->set_webhook_url();
        $this->assertEquals( $this->http->get_webhook_url(), 'https://test.domain/product' );

        $this->http->set_webhook_url( 'https://test.domain/abcdef' );
        $this->assertEquals( $this->http->get_webhook_url(), 'https://test.domain/abcdef' );
    }

    /**
     * @covers WP_Discord_Post_HTTP::set_context
     * @covers WP_Discord_Post_HTTP::get_context
     * @uses   WP_Discord_Post_HTTP::set_webhook_url
     * @uses   WP_Discord_Post_HTTP::get_webhook_url
     */
    public function test_set_context_get_context() {
        $this->http->set_context( 'test context' );
        $this->assertEquals( $this->http->get_context(), 'testcontext' );

        $this->http->set_context( 'product' );
        $this->assertEquals( $this->http->get_context(), 'product' );

        add_option( 'wp_discord_post_test_webhook_url', 'https://test.domain' );
        $this->http->set_webhook_url( 'http://mydomain.com' );

        $this->http->set_context( 'test' );
        $this->assertEquals( $this->http->get_webhook_url(), 'https://test.domain' );
    }

    /**
     * @covers WP_Discord_Post_HTTP::process
     * @covers WP_Discord_Post_HTTP::_set_post_meta
     * @covers WP_Discord_Post_HTTP::_send_request
     * @uses   WP_Discord_Post_HTTP::process
     * @uses   WP_Discord_Post_HTTP::set_context
     * @uses   WP_Discord_Post_HTTP::set_username
     * @uses   WP_Discord_Post_HTTP::set_avatar
     * @uses   WP_Discord_Post_HTTP::set_token
     * @uses   WP_Discord_Post_HTTP::set_webhook_url
     */
    public function test_process__set_post_meta__send_request() {
        // Test class with proper configuration
        add_option( 'wp_discord_post_bot_username', 'DiscordBot' );
        add_option( 'wp_discord_post_avatar_url', '' );
		add_option( 'wp_discord_post_bot_token', 'MjczMzQyMTU1ODI3NzA3OTA0.DeZ58g.GM8Dr1juWecXAKsBBics2Ss-nQ8' );
		add_option( 'wp_discord_post_webhook_url', 'https://discordapp.com/api/webhooks/384930392303468546/gcWGWP0aemOIQ55OKt40X2xMkuqIbWQOrTA7Bt6mLwYH3kqCr1KzZsXd3MPebrd1gsuH' );

        $this->http->set_context( '' );
        $this->http->set_username( 'DiscordPHPUnitTests' );
		$this->http->set_avatar( '' );
		$this->http->set_token( 'MjczMzQyMTU1ODI3NzA3OTA0.DeZ58g.GM8Dr1juWecXAKsBBics2Ss-nQ8' );
		$this->http->set_webhook_url( 'https://discordapp.com/api/webhooks/384930392303468546/gcWGWP0aemOIQ55OKt40X2xMkuqIbWQOrTA7Bt6mLwYH3kqCr1KzZsXd3MPebrd1gsuH' );

        $content  = 'This is a test with proper configuration.';

        $response = $this->http->process( $content );
        $response = wp_remote_retrieve_response_code( $response );

        $this->assertEquals( $response, 204 );

        // Test with embed.
        $embed = array(
            'title'       => 'Test title',
            'description' => 'Test description',
            'url'         => 'https://google.com',
            'timestamp'   => '2018-08-02T04:03:33+00:00',
            'image'       => 'https://woocommerce.com/wp-content/themes/woo/images/logo-woocommerce@2x.png',
            'author'      => 'Nicola Mustone',
            'fields' => array(
                array(
                    'name' => 'Field 1',
                    'value' => 'Field 1',
                ),
                array(
                    'name' => 'Field 2',
                    'value' => 'Field 2',
                ),
            ),
        );

        $content  = 'This is a test with embed content.';

        $response = $this->http->process( $content, $embed );
        $response = wp_remote_retrieve_response_code( $response );

        $this->assertEquals( $response, 204 );

        // Testing test_process_set_post_meta
        $user_id = $this->factory->user->create();
        $post_id = $this->factory->post->create( array( 'post_author' => $user_id ) );
        $this->assertEquals( get_post_meta( $post_id, '_wp_discord_post_published', true ), 'yes' );
    }
}
