<?php

function autoload( $classname ) {
	if( substr( $classname, -5, 5 ) == 'Model' ) {
		$classname = 'models/' . $classname;
	}

	require_once( $classname . '.php' );
}

spl_autoload_register( 'autoload' );
