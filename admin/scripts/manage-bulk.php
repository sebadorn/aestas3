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
$mainArea = 'manage';

switch( $_POST['area'] ) {

	case 'category':
		$isValidStatus = ( $status == 'delete' ) ? TRUE : ae_CategoryModel::isValidStatus( $status );
		$modelName = 'ae_CategoryModel';
		$preDelete = ae_CategoryModel::STATUS_TRASH;
		break;

	case 'cofilter':
		$isValidStatus = ( $status == 'delete' ) ? TRUE : ae_CommentfilterModel::isValidStatus( $status );
		$mainArea = 'settings';
		$modelName = 'ae_CommentfilterModel';
		$preDelete = ae_CommentfilterModel::STATUS_INACTIVE;
		break;

	case 'comment':
		$isValidStatus = ( $status == 'delete' ) ? TRUE : ae_CommentModel::isValidStatus( $status );
		$modelName = 'ae_CommentModel';
		$preDelete = ae_CommentModel::STATUS_TRASH;
		break;

	case 'media':
		$isValidStatus = ( $status == 'delete' ) ? TRUE : ae_MediaModel::isValidStatus( $status );
		$mainArea = 'media';
		$modelName = 'ae_MediaModel';
		$preDelete = ae_MediaModel::STATUS_TRASH;
		break;

	case 'page':
		$isValidStatus = ( $status == 'delete' ) ? TRUE : ae_PageModel::isValidStatus( $status );
		$modelName = 'ae_PageModel';
		$preDelete = ae_PageModel::STATUS_TRASH;
		break;

	case 'post':
		$isValidStatus = ( $status == 'delete' ) ? TRUE : ae_PostModel::isValidStatus( $status );
		$modelName = 'ae_PostModel';
		$preDelete = ae_PostModel::STATUS_TRASH;
		break;

	case 'user':
		$isValidStatus = ( $status == 'delete' ) ? TRUE : ae_UserModel::isValidStatus( $status );
		$modelName = 'ae_UserModel';
		$preDelete = ae_UserModel::STATUS_SUSPENDED;
		break;

	default:
		$isValidStatus = FALSE;

}

$table = constant( $modelName . '::TABLE' );
$idField = constant( $modelName . '::TABLE_ID_FIELD' );


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
	$filterMedia = '';

	foreach( $_POST['entry'] as $id ) {
		if( !ae_Validate::id( $id ) ) {
			continue;
		}

		$stmt .= $idField . ' = :entry' . $id . ' OR ';
		$params[':entry' . $id] = $id;
		$filterMedia .= $prefix . '_id = ' . $id . ' OR ';
	}

	$stmt = mb_substr( $stmt, 0, -4 );
	$stmt .= ' )';

	if( $_POST['area'] == 'media' ) {
		$filterMedia = '(' . mb_substr( $filterMedia, 0, -4 ) . ') AND ';
		$filterMedia .= $prefix . '_status = "' . $preDelete . '"';

		$filter = array(
			'LIMIT' => FALSE,
			'WHERE' => $filterMedia
		);

		$mediaList = new ae_MediaList( $filter );

		while( $m = $mediaList->next() ) {
			$m->deleteFile();
		}
	}
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
	header( 'Location: ../admin.php?area=' . $mainArea . '&' . $_POST['area'] . '&error=query_failed' );
	exit;
}


// Delete post-category relations of posts
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
		header( 'Location: ../admin.php?area=' . $mainArea . '&' . $_POST['area'] . '&error=query_delete_post_relations_failed' );
		exit;
	}
}
// Delete post-category relations of categories
else if( $status == 'delete' && $_POST['area'] == 'category' ) {
	$stmt1 = '
		DELETE FROM `' . AE_TABLE_POSTS2CATEGORIES . '`
		WHERE
	';
	$stmt2 = '
		UPDATE `' . ae_CategoryModel::TABLE . '`
		SET ca_parent = 0
		WHERE
	';
	$params = array();

	foreach( $_POST['entry'] as $id ) {
		if( !ae_Validate::id( $id ) ) {
			continue;
		}

		$stmt1 .= 'pc_category = :entry' . $id . ' OR ';
		$stmt2 .= 'ca_id = :entry' . $id . ' OR ';
		$params[':entry' . $id] = $id;
	}

	$stmt1 = mb_substr( $stmt1, 0, -4 );
	$stmt2 = mb_substr( $stmt2, 0, -4 );

	if(
		ae_Database::query( $stmt1, $params ) === FALSE ||
		ae_Database::query( $stmt2, $params ) === FALSE
	) {
		header( 'Location: ../admin.php?area=' . $mainArea . '&' . $_POST['area'] . '&error=query_delete_category_relations_failed' );
		exit;
	}
}


if( ae_Log::hasMessages() ) {
	ae_Log::printAll();
	exit;
}

header( 'Location: ../admin.php?area=' . $mainArea . '&' . $_POST['area'] . '&success=status_change' );
