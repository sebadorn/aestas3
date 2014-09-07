<?php

require_once( '../core/autoload.php' );
require_once( '../config.php' );

if( ae_Security::isLoggedIn() ) {
	header( 'Location: admin.php' );
	exit;
}

$username = isset( $_GET['username'] ) ? urldecode( $_GET['username'] ) : '';
$username = htmlspecialchars( $username );

?>
<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8" />
	<title>aestas › login</title>
	<meta name="robots" content="noindex" />
	<link rel="stylesheet" href="css/style.css" />
	<link rel="stylesheet" href="css/login.css" />
</head>
<body>

<form action="scripts/login.php" method="post">
	<h1>aestas</h1>

	<input type="text" name="username" id="username" placeholder="User" value="<?php echo $username ?>" />
	<input type="password" name="userpwd" id="userpwd" placeholder="Password" />

	<button type="submit" class="btn">login ›</button>
</form>

<?php ae_Log::printAll(); ?>

</body>
</html>