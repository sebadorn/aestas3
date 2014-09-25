<?php

function autoload( $classname ) {
	if( mb_substr( $classname, -5, 5, 'UTF-8' ) == 'Model' ) {
		$classname = 'models/' . $classname;
	}
	else if( mb_substr( $classname, 0, 4, 'UTF-8' ) == 'ae_i' ) {
		$classname = 'interfaces/' . $classname;
	}

	require_once( $classname . '.php' );
}

spl_autoload_register( 'autoload' );
