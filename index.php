<?php

require_once( 'core/autoload.php' );
require_once( 'core/config.php' );

$theme = 'standard'; // TODO: Get theme from settings.

$url = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$url = explode( '/', $url );
array_pop( $url );
$url = '//' . implode( '/', $url ) . '/';

ae_Settings::load();
define( 'URL', $url );
define( 'THEME', ae_Settings::get( 'theme' ) );
define( 'THEME_PATH', URL . 'themes/' . THEME . '/' );

unset( $url );

ae_Permalink::init();

$sb = new ae_SiteBuilder();
$sb->render( 'themes/' . $theme . '/index.php' );
