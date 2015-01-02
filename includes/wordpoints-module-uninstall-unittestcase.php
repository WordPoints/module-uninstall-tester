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
	 * The full path to the main module file.
	 *
	 * @since 0.1.0
	 *
	 * @type string $module_file
	 */
	protected $module_file;

	//
	// Methods.
	//

	/**
	 * Run the module's install script.
	 *
	 * Called by the setUp() method.
	 *
	 * Installation is run seperately, so the module is never actually loaded in this
	 * process. This provides more realistic testing of the uninstall process, since
	 * it is run while the module is inactive, just like in "real life".
	 *
	 * @since 0.1.0
	 */
	protected function install() {

		// Activate the WordPoints plugin.
		$path = WORDPOINTS_TESTS_DIR . '/../../src/wordpoints.php';

		if ( function_exists( 'wp_register_plugin_realpath' ) ) { // Back-compat for WordPress 3.8.
			wp_register_plugin_realpath( $path );
		}

		$plugins = get_option( 'active_plugins', array() );
		$plugins[] = plugin_basename( $path );
		update_option( 'active_plugins', $plugins );

		system(
			WP_PHP_BINARY
			. ' ' . escapeshellarg( dirname( dirname( __FILE__ ) ) . '/bin/install-module.php' )
			. ' ' . escapeshellarg( $this->module_file )
			. ' ' . escapeshellarg( $this->install_function )
			. ' ' . escapeshellarg( $this->locate_wp_tests_config() )
			. ' ' . (int) is_multisite()
			. ' ' . escapeshellarg( WP_PLUGIN_UNINSTALL_TESTER_DIR )
		);
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
			. ' ' . escapeshellarg(	getenv( 'WORDPOINTS_TESTS_DIR' ) . '../../src/wordpoints.php' )
			. ' ' . escapeshellarg( $this->simulation_file )
			. ' ' . escapeshellarg( $this->locate_wp_tests_config() )
			. ' ' . (int) is_multisite()
			. ' ' . escapeshellarg( WP_PLUGIN_UNINSTALL_TESTER_DIR )
			. ' ' . escapeshellarg( $this->plugin_file )
		);

		$this->flush_cache();

		$this->simulated_usage = true;
	}

	/**
	 * Run the module's uninstall script.
	 *
	 * Call it and then run your uninstall assertions. You should always test
	 * installation before testing uninstallation.
	 *
	 * @since 0.1.0
	 */
	public function uninstall() {

		if ( empty( $this->module_file ) ) {
			exit( 'Error: $module_file property not set.' . PHP_EOL );
		}

		require getenv( 'WORDPOINTS_TESTS_DIR' ) . '/../../src/includes/uninstall-bootstrap.php';
		require_once( getenv( 'WORDPOINTS_TESTS_DIR' ) . '/../../src/includes/class-un-installer-base.php' );

		$this->plugin_file = $this->module_file;
		parent::uninstall();
	}
}
