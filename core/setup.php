<?php

define( 'AE_TABLE_CATEGORIES', $dbSettings['table_prefix'] . 'categories' );
define( 'AE_TABLE_COMMENTS', $dbSettings['table_prefix'] . 'comments' );
define( 'AE_TABLE_MEDIA', $dbSettings['table_prefix'] . 'media' );
define( 'AE_TABLE_PAGES', $dbSettings['table_prefix'] . 'pages' );
define( 'AE_TABLE_POSTS', $dbSettings['table_prefix'] . 'posts' );
define( 'AE_TABLE_SETTINGS', $dbSettings['table_prefix'] . 'settings' );
define( 'AE_TABLE_USERS', $dbSettings['table_prefix'] . 'users' );
define( 'AE_VERSION', '3' );

ae_Timer::start( 'total' );
ae_Log::init( $logSettings );
ae_Database::connect( $dbSettings );
ae_Security::init( $securitySettings );
