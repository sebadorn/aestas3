<?php

require_once( '../../core/autoload.php' );
require_once( '../../core/config.php' );

if( !ae_Security::isLoggedIn() ) {
	header( 'Location: ../index.php?error=not_logged_in' );
	exit;
}


if( !isset( $_GET['status'] ) ) {
	header( 'Location: ../admin.php?error=no_status_given' );
	exit;
}

$mainArea = 'manage';

if( isset( $_GET['category'] ) && ae_Validate::id( $_GET['category'] ) ) {
	$area = 'category';
	$model = new ae_CategoryModel();
}
else if( isset( $_GET['cofilter'] ) && ae_Validate::id( $_GET['cofilter'] ) ) {
	$area = 'cofilter';
	$mainArea = 'settings';
	$model = new ae_CommentfilterModel();
}
else if( isset( $_GET['comment'] ) && ae_Validate::id( $_GET['comment'] ) ) {
	$area = 'comment';
	$model = new ae_CommentModel();
}
else if( isset( $_GET['media'] ) && ae_Validate::id( $_GET['media'] ) ) {
	$area = 'media';
	$mainArea = 'media';
	$model = new ae_MediaModel();
	$model->setMediaPath( '../../media/' );
}
else if( isset( $_GET['page'] ) && ae_Validate::id( $_GET['page'] ) ) {
	$area = 'page';
	$model = new ae_PageModel();
}
else if( isset( $_GET['post'] ) && ae_Validate::id( $_GET['post'] ) ) {
	$area = 'post';
	$model = new ae_PostModel();
}
else if( isset( $_GET['user'] ) && ae_Validate::id( $_GET['user'] ) ) {
	$area = 'user';
	$model = new ae_UserModel();
}
else {
	header( 'Location: ../admin.php?error=unknown_area_or_invalid_id' );
	exit;
}


$model->load( $_GET[$area] );

if( $_GET['status'] == 'delete' ) {
	if( !$model->delete() ) {
		header( 'Location: ../admin.php?area=' . $mainArea . '&' . $area . '&error=delete' );
		exit;
	}

	header( 'Location: ../admin.php?area=' . $mainArea . '&' . $area . '&success=delete' );
	exit;
}
else {
	try {
		$model->setStatus( $_GET['status'] );
	}
	catch( Exception $e ) {
		header( 'Location: ../admin.php?area=' . $mainArea . '&' . $area . '&error=invalid_status' );
		exit;
	}

	if( !$model->save() ) {
		header( 'Location: ../admin.php?area=' . $mainArea . '&' . $area . '&error=saving_failed' );
		exit;
	}
}

header( 'Location: ../admin.php?area=' . $mainArea . '&' . $area . '&success=status_change' );
