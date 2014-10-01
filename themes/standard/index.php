<?php

if( ae_Permalink::isPost() ) {
	$post = ae_Permalink::getPostModel();
	$content = ( $post === FALSE ) ? '404' : 'single-post';
}
else if( ae_Permalink::isPage() ) {
	$page = ae_Permalink::getPageModel();
	$content = ( $page === FALSE ) ? '404' : 'single-page';
}
else {
	$content = 'all-posts';
}

define( 'GRAVATAR_BASE', 'https://secure.gravatar.com/avatar/' );
define( 'GRAVATAR_SIZE', 48 );

?>
<!DOCTYPE html>

<html>
<?php include( 'head.php' ) ?>
<body>

<section class="main-body">
	<?php include( 'body.php' ) ?>
</section>

<?php include( 'foot.php' ) ?>

</body>
</html>