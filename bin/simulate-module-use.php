<?php

/**
 * Simulate using a module remotely.
 *
 * @package WordPoints_Module_Uninstall_Tester
 * @since 0.2.0
 */

$wp_pu_tester_dir = $argv[5];

/**
 * Load the main module file as if it was active in module usage simulation.
 *
 * @since 0.2.0
 */
function _load_wordpoints_module() {

	require $GLOBALS['argv'][6];
}

/**
 * Load the WordPress tests functions.
 *
 * We are loading this so that we can add our tests filter to load the plugin, using
 * tests_add_filter().
 *
 * @since 0.2.0
 */
require_once getenv( 'WP_TESTS_DIR' ) . 'includes/functions.php';

tests_add_filter( 'plugins_loaded', '_load_wordpoints_module', 15 );

/**
 * Load the WordPoints tests functions.
 *
 * @since 0.2.0
 */
require_once getenv( 'WORDPOINTS_TESTS_DIR' ) . 'includes/functions.php';

require $wp_pu_tester_dir . '/bin/simulate-plugin-use.php';

// EOF
