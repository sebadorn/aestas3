<?php

require_once( '../core/autoload.php' );


// Errors

error_reporting( E_ALL | E_STRICT );

$logSettings = array(
	'enabled' => true
);


// Database

$dbSettings = array(
	'host' => 'localhost',
	'name' => 'aestas3',
	'table_prefix' => 'ae_',
	'username' => 'aestas3-dev',
	'password' => 'Summer'
);


// Security

$securitySettings = array(
	'hash_iterations' => '09' // between 04-31
);


// Timezone
// @see http://php.net/manual/en/timezones.php

date_default_timezone_set( 'Europe/Berlin' );


// Media

$mediaSettings = array(
	'image_compression_png' => 9, // 0: no compression - 9: max
	'image_quality_jpeg' => 80, // 100: no compression - 0: pixel sludge
	'preview_image_max_width' => 80
);


// RSS

$rssSettings = array(
	'protocol' => 'http'
);


// Initialise

ini_set( 'default_charset', 'UTF-8' );
mb_internal_encoding( 'UTF-8' );

define( 'AE_TABLE_CATEGORIES', $dbSettings['table_prefix'] . 'categories' );
define( 'AE_TABLE_COMMENTS', $dbSettings['table_prefix'] . 'comments' );
define( 'AE_TABLE_MEDIA', $dbSettings['table_prefix'] . 'media' );
define( 'AE_TABLE_PAGES', $dbSettings['table_prefix'] . 'pages' );
define( 'AE_TABLE_POSTS', $dbSettings['table_prefix'] . 'posts' );
define( 'AE_TABLE_POSTS2CATEGORIES', $dbSettings['table_prefix'] . 'posts2categories' );
define( 'AE_TABLE_SETTINGS', $dbSettings['table_prefix'] . 'settings' );
define( 'AE_TABLE_USERS', $dbSettings['table_prefix'] . 'users' );
define( 'AE_VERSION', '3' );
define( 'IMAGE_COMPRESSION_PNG', $mediaSettings['image_compression_png'] );
define( 'IMAGE_PREVIEW_MAX_WIDTH', $mediaSettings['preview_image_max_width'] );
define( 'IMAGE_QUALITY_JPEG', $mediaSettings['image_quality_jpeg'] );
define( 'RSS_PROTOCOL', $rssSettings['protocol'] );

// Disable Magic Quotes (removed as of PHP 5.4)
if( get_magic_quotes_runtime() ) {
	set_magic_quotes_runtime( FALSE );
}

// Disable register_globals (removed as of PHP 5.4)
if( ini_get( 'register_globals' ) ) {
	ini_set( 'register_globals', 0 );
}

ae_Timer::start( 'total' );
ae_Log::init( $logSettings );
ae_Database::connect( $dbSettings );
ae_Security::init( $securitySettings );


// Delete data

$rssSettings = array();
unset( $rssSettings );

$mediaSettings = array();
unset( $mediaSettings );

$securitySettings = array();
unset( $salt );

$dbSettings = array();
unset( $dbSettings );

$logSettings = array();
unset( $logSettings );
