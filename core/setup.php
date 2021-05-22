<?php

ini_set( 'default_charset', 'UTF-8' );
mb_internal_encoding( 'UTF-8' );

define( 'AE_TABLE_CATEGORIES', $dbSettings['table_prefix'] . 'categories' );
define( 'AE_TABLE_COMMENTFILTERS', $dbSettings['table_prefix'] . 'commentfilters' );
define( 'AE_TABLE_COMMENTS', $dbSettings['table_prefix'] . 'comments' );
define( 'AE_TABLE_MEDIA', $dbSettings['table_prefix'] . 'media' );
define( 'AE_TABLE_PAGES', $dbSettings['table_prefix'] . 'pages' );
define( 'AE_TABLE_POSTS', $dbSettings['table_prefix'] . 'posts' );
define( 'AE_TABLE_POSTS2CATEGORIES', $dbSettings['table_prefix'] . 'posts2categories' );
define( 'AE_TABLE_SETTINGS', $dbSettings['table_prefix'] . 'settings' );
define( 'AE_TABLE_USERS', $dbSettings['table_prefix'] . 'users' );
define( 'AE_VERSION', '3' );
define( 'COMMENT_DEFAULT_NAME', $commentSettings['default_name'] );
define( 'COMMENT_DEFAULT_STATUS', ae_CommentModel::STATUS_APPROVED );
define( 'IMAGE_COMPRESSION_PNG', $mediaSettings['image_compression_png'] );
define( 'IMAGE_PREVIEW_MAX_WIDTH', $mediaSettings['preview_image_max_width'] );
define( 'IMAGE_QUALITY_JPEG', $mediaSettings['image_quality_jpeg'] );
define( 'OVERWRITE_MOD_REWRITE_ENABLED', FALSE ); // Detection does not work with PHP running in CGI mode
define( 'PERMALINK_BASE_CATEGORY', 'category/' );
define( 'PERMALINK_BASE_OFFSET', 'page/' );
define( 'PERMALINK_BASE_PAGE', '' );
define( 'PERMALINK_BASE_POST', '' );
define( 'PERMALINK_BASE_TAG', 'tag/' );
define( 'PERMALINK_BASE_USER', 'author/' );
define( 'PERMALINK_GET_CATEGORY', 'category' );
define( 'PERMALINK_GET_OFFSET', 'offset' );
define( 'PERMALINK_GET_PAGE', 'page' );
define( 'PERMALINK_GET_POST', 'p' );
define( 'PERMALINK_GET_SEARCH', 'search' );
define( 'PERMALINK_GET_TAG', 'tag' );
define( 'PERMALINK_GET_USER', 'author' );


// Disable Magic Quotes (removed as of PHP 5.4)
if( function_exists( 'get_magic_quotes_runtime' ) && get_magic_quotes_runtime() ) {
	set_magic_quotes_runtime( FALSE );
}

// Disable register_globals (removed as of PHP 5.4)
if( ini_get( 'register_globals' ) ) {
	ini_set( 'register_globals', 0 );
}

// URL constant

$protocol = 'http://';

if(
	( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) ||
	$_SERVER['SERVER_PORT'] == 443
) {
	$protocol = 'https://';
}

$url = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$url = explode( '/', $url );
array_pop( $url );

if( defined( 'IS_RSS' ) ) {
	array_pop( $url );
}

$url = $protocol . implode( '/', $url ) . '/';

define( 'URL', $url );

unset( $url );


// Initialize some needed classes

ae_Timer::start( 'total' );
ae_Log::init( $logSettings );

if( ae_Database::connect( $dbSettings ) === FALSE ) {
	$path = 'themes/error-msg-db.php';
	$path = file_exists( $path ) ? $path : '../' . $path;
	include( $path );

	exit;
}

ae_Security::init( $securitySettings );
ae_Settings::load();


// Constants used in themes and the RSS feed

define( 'THEME', ae_Settings::get( 'theme' ) );
define( 'THEME_PATH', URL . 'themes/' . THEME . '/' );
