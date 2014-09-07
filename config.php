<?php

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



// ----- NO MORE EDITS BELOW THIS LINE -----


// Initialise

include_once( 'core/setup.php' );


// Delete data

$securitySettings = array();
unset( $salt );

$dbSettings = array();
unset( $dbSettings );

$logSettings = array();
unset( $logSettings );