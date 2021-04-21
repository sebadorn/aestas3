<?php

require_once( '../../core/autoload.php' );
require_once( '../../core/config.php' );

ae_Security::initSession();

if( !ae_Security::isLoggedIn() ) {
	header( 'Location: ../index.php?error=not_logged_in' );
	exit;
}


if(
	!isset( $_POST['blog-title'] ) ||
	!isset( $_POST['blog-description'] ) ||
	!isset( $_POST['theme'] )
) {
	header( 'Location: ../admin.php?area=settings&error=missing_data' );
	exit;
}


$stmt = '
	INSERT INTO `' . AE_TABLE_SETTINGS . '` ( s_key, s_value )
	VALUES
		( :blogTitleKey, :blogTitleValue ),
		( :blogDescriptionKey, :blogDescriptionValue ),
		( :blogThemeKey, :blogThemeValue )
	ON DUPLICATE KEY UPDATE
		s_key = VALUES( s_key ),
		s_value = VALUES( s_value )
';
$params = array(
	':blogTitleKey' => 'blog_title',
	':blogTitleValue' => $_POST['blog-title'],
	':blogDescriptionKey' => 'blog_description',
	':blogDescriptionValue' => $_POST['blog-description'],
	':blogThemeKey' => 'theme',
	':blogThemeValue' => $_POST['theme']
);

if( ae_Database::query( $stmt, $params ) === FALSE ) {
	header( 'Location: ../admin.php?area=settings&error=failed_db_update' );
	exit;
}


header( 'Location: ../admin.php?area=settings&success' );
