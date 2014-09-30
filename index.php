<?php

require_once( 'core/autoload.php' );
require_once( 'core/config.php' );

ae_Permalink::init();

$sb = new ae_SiteBuilder();
$sb->render( 'themes/' . THEME . '/index.php' );
