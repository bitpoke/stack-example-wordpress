<?php
/**
 * Plugin Name: Presslabs Stack
 * Plugin URI: http://presslabs.com/stack/
 * Description: Must-Use plugin for Stack
 * Version: 0.1.9
 * Author: Presslabs
 * Author URI: http://presslabs.com/
 */

// we are copied into mu-plugins root
if ( file_exists( __DIR__ . '/stack-mu-plugin/' . basename( __FILE__ ) ) ) {
    require_once __DIR__ . '/stack-mu-plugin/' . basename( __FILE__ );
} else {
    // load Composer autoloader if bundled
    if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
        require_once __DIR__ . '/vendor/autoload.php';
    }

    if ( ! class_exists( '\Stack\Config' ) ) {
        trigger_error( 'Presslabs Stack WordPress mu-plugin is not fully installed! Please install with Composer or download full release archive.', E_USER_ERROR );
    }

    require __DIR__ . '/src/mu-plugin.php';
}
