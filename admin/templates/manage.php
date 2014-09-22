<?php

$pageOffset = ( isset( $_GET['offset'] ) && is_numeric( $_GET['offset'] ) ) ? $_GET['offset'] : 0;
$itemsPerPage = 20;
$status = isset( $_GET['status'] ) ? $_GET['status'] : FALSE;

$filter = array();
$filter['LIMIT'] = sprintf( '%d, %d', $pageOffset * $itemsPerPage, $itemsPerPage );


// categories
if( isset( $_GET['category'] ) ) {
	$manageArea = 'Categories';

	if( ae_CategoryModel::isValidStatus( $status ) ) {
		$filter['WHERE'] = 'ca_status = "' . $status . '"';
	}

	$list = new ae_CategoryList( $filter );
}

// pages
else if( isset( $_GET['page'] ) ) {
	$manageArea = 'Pages';

	if( ae_PageModel::isValidStatus( $status ) ) {
		$filter['WHERE'] = 'pa_status = "' . $status . '"';
	}
	else {
		$filter['WHERE'] = 'pa_status != "trash"';
	}

	$list = new ae_PageList( $filter );
}

// posts
else if( isset( $_GET['post'] ) ) {
	$manageArea = 'Posts';

	if( ae_PostModel::isValidStatus( $status ) ) {
		$filter['WHERE'] = 'po_status = "' . $status . '"';
	}
	else {
		$filter['WHERE'] = 'po_status != "trash"';
	}

	$list = new ae_PostList( $filter );
}

// users
else if( isset( $_GET['user'] ) ) {
	$manageArea = 'Users';

	if( ae_UserModel::isValidStatus( $status ) ) {
		$filter['WHERE'] = 'u_status = "' . $status . '"';
	}

	$list = new ae_UserList( $filter );
}

// comments
else {
	$manageArea = 'Comments';

	if( ae_CommentModel::isValidStatus( $status ) ) {
		$filter['WHERE'] = 'co_status = "' . $status . '"';
	}
	else {
		$filter['WHERE'] = 'co_status != "trash" AND co_status != "spam"';
	}

	$list = new ae_CommentList( $filter );
}

$urlBasis = '?area=manage&amp;offset=' . $pageOffset . '&amp;';

?>
<h1>Manage: <?php echo $manageArea ?></h1>


<nav class="filter-status-nav">

<?php if( $manageArea == 'Categories' ): ?>
	<?php $urlBasis .= 'category' ?>

	<a class="filter-status-none" href="<?php echo $urlBasis ?>"><em>default</em></a>,
	<a class="filter-status-available" href="<?php echo $urlBasis ?>&amp;status=available">available</a>,
	<a class="filter-status-trash" href="<?php echo $urlBasis ?>&amp;status=trash">trash</a>

<?php elseif( $manageArea == 'Comments' ): ?>
	<?php $urlBasis .= 'comment' ?>

	<a class="filter-status-none" href="<?php echo $urlBasis ?>"><em>default</em></a>,
	<a class="filter-status-approved" href="<?php echo $urlBasis ?>&amp;status=approved">approved</a>,
	<a class="filter-status-unapproved" href="<?php echo $urlBasis ?>&amp;status=unapproved">unapproved</a>,
	<a class="filter-status-spam" href="<?php echo $urlBasis ?>&amp;status=spam">spam</a>,
	<a class="filter-status-trash" href="<?php echo $urlBasis ?>&amp;status=trash">trash</a>

<?php elseif( $manageArea == 'Pages' ): ?>
	<?php $urlBasis .= 'page' ?>

	<a class="filter-status-none" href="<?php echo $urlBasis ?>"><em>default</em></a>,
	<a class="filter-status-published" href="<?php echo $urlBasis ?>&amp;status=published">published</a>,
	<a class="filter-status-draft" href="<?php echo $urlBasis ?>&amp;status=draft">draft</a>,
	<a class="filter-status-trash" href="<?php echo $urlBasis ?>&amp;status=trash">trash</a>

<?php elseif( $manageArea == 'Posts' ): ?>
	<?php $urlBasis .= 'post' ?>

	<a class="filter-status-none" href="<?php echo $urlBasis ?>"><em>default</em></a>,
	<a class="filter-status-published" href="<?php echo $urlBasis ?>&amp;status=published">published</a>,
	<a class="filter-status-draft" href="<?php echo $urlBasis ?>&amp;status=draft">draft</a>,
	<a class="filter-status-trash" href="<?php echo $urlBasis ?>&amp;status=trash">trash</a>

