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
$plugin_file      = $argv[6];

/**
 * Plugin Uninstall Tester bootstrap file to load WordPress.
 *
 * @since 0.1.0
 */
require $wp_pu_tester_dir . '/bin/bootstrap.php';

/**
 * The plugin API.
 *
 * We need this so that we can use `activate_plugin()`.
 *
 * @since 0.4.0
 */
require_once ABSPATH . '/wp-admin/includes/plugin.php';

// Activate WordPoints.
activate_plugin( $plugin_file, '', $is_network_wide );

wordpoints_activate_module( $module_file, '', $is_network_wide );
