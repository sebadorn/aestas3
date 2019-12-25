<?php

require_once( '../../core/autoload.php' );
require_once( '../../core/config.php' );

ae_Security::initSession();

if( !ae_Security::isLoggedIn() ) {
	header( 'Location: ../index.php?error=not_logged_in' );
	exit;
}


if( !isset( $_POST['area'] ) || !ae_Security::isValidSubArea( 'create', $_POST['area'] ) ) {
	header( 'Location: ../admin.php?error=unknown_create_area' );
	exit;
}

if( isset( $_POST['edit-id'] ) && !ae_Validate::id( $_POST['edit-id'] ) ) {
	header( 'Location: ../admin.php?area=manage&' . $_POST['area'] . '&error=invalid_edit_id' );
	exit;
}


/**
 * Create the category.
 * @return {int} ID of the new category.
 */
function createCategory() {
	if( !isset(
		$_POST['category-title'],
		$_POST['category-parent'],
		$_POST['category-permalink']
	) ) {
		header( 'Location: ../admin.php?error=missing_data_for_category' );
		exit;
	}

	$permalink = trim( $_POST['category-permalink'] );

	$category = new ae_CategoryModel();

	if( isset( $_POST['edit-id'] ) ) {
		$category->setId( $_POST['edit-id'] );
	}

	$category->setTitle( $_POST['category-title'] );
	$category->setParent( $_POST['category-parent'] );

	if( $permalink != '' ) {
		$category->setPermalink( $permalink );
	}

	$category->save();

	return $category->getId();
}


/**
 * Create the comment filter.
 * @return {int} ID of the comment filter.
 */
function createCommentfilter() {
	if( !isset(
		$_POST['cf-name'], $_POST['cf-target'], $_POST['cf-match'], $_POST['cf-action'],
		$_POST['submit']
	) ) {
		header( 'Location: ../admin.php?error=missing_data_for_cofilter' );
		exit;
	}

	$cf = new ae_CommentfilterModel();

	if( isset( $_POST['edit-id'] ) ) {
		$cf->setId( $_POST['edit-id'] );
	}

	$cf->setName( $_POST['cf-name'] );
	$cf->setMatchTarget( $_POST['cf-target'] );
	try {
		$cf->setMatchRule( $_POST['cf-match'] );
	}
	catch( Exception $exc ) {
		header( 'Location: ../admin.php?area=settings&cofilter&error=invalid_regex' );
		exit;
	}
	$cf->setAction( $_POST['cf-action'] );
	$cf->setStatus( isset( $_POST['cf-status'] ) ? $_POST['cf-status'] : ae_CommentfilterModel::STATUS_ACTIVE );

	$cf->save();

	return $cf->getId();
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

	if( isset( $_POST['edit-id'] ) ) {
		$page->setId( $_POST['edit-id'] );
	}

	$page->setTitle( $_POST['page-title'] );

	if( $permalink != '' ) {
		$page->setPermalink( $permalink );
	}

	$page->setContent( $_POST['page-content'] );
	$page->setDatetime( isset( $_POST['page-schedule'] ) ? $datetime : date( 'Y-m-d H:i:s' ) );
	$page->setCommentsStatus( $_POST['page-comments-status'] );
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

	if( isset( $_POST['edit-id'] ) ) {
		$post->setId( $_POST['edit-id'] );
	}

	$post->setTitle( $_POST['post-title'] );

	if( $permalink != '' ) {
		$post->setPermalink( $permalink );
	}

	$post->setContent( $_POST['post-content'] );
	$post->setDatetime( isset( $_POST['post-schedule'] ) ? $datetime : date( 'Y-m-d H:i:s' ) );
	$post->setCommentsStatus( $_POST['post-comments-status'] );
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
function createPost2CategoryRelations( $postId ) {
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

	$stmt = mb_substr( $stmt, 0, -2 );

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

	if( isset( $_POST['edit-id'] ) ) {
		if( !$user->load( $_POST['edit-id'] ) ) {
			return FALSE;
		}
	}

	$user->setNameInternal( $_POST['user-name-internal'] );
	$user->setNameExternal( $_POST['user-name-external'] );

	if( $permalink != '' ) {
		$user->setPermalink( $permalink );
	}

	if( $_POST['user-password'] !== '' ) {
		$user->setPasswordHash( ae_Security::hash( $_POST['user-password'] ) );
	}

	$user->setStatus( $status );
	$user->save();

	return $user->getId();
}


/**
 * Delete all relations between the edited post and its categories.
 * @param  {int}     $postId Post ID.
 * @return {boolean}         TRUE, if successful deleted relations or no relations to delete, FALSE otherwise.
 */
function deletePost2CategoryRelations( $postId ) {
	if( !isset( $_POST['edit-id'] ) ) {
		return TRUE;
	}

	$stmt = '
		DELETE FROM `' . AE_TABLE_POSTS2CATEGORIES. '`
		WHERE pc_post = :id
	';
	$params = array(
		':id' => $postId
	);

	if( ae_Database::query( $stmt, $params ) === FALSE ) {
		return FALSE;
	}

	return TRUE;
}


/**
 * Update the comment.
 * @return {int} ID of the comment.
 */
function updateComment() {
	if(
		!isset(
			$_POST['edit-id'],
			$_POST['comment-author-name'],
			$_POST['comment-author-email'],
			$_POST['comment-author-url'],
			$_POST['comment-content'],
			$_POST['comment-user']
		) ||
		$_POST['comment-content'] === ''
	) {
		header( 'Location: ../admin.php?error=missing_data_for_comment' );
		exit;
	}

	$content = nl2br( $_POST['comment-content'] );

	$comment = new ae_CommentModel();
	$comment->load( $_POST['edit-id'] );
	$comment->setAuthorName( $_POST['comment-author-name'] );
	$comment->setAuthorEmail( $_POST['comment-author-email'] );
	$comment->setAuthorUrl( $_POST['comment-author-url'] );
	$comment->setContent( $content );
	$comment->setUserId( $_POST['comment-user'] );

	if( !$comment->save() ) {
		return FALSE;
	}

	return $comment->getId();
}


/**
 * Update media.
 * @return {int} ID of the media object.
 */
function updateMedia() {
	if( !isset( $_POST['media-name'] ) || $_POST['media-name'] == '' ) {
		header( 'Location: ../admin.php?error=missing_data_for_media' );
		exit;
	}

	$media = new ae_MediaModel();
	$media->load( $_POST['edit-id'] );
	$media->setMediaPath( '../../media/' );
	$media->setName( $_POST['media-name'] );

	if( !$media->save() ) {
		return FALSE;
	}

	return $media->getId();
}



$id = FALSE;

switch( $_POST['area'] ) {

	case 'category':
		$id = createCategory();
		break;

	case 'cofilter':
		$id = createCommentfilter();
		break;

	case 'comment':
		$id = updateComment();
		break;

	case 'media':
		$id = updateMedia();
		break;

	case 'page':
		$id = createPage();
		break;

	case 'post':
		$id = createPost();
		deletePost2CategoryRelations( $id );
		createPost2CategoryRelations( $id );
		break;

	case 'user':
		$id = createUser();
		break;

}


if( $id === FALSE ) {
	ae_Log::printAll();
	exit;
}

if( $_POST['area'] == 'cofilter' ) {
	header( 'Location: ../admin.php?area=settings&cofilter&saved' );
}
else {
	header( 'Location: ../admin.php?area=edit&' . $_POST['area'] . '=' . $id . '&saved' );
}
