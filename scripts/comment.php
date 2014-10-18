<?php

require_once( '../core/autoload.php' );
require_once( '../core/config.php' );

if(
	( isset( $_POST['comment-do-not-fill'] ) && $_POST['comment-do-not-fill'] != '' ) ||
	!isset( $_POST['comment-post'] )
) {
	header( 'Location: ../' );
	exit;
}

if(
	!isset(
		$_POST['comment-author-name'],
		$_POST['comment-author-email'],
		$_POST['comment-author-url'],
		$_POST['comment-content'],
		$_POST['comment-post']
	) ||
	mb_strlen( trim( $_POST['comment-content'] ) ) == 0
) {
	header( 'Location: ../?p=' . $_POST['comment-post'] . '&error=missing_data#comment-form' );
	exit;
}


$url = trim( $_POST['comment-author-url'] );

if( mb_strlen( $url ) > 0 && !preg_match( '/^(http|ftp)s?:\/\//i', $url ) ) {
	$url = 'http://' . $url;
}

$content = ae_Security::sanitizeHTML( trim( $_POST['comment-content'] ) );
$content = nl2br( $content );

$co = new ae_CommentModel();

// Bad errors
try {
	$co->setPostId( $_POST['comment-post'] );
}
catch( Exception $exc ) {
	header( 'Location: ../?p=' . $_POST['comment-post'] . '&error=invalid_data#comment-form' );
	exit;
}

// Forgivable errors with default values for fallback
try {
	$co->setAuthorName( $_POST['comment-author-name'] );
	$co->setAuthorEmail( $_POST['comment-author-email'] );
	$co->setAuthorUrl( $url );
	$co->setAuthorIp( $_SERVER['REMOTE_ADDR'] );
	$co->setContent( $content );
	$co->setStatus( COMMENT_DEFAULT_STATUS );

	if( ae_Security::isLoggedIn() ) {
		$co->setUserId( ae_Security::getCurrentUserId() );
	}

	$filter = array(
		'LIMIT' => FALSE,
		'WHERE' => 'cf_status = :status'
	);
	$params = array(
		':status' => ae_CommentfilterModel::STATUS_ACTIVE
	);
	$cfList = new ae_CommentfilterList( $filter, $params, FALSE );
	$keep = $cfList->applyFilters( $co );

	if( !$keep ) {
		header( 'Location: ../?p=' . $_POST['comment-post'] . '&error=comment_deleted_by_filter' );
		exit;
	}

	$co->save();
}
catch( Exception $exc ) {
	header( 'Location: ../?p=' . $_POST['comment-post'] . '&error=failed_to_save#comment-form' );
	exit;
}


header( 'Location: ../?p=' . $_POST['comment-post'] . '&saved#comment-' . $co->getId() );
