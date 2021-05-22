<?php

// Errors

ini_set( 'display_errors', '1' );
error_reporting( E_ALL | E_STRICT );

$logSettings = array(
	'enabled' => true
);


// Database

$dbSettings = array(
	'host' => 'localhost',
	'name' => 'aestas3',
	'table_prefix' => 'ae3_',
	'username' => 'aestas3-dev',
	'password' => 'Summer'
);


// Security

$securitySettings = array(
	'allowed_tags' => array( 'a', 'blockquote', 'code', 'del', 'em', 'strong' )
);


// Timezone
// @see http://php.net/manual/en/timezones.php

date_default_timezone_set( 'Europe/Berlin' );


// Comments

$commentSettings = array(
	'default_name' => 'Namenlos'
);


// Media

$mediaSettings = array(
	'image_compression_png' => 9, // 0: no compression - 9: max
	'image_quality_jpeg' => 80, // 100: no compression - 0: pixel sludge
	'preview_image_max_width' => 80
);



// ----- NO MORE EDITS BELOW THIS LINE -----


// Initialise

include_once( 'setup.php' );


// Delete data

$mediaSettings = array();
unset( $mediaSettings );

$commentSettings = array();
unset( $commentSettings );

$securitySettings = array();
unset( $salt );

$dbSettings = array();
unset( $dbSettings );

$logSettings = array();
unset( $logSettings );