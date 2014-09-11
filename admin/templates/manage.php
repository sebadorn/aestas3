<?php

$manageArea = 'Comments';

if( isset( $_GET['categories'] ) ) {
	$manageArea = 'Categories';
}
else if( isset( $_GET['pages'] ) ) {
	$manageArea = 'Pages';
}
else if( isset( $_GET['posts'] ) ) {
	$manageArea = 'Posts';
}
else if( isset( $_GET['users'] ) ) {
	$manageArea = 'Users';
}

?>
<h1>Manage: <?php echo $manageArea ?></h1>

<?php if( $manageArea == 'Comments' ): ?>



<?php elseif( $manageArea == 'Pages' ): ?>



<?php elseif( $manageArea == 'Posts' ): ?>



<?php elseif( $manageArea == 'Users' ): ?>



<?php endif ?>