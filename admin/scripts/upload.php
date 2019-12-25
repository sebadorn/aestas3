<?php

require_once( '../../core/autoload.php' );
require_once( '../../core/config.php' );

ae_Security::initSession();

if( !ae_Security::isLoggedIn() ) {
	header( 'Location: ../index.php?error=not_logged_in' );
	exit;
}


if( !isset( $_FILES['upload'] ) ) {
	header( 'Location: ../admin.php?area=media&error=no_files_uploaded' );
	exit;
}

$f = new ae_FileUpload( $_FILES['upload'] );
$f->setPathToMediaDir( '../../media/' );

if( !$f->saveToFileSystem() || !$f->saveToDB() ) {
	ae_Log::printAll();
	exit;
}

header( 'Location: ../admin.php?area=media&success' );
