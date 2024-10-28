<?php
/**
 * Plugin Name: Debug Bar Console
 * Plugin URI: http://wordpress.org/extend/plugins/debug-bar-console/
 * Description: Adds a PHP/SQL console panel to the Debug Bar plugin. Requires the Debug Bar plugin.
 * Author: Drew Jaynes
 * Author URI: https://werdswords.com
 * Version: 0.3.1
 * License: GPLv2
 */

/*
 * Copyright (c) 2024, Drew Jaynes
 * Copyright (c) 2011-2024, Daryl Koopersmith
 * http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

add_filter('debug_bar_panels', 'debug_bar_console_panel');
function debug_bar_console_panel( $panels ) {
	require_once 'class-debug-bar-console.php';
	$panels[] = new Debug_Bar_Console();
	return $panels;
}

add_action('debug_bar_enqueue_scripts', 'debug_bar_console_scripts');
function debug_bar_console_scripts() {
	$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.dev' : '';

	// Codemirror
	wp_enqueue_style( 'debug-bar-codemirror', plugins_url( "codemirror/lib/codemirror.css", __FILE__ ), array(), '2.22' );
	wp_enqueue_script( 'debug-bar-codemirror', plugins_url( "codemirror/debug-bar-codemirror.js", __FILE__ ), array(), '2.22' );

	wp_enqueue_style( 'debug-bar-console', plugins_url( "css/debug-bar-console$suffix.css", __FILE__ ), array( 'debug-bar', 'debug-bar-codemirror' ), '20240827' );
	wp_enqueue_script( 'debug-bar-console', plugins_url( "js/debug-bar-console$suffix.js", __FILE__ ), array( 'debug-bar', 'debug-bar-codemirror' ), '20240827' );
}

