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

?>
<!DOCTYPE html>

<html>
<?php include( 'head.php' ) ?>
<body>

<?php include( 'body.php' ) ?>
<?php include( 'foot.php' ) ?>

</body>
</html>