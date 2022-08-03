<?php
// Set custom wp-config.php settings in this file

if ( '' !== getenv( 'GITPOD_WORKSPACE_ID' ) ) {
	// on Gitpod, make sure the user can update plugins, themes, translations, mu-plugins and drop-ins
	define( 'FS_METHOD', 'direct' );
}

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
