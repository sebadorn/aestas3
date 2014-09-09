<?php

require_once( '../core/autoload.php' );
require_once( '../config.php' );


if( !ae_Security::isLoggedIn() ) {
	header( 'Location: index.php?error=not_logged_in' );
	exit;
}

$sb = new ae_SiteBuilder();
include_once( 'sb_params.php' );

?>
<!DOCTYPE html>

<html>
<?php $sb->render( 'templates/head.php', $paramsHead ); ?>
<body>

<?php $sb->render( 'templates/nav.php', $paramsNav ); ?>

<?php $sb->render( 'templates/footer.php', $paramsFooter ); ?>

<?php ae_Log::printAll(); ?>

</body>
</html>