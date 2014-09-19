<?php

if( isset( $_GET['category'] ) ) {
	$manageArea = 'Categories';
	$list = new ae_CategoryList();
}
else if( isset( $_GET['page'] ) ) {
	$manageArea = 'Pages';
	$list = new ae_PageList();
}
else if( isset( $_GET['post'] ) ) {
	$manageArea = 'Posts';
	$list = new ae_PostList();
}
else if( isset( $_GET['user'] ) ) {
	$manageArea = 'Users';
	$list = new ae_UserList();
}
else {
	$manageArea = 'Comments';
	$list = new ae_CommentList();
}

$pageOffset = ( isset( $_GET['offset'] ) && is_numeric( $_GET['offset'] ) ) ? $_GET['offset'] : 0;

?>
<h1>Manage: <?php echo $manageArea ?></h1>

<?php while( $entry = $list->next() ): ?>


	<?php if( $manageArea == 'Categories' ): ?>


		<?php
			$linkEdit = 'admin.php?area=edit&amp;category=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?category=' . $entry->getId();
			$linkAvailable = $linkStatus . '&amp;status=' . ae_CategoryModel::STATUS_AVAILABLE;
			$linkTrash = $linkStatus . '&amp;status=' . ae_CategoryModel::STATUS_TRASH;
		?>
		<div class="manage-entry category-entry status-<?php echo $entry->getStatus() ?>">
			<input type="checkbox" name="categories[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo $entry->getTitle() ?></span>

			<div class="entry-actions">
				<a class="entry-edit" href="<?php echo $linkEdit ?>">edit</a>
				<a class="entry-available" href="<?php echo $linkAvailable ?>">available</a>
				<a class="entry-trash" href="<?php echo $linkTrash ?>">trash</a>
			</div>
		</div>


	<?php elseif( $manageArea == 'Comments' ): ?>


		<?php
			$linkEdit = 'admin.php?area=edit&amp;comment=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?comment=' . $entry->getId();
			$linkApproved = $linkStatus . '&amp;status=' . ae_CommentModel::STATUS_APPROVED;
			$linkUnapproved = $linkStatus . '&amp;status=' . ae_CommentModel::STATUS_UNAPPROVED;
			$linkSpam = $linkStatus . '&amp;status=' . ae_CommentModel::STATUS_SPAM;
			$linkTrash = $linkStatus . '&amp;status=' . ae_CommentModel::STATUS_TRASH;
		?>
		<div class="manage-entry comment-entry status-<?php echo $entry->getStatus() ?>">
			<input type="checkbox" name="comments[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo $entry->getAuthor() ?></span>

			<div class="entry-actions">
				<a class="entry-edit" href="<?php echo $linkEdit ?>">edit</a>
				<a class="entry-approve" href="<?php echo $linkApproved ?>">approve</a>
				<a class="entry-unapprove" href="<?php echo $linkUnapproved ?>">unapprove</a>
				<a class="entry-spam" href="<?php echo $linkSpam ?>">spam</a>
				<a class="entry-trash" href="<?php echo $linkTrash ?>">trash</a>
			</div>
		</div>


	<?php elseif( $manageArea == 'Pages' ): ?>


		<?php
			$linkEdit = 'admin.php?area=edit&amp;page=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?page=' . $entry->getId();
			$linkPublished = $linkStatus . '&amp;status=' . ae_PageModel::STATUS_PUBLISHED;
			$linkDraft = $linkStatus . '&amp;status=' . ae_PageModel::STATUS_DRAFT;
			$linkTrash = $linkStatus . '&amp;status=' . ae_PageModel::STATUS_TRASH;
		?>
		<div class="manage-entry page-entry status-<?php echo $entry->getStatus() ?>">
			<input type="checkbox" name="pages[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo htmlspecialchars( $entry->getTitle() ) ?></span>

			<div class="entry-actions">
				<a class="entry-edit" href="<?php echo $linkEdit ?>">edit</a>
				<a class="entry-publish" href="<?php echo $linkPublished ?>">publish</a>
				<a class="entry-draft" href="<?php echo $linkDraft ?>">draft</a>
				<a class="entry-trash" href="<?php echo $linkTrash ?>">trash</a>
			</div>
		</div>


	<?php elseif( $manageArea == 'Posts' ): ?>


		<?php
			$linkEdit = 'admin.php?area=edit&amp;post=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?post=' . $entry->getId();
			$linkPublished = $linkStatus . '&amp;status=' . ae_PostModel::STATUS_PUBLISHED;
			$linkDraft = $linkStatus . '&amp;status=' . ae_PostModel::STATUS_DRAFT;
			$linkTrash = $linkStatus . '&amp;status=' . ae_PostModel::STATUS_TRASH;
		?>
		<div class="manage-entry post-entry status-<?php echo $entry->getStatus() ?>">
			<input type="checkbox" name="posts[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo htmlspecialchars( $entry->getTitle() ) ?></span>

			<div class="entry-actions">
				<a class="entry-edit" href="<?php echo $linkEdit ?>">edit</a>
				<a class="entry-publish" href="<?php echo $linkPublished ?>">publish</a>
				<a class="entry-draft" href="<?php echo $linkDraft ?>">draft</a>
				<a class="entry-trash" href="<?php echo $linkTrash ?>">trash</a>
			</div>
		</div>


	<?php elseif( $manageArea == 'Users' ): ?>


		<?php
			$linkEdit = 'admin.php?area=edit&amp;user=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?user=' . $entry->getId();
			$linkActive = $linkStatus . '&amp;status=' . ae_UserModel::STATUS_ACTIVE;
			$linkSuspended = $linkStatus . '&amp;status=' . ae_UserModel::STATUS_SUSPENDED;
		?>
		<div class="manage-entry user-entry status-<?php echo $entry->getStatus() ?>">
			<input type="checkbox" name="users[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo htmlspecialchars( $entry->getNameInternal() ) ?></span>

			<div class="entry-actions">
				<a class="entry-edit" href="<?php echo $linkEdit ?>">edit</a>
				<a class="entry-active" href="<?php echo $linkActive ?>">active</a>
				<a class="entry-suspend" href="<?php echo $linkSuspended ?>">suspend</a>
			</div>
		</div>


	<?php endif ?>


<?php endwhile ?>


<?php
	$loadedItems = $list->getNumItems();
	$loadedItems = ( $loadedItems == 0 ) ? 1 : $loadedItems;
	$pages = ceil( $list->getTotalNumItems() / $loadedItems );
	$pageLink = 'admin.php?' . htmlspecialchars( $_SERVER['QUERY_STRING'] ) . '&amp;offset=';
?>

<nav class="manage-page-navigation">

<?php for( $i = 0; $i < $pages; $i++ ): ?>
	<?php $status = ( $i == $pageOffset ) ? ' current-offset' : '' ?>

	<a class="page-offset<?php echo $status ?>" href="<?php echo $pageLink . $i ?>"><?php echo ( $i + 1 ) ?></a>

<?php endfor ?>

</nav>
