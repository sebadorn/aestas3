<?php

require_once( '../../core/autoload.php' );
require_once( '../../core/config.php' );

if( !ae_Security::isLoggedIn() ) {
	header( 'Location: ../index.php?error=not_logged_in' );
	exit;
}


if( !isset( $_POST['area'], $_POST['bulk-status-change'] ) ) {
	header( 'Location: ../admin.php?error=no_area_or_status_given' );
	exit;
}

if( !isset( $_POST['entry'] ) || count( $_POST['entry'] ) == 0 ) {
	header( 'Location: ../admin.php?error=no_entries_given' );
	exit;
}


$status = $_POST['bulk-status-change'];

switch( $_POST['area'] ) {

	case 'category':
		$isValidStatus = ( $status == 'delete' ) ? TRUE : ae_CategoryModel::isValidStatus( $status );
		$preDelete = ae_CategoryModel::STATUS_TRASH;
		$table = ae_CategoryModel::TABLE;
		$idField = ae_CategoryModel::TABLE_ID_FIELD;
		break;

	case 'comment':
		$isValidStatus = ( $status == 'delete' ) ? TRUE : ae_CommentModel::isValidStatus( $status );
		$preDelete = ae_CommentModel::STATUS_TRASH;
		$table = ae_CommentModel::TABLE;
		$idField = ae_CommentModel::TABLE_ID_FIELD;
		break;

	case 'page':
		$isValidStatus = ( $status == 'delete' ) ? TRUE : ae_PageModel::isValidStatus( $status );
		$preDelete = ae_PageModel::STATUS_TRASH;
		$table = ae_PageModel::TABLE;
		$idField = ae_PageModel::TABLE_ID_FIELD;
		break;

	case 'post':
		$isValidStatus = ( $status == 'delete' ) ? TRUE : ae_PostModel::isValidStatus( $status );
		$preDelete = ae_PostModel::STATUS_TRASH;
		$table = ae_PostModel::TABLE;
		$idField = ae_PostModel::TABLE_ID_FIELD;
		break;

	case 'user':
		$isValidStatus = ( $status == 'delete' ) ? TRUE : ae_UserModel::isValidStatus( $status );
		$preDelete = ae_UserModel::STATUS_SUSPENDED;
		$table = ae_UserModel::TABLE;
		$idField = ae_UserModel::TABLE_ID_FIELD;
		break;

	default:
		$isValidStatus = FALSE;

}

if( !$isValidStatus ) {
	header( 'Location: ../admin.php?error=invalid_status' );
	exit;
}


$prefix = explode( '_', $idField );
$prefix = $prefix[0];

// Delete all selected
if( $status == 'delete' ) {
	$stmt = '
		DELETE FROM `' . $table . '`
		WHERE
			' . $prefix . '_status = :preDelete
			AND (
	';
	$params = array(
		':preDelete' => $preDelete
	);

	foreach( $_POST['entry'] as $id ) {
		if( !ae_Validate::id( $id ) ) {
			continue;
		}

		$stmt .= $idField . ' = :entry' . $id . ' OR ';
		$params[':entry' . $id] = $id;
	}

	$stmt = mb_substr( $stmt, 0, -4 );
	$stmt .= ' )';
}
// Change statuses
else {
	$stmt = '
		UPDATE `' . $table . '`
		SET ' . $prefix . '_status = :status
		WHERE
	';
	$params = array(
		':status' => $status
	);

	foreach( $_POST['entry'] as $id ) {
		if( !ae_Validate::id( $id ) ) {
			continue;
		}

		$stmt .= $idField . ' = :entry' . $id . ' OR ';
		$params[':entry' . $id] = $id;
	}

	$stmt = mb_substr( $stmt, 0, -4 );
}


if( ae_Database::query( $stmt, $params ) === FALSE ) {
	header( 'Location: ../admin.php?area=manage&' . $_POST['area'] . '&error=query_failed' );
	exit;
}

if( $status == 'delete' && $_POST['area'] == 'post' ) {
	$stmt = '
		DELETE FROM `' . AE_TABLE_POSTS2CATEGORIES . '`
		WHERE
	';
	$params = array();

	foreach( $_POST['entry'] as $id ) {
		if( !ae_Validate::id( $id ) ) {
			continue;
		}

		$stmt .= 'pc_post = :entry' . $id . ' OR ';
		$params[':entry' . $id] = $id;
	}

	$stmt = mb_substr( $stmt, 0, -4 );

	if( ae_Database::query( $stmt, $params ) === FALSE ) {
		header( 'Location: ../admin.php?area=manage&' . $_POST['area'] . '&error=query_delete_post_relations_failed' );
		exit;
	}
}


header( 'Location: ../admin.php?area=manage&' . $_POST['area'] . '&success=status_change' );
