<?php
// Set custom wp-config.php settings in this file 
if ( 'true' === getenv('CODESPACES') ) {
	// on Github Codespaces obey the X-Forwarded-Host header
	$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_X_FORWARDED_HOST'];
}

if ( 'development' === getenv('WP_ENV') ) {
	define( 'DISALLOW_FILE_EDIT', false );
	define( 'DISALLOW_FILE_MODS', false );

	define( 'WP_DEBUG', true );
	define( 'SAVEQUERIES', true );
}
