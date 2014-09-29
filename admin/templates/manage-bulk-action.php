<?php

if( $area == 'category' ) {
	$statuses = ae_CategoryModel::listStatuses();
}
else if( $area == 'comment' ) {
	$statuses = ae_CommentModel::listStatuses();
}
else if( $area == 'media' ) {
	$statuses = ae_MediaModel::listStatuses();
}
else if( $area == 'page' ) {
	$statuses = ae_PageModel::listStatuses();
}
else if( $area == 'post' ) {
	$statuses = ae_PostModel::listStatuses();
}
else if( $area == 'user' ) {
	$statuses = ae_UserModel::listStatuses();
}


$select = ae_Forms::selectStatus( 'bulk-status-change', $statuses );

if( isset( $_GET['status'] ) && $_GET['status'] == 'trash' ) {
	$select = str_replace( 'trash', 'delete', $select );
}

?>

<div class="bulk-action">
	<?php echo $select ?>
	<button type="submit" class="btn btn-publish">apply</button>
</div>
