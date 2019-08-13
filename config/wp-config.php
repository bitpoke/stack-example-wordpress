<?php
// Set custom wp-config.php settings in this file 

if ( 'development' === getenv('WP_ENV') ) {
	define( 'DISALLOW_FILE_EDIT', false );
	define( 'DISALLOW_FILE_MODS', false );

	define( 'WP_DEBUG', true );
	define( 'SAVEQUERIES', true );
}
