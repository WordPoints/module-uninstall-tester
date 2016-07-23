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
	 * The main file of WordPoints.
	 *
	 * @since 0.3.0
	 *
	 * @var string
	 */
	protected $wordpoints_file;

	/**
	 * @since 0.3.0
	 */
	public function setUp() {

		$this->wordpoints_file = dirname( dirname( WORDPOINTS_TESTS_DIR ) ) . '/src/wordpoints.php';

		wp_register_plugin_realpath( $this->wordpoints_file );

		$this->plugin_file = plugin_basename( $this->wordpoints_file );

		parent::setUp();
	}

	//
	// Methods.
	//

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

		// Activate the WordPoints plugin.
		$plugins = get_option( 'active_plugins', array() );
		$plugins[] = $this->plugin_file;
		update_option( 'active_plugins', $plugins );

		system(
			WP_PHP_BINARY
			. ' ' . escapeshellarg( dirname( dirname( __FILE__ ) ) . '/bin/install-module.php' )
			. ' ' . escapeshellarg( $this->module_file )
			. ' ' . escapeshellarg( $this->locate_wp_tests_config() )
			. ' ' . (int) is_multisite()
			. ' ' . (int) $this->network_active
			. ' ' . escapeshellarg( WP_PLUGIN_UNINSTALL_TESTER_DIR )
			. ' ' . escapeshellarg( $this->plugin_file )
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
			. ' ' . escapeshellarg( $this->wordpoints_file )
			. ' ' . escapeshellarg( $this->simulation_file )
			. ' ' . escapeshellarg( $this->locate_wp_tests_config() )
			. ' ' . (int) is_multisite()
			. ' ' . (int) $this->network_active
			. ' ' . escapeshellarg( WP_PLUGIN_UNINSTALL_TESTER_DIR )
			. ' ' . escapeshellarg( $this->plugin_file )
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
