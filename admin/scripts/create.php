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


/**
 * Create the category.
 * @return {int} ID of the new category.
 */
function createCategory() {
	if( !isset( $_POST['category-title'], $_POST['category-permalink'] ) ) {
		header( 'Location: ../admin.php?error=missing_data_for_category' );
		exit;
	}

	$permalink = trim( $_POST['category-permalink'] );

	$category = new ae_CategoryModel();
	$category->setTitle( $_POST['category-title'] );

	if( $permalink != '' ) {
		$category->setPermalink( $permalink );
	}

	$category->save();

	return $category->getId();
}


/**
 * Create the page.
 * @return {int} ID of the new page.
 */
function createPage() {
	if( !isset(
		$_POST['page-title'], $_POST['page-permalink'], $_POST['page-content'],
		$_POST['page-publish-month'], $_POST['page-publish-day'], $_POST['page-publish-year'],
		$_POST['page-publish-hour'], $_POST['page-publish-minute'],
		$_POST['submit']
	) ) {
		header( 'Location: ../admin.php?error=missing_data_for_page' );
		exit;
	}

	$comments = isset( $_POST['page-comments-disabled'] )
	            ? ae_PageModel::COMMENTS_DISABLED
	            : ae_PageModel::COMMENTS_OPEN;
	$datetime = sprintf(
		'%04d-%02d-%02d %02d:%02d:00',
		$_POST['page-publish-year'], $_POST['page-publish-month'], $_POST['page-publish-day'],
		$_POST['page-publish-hour'], $_POST['page-publish-minute']
	);
	$permalink = trim( $_POST['page-permalink'] );
	$status = ( $_POST['submit'] == 'draft' )
	          ? ae_PageModel::STATUS_DRAFT
	          : ae_PageModel::STATUS_PUBLISHED;

	$page = new ae_PageModel();
	$page->setTitle( $_POST['page-title'] );

	if( $permalink != '' ) {
		$page->setPermalink( $permalink );
	}

	$page->setContent( $_POST['page-content'] );
	$page->setDatetime( isset( $_POST['page-schedule'] ) ? $datetime : date( 'Y-m-d H:i:s' ) );
	$page->setCommentsStatus( $comments );
	$page->setStatus( $status );
	$page->setUserId( ae_Security::getCurrentUserId() );
	$page->save();

	return $page->getId();
}


/**
 * Create the post.
 * @return {int} ID of the new post.
 */
function createPost() {
	if( !isset(
		$_POST['post-title'], $_POST['post-permalink'], $_POST['post-content'], $_POST['post-tags'],
		$_POST['post-publish-month'], $_POST['post-publish-day'], $_POST['post-publish-year'],
		$_POST['post-publish-hour'], $_POST['post-publish-minute'],
		$_POST['submit']
	) ) {
		header( 'Location: ../admin.php?error=missing_data_for_post' );
		exit;
	}

	$comments = isset( $_POST['post-comments-disabled'] )
	            ? ae_PostModel::COMMENTS_DISABLED
	            : ae_PostModel::COMMENTS_OPEN;
	$datetime = sprintf(
		'%04d-%02d-%02d %02d:%02d:00',
		$_POST['post-publish-year'], $_POST['post-publish-month'], $_POST['post-publish-day'],
		$_POST['post-publish-hour'], $_POST['post-publish-minute']
	);
	$permalink = trim( $_POST['post-permalink'] );
	$status = ( $_POST['submit'] == 'draft' )
	          ? ae_PostModel::STATUS_DRAFT
	          : ae_PostModel::STATUS_PUBLISHED;

	$post = new ae_PostModel();
	$post->setTitle( $_POST['post-title'] );

	if( $permalink != '' ) {
		$post->setPermalink( $permalink );
	}

	$post->setContent( $_POST['post-content'] );
	$post->setDatetime( isset( $_POST['post-schedule'] ) ? $datetime : date( 'Y-m-d H:i:s' ) );
	$post->setCommentsStatus( $comments );
	$post->setStatus( $status );
	$post->setTags( $_POST['post-tags'] );
	$post->setUserId( ae_Security::getCurrentUserId() );
	$post->save();

	return $post->getId();
}


/**
 * Add the relations between the new post and its categories.
 * @param  {int}     $postId Post ID.
 * @return {boolean}         TRUE, if successful added relations or no relations to add, FALSE otherwise.
 */
function createPost2CategoryRelation( $postId ) {
	if(
		!isset( $_POST['post-categories'] ) ||
		!is_array( $_POST['post-categories'] ) ||
		count( $_POST['post-categories'] ) == 0
	) {
		return TRUE;
	}

	$stmt = '
		INSERT INTO `' . AE_TABLE_POSTS2CATEGORIES. '` (
			pc_post,
			pc_category
		)
		VALUES
	';
	$params = array();

	foreach( $_POST['post-categories'] as $caId ) {
		if( ae_Validate::id( $caId ) ) {
			$stmt .= '( ?, ? ), ';
			$params[] = $postId;
			$params[] = $caId;
		}
	}

	$stmt = substr( $stmt, 0, -2 );

	if( ae_Database::query( $stmt, $params ) === FALSE ) {
		return FALSE;
	}
}


/**
 * Create the user.
 * @return {int} ID of the new user.
 */
function createUser() {
	if( !isset(
		$_POST['user-name-internal'], $_POST['user-name-external'],
		$_POST['user-permalink'], $_POST['user-password']
	) ) {
		header( 'Location: ../admin.php?error=missing_data_for_user' );
		exit;
	}

	$permalink = trim( $_POST['user-permalink'] );
	$status = isset( $_POST['user-status-suspended'] )
	          ? ae_UserModel::STATUS_SUSPENDED
	          : ae_UserModel::STATUS_ACTIVE;

	$user = new ae_UserModel();
	$user->setNameInternal( $_POST['user-name-internal'] );
	$user->setNameExternal( $_POST['user-name-external'] );

	if( $permalink != '' ) {
		$user->setPermalink( $permalink );
	}

	$user->setPasswordHash( ae_Security::hash( $_POST['user-password'], $user->getNameInternal() ) );
	$user->setStatus( $status );
	$user->save();

	return $user->getId();
}


$id = FALSE;

switch( $_POST['area'] ) {

	case 'category':
		$id = createCategory();
		break;

	case 'page':
		$id = createPage();
		break;

	case 'post':
		$id = createPost();
		createPost2CategoryRelation( $id );
		break;

	case 'user':
		$id = createUser();
		break;

}


if( $id !== FALSE ) {
	header( 'Location: ../admin.php?area=manage&' . $_POST['area'] . '&id=' . $id );
}
else {
	ae_Log::printAll();
}
