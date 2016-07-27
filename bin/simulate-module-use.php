<?php

/**
 * Simulate using a module remotely.
 *
 * @package WordPoints_Module_Uninstall_Tester
 * @since 0.2.0
 */

$wp_pu_tester_dir = $argv[6];

/**
 * Load the WordPoints tests functions.
 *
 * @since 0.2.0
 */
require_once getenv( 'WORDPOINTS_TESTS_DIR' ) . 'includes/functions.php';

/**
 * The Plugin Uninstall Tester's usage simulator script.
 *
 * @since 0.2.0
 */
require $wp_pu_tester_dir . '/bin/simulate-plugin-use.php';

// EOF
