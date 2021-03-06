<?php

require_once( '../../core/autoload.php' );
require_once( '../../core/config.php' );


if( !isset( $_POST['username'], $_POST['userpwd'] ) ) {
	header( 'Location: ../index.php' );
}


$query = '
	SELECT COUNT( u_id ) as hits, u_id, u_pwd, u_status
	FROM `' . AE_TABLE_USERS . '`
	WHERE u_name_intern = :name
';
$params = array(
	':name' => $_POST['username']
);
$result = ae_Database::query( $query, $params );
$u = $result[0];


// Reject: Account is suspended
if( $u['hits'] == '1' && $u['u_status'] != ae_UserModel::STATUS_ACTIVE ) {
	header( 'Location: ../index.php?error=account_suspended&username=' . urlencode( $_POST['username'] ) );
	exit;
}
// Accept: Exactly one user found
else if( $u['hits'] == '1' && $u['u_id'] >= 0 && ae_Security::verify( $_POST['userpwd'], $u['u_pwd'] ) ) {
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
