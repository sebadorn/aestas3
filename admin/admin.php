<?php

require_once( '../core/autoload.php' );
require_once( '../config.php' );


if( !ae_Security::isLoggedIn() ) {
	header( 'Location: index.php?error=not_logged_in' );
	exit;
}

$nav = array(
	'Dashboard' => 'dashboard',
	'Manage' => 'manage',
	'Create' => 'create'
);
?>
<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8" />
	<title>aestas › admin area</title>
	<link rel="stylesheet" href="css/style.css" />
</head>
<body>

<nav class="nav-main">

<?php foreach( $nav as $title => $link ): ?>
	<a href="admin.php?page=<?php echo $link ?>"><?php echo $title ?></a>
<?php endforeach ?>

</nav>


<?php ae_Log::printAll(); ?>

</body>
</html>