<?php elseif( $manageArea == 'Users' ): ?>
	<?php $urlBasis .= 'user' ?>

	<a class="filter-status-none" href="<?php echo $urlBasis ?>"><em>default</em></a>,
	<a class="filter-status-active" href="<?php echo $urlBasis ?>&amp;status=active">active</a>,
	<a class="filter-status-suspended" href="<?php echo $urlBasis ?>&amp;status=suspended">suspended</a>

<?php endif ?>

</nav>


<?php while( $entry = $list->next() ): ?>
	<?php $status = $entry->getStatus() ?>

	<?php if( $manageArea == 'Categories' ): ?>


		<?php
			$linkEdit = 'admin.php?area=edit&amp;category=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?category=' . $entry->getId();
			$linkAvailable = $linkStatus . '&amp;status=' . ae_CategoryModel::STATUS_AVAILABLE;
			$linkTrash = $linkStatus . '&amp;status=' . ae_CategoryModel::STATUS_TRASH;
			$linkDelete = $linkStatus . '&amp;status=delete';
		?>
		<div class="manage-entry category-entry status-<?php echo $entry->getStatus() ?>">
			<input type="checkbox" name="categories[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo $entry->getTitle() ?></span>

			<div class="entry-actions">
			<?php if( $status != ae_CategoryModel::STATUS_AVAILABLE ): ?>
				<a title="available" class="entry-available icon-add-before icon-before-check" href="<?php echo $linkAvailable ?>"></a>
			<?php endif ?>
				<a title="edit" class="entry-edit icon-add-before icon-before-pen" href="<?php echo $linkEdit ?>"></a>
			<?php if( $status != ae_CategoryModel::STATUS_TRASH ): ?>
				<a title="move to trash" class="entry-trash icon-add-before icon-before-trash" href="<?php echo $linkTrash ?>"></a>
			<?php else: ?>
				<a title="delete" class="entry-delete icon-add-before icon-before-trash" href="<?php echo $linkDelete ?>"></a>
			<?php endif ?>
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
			$linkDelete = $linkStatus . '&amp;status=delete';
		?>
		<div class="manage-entry comment-entry status-<?php echo $entry->getStatus() ?>">
			<input type="checkbox" name="comments[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo $entry->getAuthorName() ?></span>

			<div class="entry-actions">
			<?php if( $status != ae_CommentModel::STATUS_APPROVED ): ?>
				<a title="approve" class="entry-approve icon-add-before icon-before-check" href="<?php echo $linkApproved ?>"></a>
			<?php endif ?>
			<?php if( $status != ae_CommentModel::STATUS_UNAPPROVED ): ?>
				<a title="unapprove" class="entry-unapprove icon-add-before icon-before-x" href="<?php echo $linkUnapproved ?>"></a>
			<?php endif ?>
			<?php if( $status != ae_CommentModel::STATUS_SPAM ): ?>
				<a title="spam" class="entry-spam icon-add-before icon-before-ban" href="<?php echo $linkSpam ?>"></a>
			<?php endif ?>
				<a title="edit" class="entry-edit icon-add-before icon-before-pen" href="<?php echo $linkEdit ?>"></a>
			<?php if( $status != ae_CommentModel::STATUS_TRASH ): ?>
				<a title="move to trash" class="entry-trash icon-add-before icon-before-trash" href="<?php echo $linkTrash ?>"></a>
			<?php else: ?>
				<a title="delete" class="entry-delete icon-add-before icon-before-trash" href="<?php echo $linkDelete ?>"></a>
			<?php endif ?>
			</div>
		</div>


	<?php elseif( $manageArea == 'Pages' ): ?>


		<?php
			$linkEdit = 'admin.php?area=edit&amp;page=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?page=' . $entry->getId();
			$linkPublished = $linkStatus . '&amp;status=' . ae_PageModel::STATUS_PUBLISHED;
			$linkDraft = $linkStatus . '&amp;status=' . ae_PageModel::STATUS_DRAFT;
			$linkTrash = $linkStatus . '&amp;status=' . ae_PageModel::STATUS_TRASH;
			$linkDelete = $linkStatus . '&amp;status=delete';
		?>
		<div class="manage-entry page-entry status-<?php echo $entry->getStatus() ?>">
			<input type="checkbox" name="pages[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo htmlspecialchars( $entry->getTitle() ) ?></span>

			<div class="entry-actions">
			<?php if( $status != ae_PostModel::STATUS_PUBLISHED ): ?>
				<a title="publish" class="entry-publish icon-add-before icon-before-check" href="<?php echo $linkPublished ?>"></a>
			<?php endif ?>
			<?php if( $status != ae_PostModel::STATUS_DRAFT ): ?>
				<a title="draft" class="entry-draft icon-add-before icon-before-script" href="<?php echo $linkDraft ?>"></a>
			<?php endif ?>
				<a title="edit" class="entry-edit icon-add-before icon-before-pen" href="<?php echo $linkEdit ?>"></a>
			<?php if( $status != ae_PostModel::STATUS_TRASH ): ?>
				<a title="move to trash" class="entry-trash icon-add-before icon-before-trash" href="<?php echo $linkTrash ?>"></a>
			<?php else: ?>
				<a title="delete" class="entry-delete icon-add-before icon-before-trash" href="<?php echo $linkDelete ?>"></a>
			<?php endif ?>
			</div>
		</div>


	<?php elseif( $manageArea == 'Posts' ): ?>


		<?php
			$linkEdit = 'admin.php?area=edit&amp;post=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?post=' . $entry->getId();
			$linkPublished = $linkStatus . '&amp;status=' . ae_PostModel::STATUS_PUBLISHED;
			$linkDraft = $linkStatus . '&amp;status=' . ae_PostModel::STATUS_DRAFT;
			$linkTrash = $linkStatus . '&amp;status=' . ae_PostModel::STATUS_TRASH;
			$linkDelete = $linkStatus . '&amp;status=delete';
		?>
		<div class="manage-entry post-entry status-<?php echo $entry->getStatus() ?>">
			<input type="checkbox" name="posts[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo htmlspecialchars( $entry->getTitle() ) ?></span>

			<div class="entry-actions">
			<?php if( $status != ae_PostModel::STATUS_PUBLISHED ): ?>
				<a title="publish" class="entry-publish icon-add-before icon-before-check" href="<?php echo $linkPublished ?>"></a>
			<?php endif ?>
			<?php if( $status != ae_PostModel::STATUS_DRAFT ): ?>
				<a title="draft" class="entry-draft icon-add-before icon-before-script" href="<?php echo $linkDraft ?>"></a>
			<?php endif ?>
				<a title="edit" class="entry-edit icon-add-before icon-before-pen" href="<?php echo $linkEdit ?>"></a>
			<?php if( $status != ae_PostModel::STATUS_TRASH ): ?>
				<a title="move to trash" class="entry-trash icon-add-before icon-before-trash" href="<?php echo $linkTrash ?>"></a>
			<?php else: ?>
				<a title="delete" class="entry-delete icon-add-before icon-before-trash" href="<?php echo $linkDelete ?>"></a>
			<?php endif ?>
			</div>
		</div>


	<?php elseif( $manageArea == 'Users' ): ?>


		<?php
			$linkEdit = 'admin.php?area=edit&amp;user=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?user=' . $entry->getId();
			$linkActive = $linkStatus . '&amp;status=' . ae_UserModel::STATUS_ACTIVE;
			$linkSuspended = $linkStatus . '&amp;status=' . ae_UserModel::STATUS_SUSPENDED;
			$linkDelete = $linkStatus . '&amp;status=delete';
		?>
		<div class="manage-entry user-entry status-<?php echo $entry->getStatus() ?>">
			<input type="checkbox" name="users[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo htmlspecialchars( $entry->getNameInternal() ) ?></span>

			<div class="entry-actions">
			<?php if( $status != ae_UserModel::STATUS_ACTIVE ): ?>
				<a title="activate" class="entry-active icon-add-before icon-before-check" href="<?php echo $linkActive ?>"></a>
			<?php endif ?>
			<?php if( $status != ae_UserModel::STATUS_SUSPENDED ): ?>
				<a title="suspend" class="entry-suspend icon-add-before icon-before-ban" href="<?php echo $linkSuspended ?>"></a>
			<?php endif ?>
				<a title="edit" class="entry-edit icon-add-before icon-before-pen" href="<?php echo $linkEdit ?>"></a>
			<?php if( $status == ae_UserModel::STATUS_SUSPENDED ): ?>
				<a title="delete" class="entry-delete icon-add-before icon-before-trash" href="<?php echo $linkDelete ?>"></a>
			<?php endif ?>
			</div>
		</div>


	<?php endif ?>


<?php endwhile ?>


<?php
	$numPages = ceil( $list->getTotalNumItems() / $itemsPerPage );
	$queryStr = preg_replace( '/[?&]offset=?[0-9]*/i', '', $_SERVER['QUERY_STRING'] );
	$linkBase = 'admin.php?' . htmlspecialchars( $queryStr ) . '&amp;offset=';
?>

<nav class="manage-page-navigation">
	<?php echo ae_SiteBuilder::pagination( $numPages, $pageOffset, $linkBase ) ?>
</nav>
