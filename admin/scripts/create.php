<?php

require_once( '../../core/autoload.php' );
require_once( '../../config.php' );

if( !ae_Security::isLoggedIn() ) {
	header( 'Location: ../index.php?error=not_logged_in' );
	exit;
}


if( !isset( $_POST['area'] ) || !ae_Security::isValidSubArea( 'create', $_POST['area'] ) ) {
	header( 'Location: ../admin.php?error=unknown_create_area' );
	exit;
}


switch( $_POST['area'] ) {

	case 'category':
		$category = new ae_CategoryModel();
		$category->setTitle( $_POST['category-title'] );
		$category->save();
		$id = $category->getId();
		break;

}


header( 'Location: ../admin.php?area=manage&' . $_POST['area'] . '&id=' . $id );
