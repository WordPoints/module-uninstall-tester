<?php

/**
 * WordPoints module uninstall test case.
 *
 * @package WordPoints_Module_Uninstall_Tester
 * @since 0.1.0
 */

/**
 * Test WordPoints module installation and uninstallation.
 *
 * @since 0.1.0
 */
abstract class WordPoints_Module_Uninstall_UnitTestCase extends WP_Plugin_Uninstall_UnitTestCase {

	//
	// Protected properties.
	//

	/**
	 * The basename path to the main module file.
	 *
	 * @since 0.1.0
	 * @since 0.3.0 No longer expected to be a full path.
	 *
	 * @type string $module_file
	 */
	protected $module_file;

	/**
	 * Whether WordPoints should be network activated during the tests.
	 *
	 * Will default to the value of the `WORDPOINTS_NETWORK_ACTIVE` environment
	 * variable.
	 *
	 * @since 0.4.0
	 *
	 * @var bool
	 */
	protected $wordpoints_network_active;

	/**
	 * @since 0.4.0
	 */
	protected $plugin_file = 'wordpoints/wordpoints.php';

	//
	// Methods.
	//

	/**
	 * @since 0.3.0
	 */
	public function setUp() {

		if ( ! isset( $this->wordpoints_network_active ) && $this->network_active ) {
			$this->wordpoints_network_active = true;
		}

		if ( ! isset( $this->wordpoints_network_active ) ) {
			$this->wordpoints_network_active = (bool) getenv(
				'WORDPOINTS_NETWORK_ACTIVE'
			);
		}

		parent::setUp();
	}

	/**
	 * Run the module's install script.
	 *
	 * Called by the setUp() method.
	 *
	 * Installation is run separately, so the module is never actually loaded in this
	 * process. This provides more realistic testing of the uninstall process, since
	 * it is run while the module is inactive, just like in "real life".
	 *
	 * @since 0.1.0
	 */
	protected function install() {

		system(
			WP_PHP_BINARY
			. ' ' . escapeshellarg( dirname( dirname( __FILE__ ) ) . '/bin/install-module.php' )
			. ' ' . escapeshellarg( $this->module_file )
			. ' ' . escapeshellarg( $this->locate_wp_tests_config() )
			. ' ' . (int) is_multisite()
			. ' ' . (int) $this->network_active
			. ' ' . escapeshellarg( WP_PLUGIN_UNINSTALL_TESTER_DIR )
			. ' ' . escapeshellarg( $this->plugin_file )
			. ' ' . (int) $this->wordpoints_network_active
			, $exit_code
		);

		if ( 0 !== $exit_code ) {
			$this->fail( 'Remote module installation failed with exit code ' . $exit_code );
		}
	}

	/**
	 * Simulate the usage of the plugin, by including a simulation file remotely.
	 *
	 * Called by uninstall() to simulate the usage of the plugin. This is useful to
	 * help make sure that the plugin really uninstalls itself completely, by undoing
	 * everything that might be done while it is active, not just reversing the un-
	 * install routine (though in some cases that may be all that is necessary).
	 *
	 * @since 0.2.0
	 */
	public function simulate_usage() {

		if ( empty( $this->simulation_file ) || $this->simulated_usage ) {
			return;
		}

		global $wpdb;

		$wpdb->query( 'ROLLBACK' );

		system(
			WP_PHP_BINARY
			. ' ' . escapeshellarg( dirname( dirname( __FILE__ ) ) . '/bin/simulate-module-use.php' )
			. ' ' . escapeshellarg( $this->plugin_file )
			. ' ' . escapeshellarg( $this->simulation_file )
			. ' ' . escapeshellarg( $this->locate_wp_tests_config() )
			. ' ' . (int) is_multisite()
			. ' ' . (int) $this->wordpoints_network_active
			. ' ' . escapeshellarg( WP_PLUGIN_UNINSTALL_TESTER_DIR )
			. ' ' . (int) $this->network_active
			, $exit_code
		);

		if ( 0 !== $exit_code ) {
			$this->fail( 'Usage simulation failed with exit code ' . $exit_code );
		}

		$this->flush_cache();

		$this->simulated_usage = true;
	}
}

// EOF
