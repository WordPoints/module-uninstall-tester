<?php

/**
 * Install a module remotely.
 *
 * @package WordPoints_Module_Uninstall_Tester
 * @since 0.1.0
 */

$module_file      = $argv[1];
$config_file_path = $argv[2];
$is_multisite     = $argv[3];
$is_network_wide  = $argv[4];
$wp_pu_tester_dir = $argv[5];

/**
 * WordPress tests helper functions.
 *
 * We're loading this so that we can hook up our function with tests_add_filter().
 *
 * @since 0.1.0
 */
require_once getenv( 'WP_TESTS_DIR' ) . '/includes/functions.php';

/**
 * Loads WordPoints during module install.
 *
 * @since 0.1.0
 *
 * @action muplugins_loaded
 */
function _wordpoints_module_uninstall_tester_load_wordpoints() {

	require getenv( 'WORDPOINTS_TESTS_DIR' ) . '/../../src/wordpoints.php';
	wordpoints_activate( $GLOBALS['argv'][6] );
}
tests_add_filter( 'muplugins_loaded', '_wordpoints_module_uninstall_tester_load_wordpoints' );

/**
 * Plugin Uninstall Tester bootstrap file to load WordPress.
 *
 * @since 0.1.0
 */
require $wp_pu_tester_dir . '/bin/bootstrap.php';

wordpoints_activate_module( $module_file, '', $is_network_wide );
