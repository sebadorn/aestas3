<?php

require_once( '../../core/autoload.php' );
require_once( '../../core/config.php' );


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
