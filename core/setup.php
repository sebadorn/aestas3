<?php

define( 'AE_TABLE_USERS', $dbSettings['table_prefix'] . 'users' );

ae_Log::init( $logSettings );
ae_Database::connect( $dbSettings );
ae_Security::init( $securitySettings );
