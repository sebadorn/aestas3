<?php

require_once( '../core/autoload.php' );
require_once( '../config.php' );

if( !ae_Security::isLoggedIn() ) {
	header( 'Location: index.php?error=not_logged_in' );
	exit;
}


$area = 'dashboard';

if( !isset( $_GET['area'] ) ) {
	$area = 'dashboard';
}
else if( !ae_Security::isValidArea( $_GET['area'] ) ) {
	$msg = sprintf( 'Area "%s" is not a valid area.', htmlspecialchars( $_GET['area'] ) );
	ae_Log::warning( $msg );
}
else {
	$area = $_GET['area'];
}


$sb = new ae_SiteBuilder();
include_once( 'sb_params.php' );

?>
<!DOCTYPE html>

<html>
<?php $sb->render( 'templates/head.php', $paramsHead ); ?>
<body>

<?php $sb->render( 'templates/nav.php', $paramsNav ); ?>

<section class="main-body">

	<?php $sb->render( 'templates/' . $area . '.php' ); ?>

</section>

<?php $sb->render( 'templates/footer.php' ); ?>

</body>
</html>