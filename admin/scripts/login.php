<?php

require_once( '../../core/autoload.php' );
require_once( '../../config.php' );


if( !isset( $_POST['username'], $_POST['userpwd'] ) ) {
	header( 'Location: ../index.php' );
}


$hash = ae_Security::hash( $_POST['userpwd'], $_POST['username'] );
$query = '
	SELECT COUNT( u_id ) as hits, u_id, u_status
	FROM `' . AE_TABLE_USERS . '`
	WHERE u_pwd = :hash
';
$params = array(
	':hash' => $hash
);
$result = ae_Database::query( $query, $params );


// Reject: Account is suspended
if( $result[0]['u_status'] != ae_UserModel::STATUS_ACTIVE ) {
	header( 'Location: ../index.php?error=account_suspended&username=' . urlencode( $_POST['username'] ) );
	exit;
}
// Accept: Exactly one user found
else if( $result[0]['hits'] == '1' && $result[0]['u_id'] >= 0 ) {
	ae_Security::login( $result[0]['u_id'] );
	header( 'Location: ../admin.php' );
	exit;
}


if( ae_Log::hasMessages() ) {
	ae_Log::printAll();
}
else {
	header( 'Location: ../index.php?error=nomatch&username=' . urlencode( $_POST['username'] ) );
}
