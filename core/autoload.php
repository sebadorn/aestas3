<?php

function autoload( $classname ) {
	if( substr( $classname, -5, 5 ) == 'Model' ) {
		$classname = 'models/' . $classname;
	}
	else if( substr( $classname, 0, 4 ) == 'ae_i' ) {
		$classname = 'interfaces/' . $classname;
	}

	require_once( $classname . '.php' );
}

spl_autoload_register( 'autoload' );
