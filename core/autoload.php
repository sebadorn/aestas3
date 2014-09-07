<?php

set_include_path(
	'.' . PATH_SEPARATOR .
	'..' . PATH_SEPARATOR .
	'./core' . PATH_SEPARATOR .
	'../core' . PATH_SEPARATOR .
	get_include_path()
);

function autoload( $classname ) {
	require_once( $classname . '.php' );
}

spl_autoload_register( 'autoload' );
