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

<?php if( $manageArea == 'Categories' ): ?>


	<?php $caList = new ae_CategoryList() ?>
	<?php while( $entry = $caList->next() ): ?>
		<?php
			$linkEdit = 'admin.php?area=edit&amp;category=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?category=' . $entry->getId();
			$linkAvailable = $linkStatus . '&amp;status=' . ae_CategoryModel::STATUS_AVAILABLE;
			$linkTrash = $linkStatus . '&amp;status=' . ae_CategoryModel::STATUS_TRASH;
		?>
		<div class="manage-entry category-entry">
			<input type="checkbox" name="categories[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo $entry->getTitle() ?></span>

			<div class="entry-actions">
				<a class="entry-edit" href="<?php echo $linkEdit ?>">edit</a>
				<a class="entry-available" href="<?php echo $linkAvailable ?>">available</a>
				<a class="entry-trash" href="<?php echo $linkTrash ?>">trash</a>
			</div>
		</div>
	<?php endwhile ?>


<?php elseif( $manageArea == 'Comments' ): ?>


	<?php $coList = new ae_CommentList() ?>
	<?php while( $entry = $coList->next() ): ?>
		<?php
			$linkEdit = 'admin.php?area=edit&amp;comment=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?comment=' . $entry->getId();
			$linkApproved = $linkStatus . '&amp;status=' . ae_CommentModel::STATUS_APPROVED;
			$linkUnapproved = $linkStatus . '&amp;status=' . ae_CommentModel::STATUS_UNAPPROVED;
			$linkSpam = $linkStatus . '&amp;status=' . ae_CommentModel::STATUS_SPAM;
			$linkTrash = $linkStatus . '&amp;status=' . ae_CommentModel::STATUS_TRASH;
		?>
		<div class="manage-entry comment-entry">
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
	<?php endwhile ?>


<?php elseif( $manageArea == 'Pages' ): ?>


	<?php $paList = new ae_PageList() ?>
	<?php while( $entry = $paList->next() ): ?>
		<?php
			$linkEdit = 'admin.php?area=edit&amp;page=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?page=' . $entry->getId();
			$linkPublished = $linkStatus . '&amp;status=' . ae_PageModel::STATUS_PUBLISHED;
			$linkDraft = $linkStatus . '&amp;status=' . ae_PageModel::STATUS_DRAFT;
			$linkTrash = $linkStatus . '&amp;status=' . ae_PageModel::STATUS_TRASH;
		?>
		<div class="manage-entry page-entry">
			<input type="checkbox" name="pages[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo htmlspecialchars( $entry->getTitle() ) ?></span>

			<div class="entry-actions">
				<a class="entry-edit" href="<?php echo $linkEdit ?>">edit</a>
				<a class="entry-publish" href="<?php echo $linkPublished ?>">publish</a>
				<a class="entry-draft" href="<?php echo $linkDraft ?>">draft</a>
				<a class="entry-trash" href="<?php echo $linkTrash ?>">trash</a>
			</div>
		</div>
	<?php endwhile ?>


<?php elseif( $manageArea == 'Posts' ): ?>


	<?php $poList = new ae_PostList() ?>
	<?php while( $entry = $poList->next() ): ?>
		<?php
			$linkEdit = 'admin.php?area=edit&amp;post=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?post=' . $entry->getId();
			$linkPublished = $linkStatus . '&amp;status=' . ae_PostModel::STATUS_PUBLISHED;
			$linkDraft = $linkStatus . '&amp;status=' . ae_PostModel::STATUS_DRAFT;
			$linkTrash = $linkStatus . '&amp;status=' . ae_PostModel::STATUS_TRASH;
		?>
		<div class="manage-entry post-entry">
			<input type="checkbox" name="posts[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo htmlspecialchars( $entry->getTitle() ) ?></span>

			<div class="entry-actions">
				<a class="entry-edit" href="<?php echo $linkEdit ?>">edit</a>
				<a class="entry-publish" href="<?php echo $linkPublished ?>">publish</a>
				<a class="entry-draft" href="<?php echo $linkDraft ?>">draft</a>
				<a class="entry-trash" href="<?php echo $linkTrash ?>">trash</a>
			</div>
		</div>
	<?php endwhile ?>


<?php elseif( $manageArea == 'Users' ): ?>


	<?php $uList = new ae_UserList() ?>
	<?php while( $entry = $uList->next() ): ?>
		<?php
			$linkEdit = 'admin.php?area=edit&amp;user=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?user=' . $entry->getId();
			$linkActive = $linkStatus . '&amp;status=' . ae_UserModel::STATUS_ACTIVE;
			$linkSuspended = $linkStatus . '&amp;status=' . ae_UserModel::STATUS_SUSPENDED;
		?>
		<div class="manage-entry user-entry">
			<input type="checkbox" name="users[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo htmlspecialchars( $entry->getNameInternal() ) ?></span>

			<div class="entry-actions">
				<a class="entry-edit" href="<?php echo $linkEdit ?>">edit</a>
				<a class="entry-active" href="<?php echo $linkActive ?>">active</a>
				<a class="entry-suspend" href="<?php echo $linkSuspended ?>">suspend</a>
			</div>
		</div>
	<?php endwhile ?>


<?php endif ?>