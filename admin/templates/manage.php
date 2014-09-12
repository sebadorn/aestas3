<?php

$manageArea = 'Comments';

if( isset( $_GET['category'] ) ) {
	$manageArea = 'Categories';
}
else if( isset( $_GET['page'] ) ) {
	$manageArea = 'Pages';
}
else if( isset( $_GET['post'] ) ) {
	$manageArea = 'Posts';
}
else if( isset( $_GET['user'] ) ) {
	$manageArea = 'Users';
}

?>
<h1>Manage: <?php echo $manageArea ?></h1>

<?php if( $manageArea == 'Comments' ): ?>



<?php elseif( $manageArea == 'Pages' ): ?>



<?php elseif( $manageArea == 'Posts' ): ?>



<?php elseif( $manageArea == 'Users' ): ?>



<?php endif ?>