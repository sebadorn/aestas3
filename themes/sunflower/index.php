<?php

$content = NULL;
$title = ae_Settings::get( 'blog_title' );

if( ae_Permalink::isPost() ) {
	$post = ae_Permalink::getPostModel();

	if( $post === FALSE ) {
		$content = '404';
		$title = '404 | ' . $title;
	}
	else if( $post->getStatus() !== ae_PageModel::STATUS_PUBLISHED ) {
		ae_Security::initSession();

		if( !ae_Security::isLoggedIn() ) {
			$content = '404';
			$title = '404 | ' . $title;
		}
	}

	if( $content === NULL ) {
		$content = 'single-post';
		$title = htmlspecialchars( $post->getTitle() ) . ' | ' . $title;
	}
}
else if( ae_Permalink::isPage() ) {
	$page = ae_Permalink::getPageModel();

	if( $page === FALSE ) {
		$content = '404';
		$title = '404 | ' . $title;
	}
	else if( $page->getStatus() !== ae_PageModel::STATUS_PUBLISHED ) {
		ae_Security::initSession();

		if( !ae_Security::isLoggedIn() ) {
			$content = '404';
			$title = '404 | ' . $title;
		}
	}

	if( $content === NULL ) {
		$content = 'single-page';
		$title = htmlspecialchars( $page->getTitle() ) . ' | ' . $title;
	}
}
else if( isset( $_GET[PERMALINK_GET_SEARCH] ) ) {
	$content = 'search';
}
else {
	$content = 'all-posts';
}

// HTTP 404: Not found
if( $content == '404' ) {
	header( 'HTTP/1.0 404 Not Found' );
}

define( 'IS_SINGLE_POST', ( $content == 'single-post' ) );
define( 'POSTS_OFFSET', max( ae_Permalink::getPostOffset() - 1, 0 ) );
define( 'POSTS_PER_PAGE', 5 );

$sb = new ae_SiteBuilder();
$sb->setBasePath( 'themes/' . THEME );

?>
<!DOCTYPE html>

<html>
<?php include( 'head.php' ) ?>
<body>

<?php $sb->render( 'header.php' ) ?>

<section class="main-body">
	<?php include( 'body.php' ) ?>
</section>

<?php $sb->render( 'foot.php' ) ?>

</body>
</html>