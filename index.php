<?php

require_once( 'core/autoload.php' );
require_once( 'core/config.php' );

$theme = 'standard'; // TODO: Get theme from settings.

$sb = new ae_SiteBuilder();
$sb->render( 'themes/' . $theme . '/index.php' );
