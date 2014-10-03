<?php

$title = ae_Settings::get( 'blog_title' );

if( ae_Permalink::isPost() ) {
	$post = ae_Permalink::getPostModel();

	if( $post === FALSE ) {
		$content = '404';
		$title = '404 | ' . $title;
	}
	else {
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
	else {
		$content = 'single-page';
		$title = htmlspecialchars( $page->getTitle() ) . ' | ' . $title;
	}
}
else if( isset( $_GET['search'] ) ) {
	$content = 'search';
}
else {
	$content = 'all-posts';
}

define( 'COMMENT_AUTHOR_DEFAULT_NAME', 'Namenlos' );
define( 'GRAVATAR_BASE', 'https://secure.gravatar.com/avatar/' );
define( 'GRAVATAR_SIZE', 48 );
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
<?php $sb->render( 'sidebar.php' ) ?>

<section class="main-body">
	<?php include( 'body.php' ) ?>
</section>

<?php $sb->render( 'foot.php' ) ?>

</body>
</html>