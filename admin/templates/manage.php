<?php

$pageOffset = ( isset( $_GET['offset'] ) && is_numeric( $_GET['offset'] ) ) ? $_GET['offset'] : 0;
$itemsPerPage = 20;
$status = isset( $_GET['status'] ) ? $_GET['status'] : FALSE;

$filter = array();
$filter['LIMIT'] = sprintf( '%d, %d', $pageOffset * $itemsPerPage, $itemsPerPage );


// categories
if( isset( $_GET['category'] ) ) {
	$area = 'category';
	$areaName = 'Categories';

	if( ae_CategoryModel::isValidStatus( $status ) ) {
		$filter['WHERE'] = 'ca_status = "' . $status . '"';
	}

	$list = new ae_CategoryList( $filter );
}

// pages
else if( isset( $_GET['page'] ) ) {
	$area = 'page';
	$areaName = 'Pages';

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
	$area = 'post';
	$areaName = 'Posts';

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
	$area = 'user';
	$areaName = 'Users';

	if( ae_UserModel::isValidStatus( $status ) ) {
		$filter['WHERE'] = 'u_status = "' . $status . '"';
	}

	$list = new ae_UserList( $filter );
}

// comments
else {
	$area = 'comment';
	$areaName = 'Comments';

	if( ae_CommentModel::isValidStatus( $status ) ) {
		$filter['WHERE'] = 'co_status = "' . $status . '"';
	}
	else {
		$filter['WHERE'] = 'co_status != "trash" AND co_status != "spam"';
	}

	$list = new ae_CommentList( $filter );
}

$urlBasis = '?area=manage&amp;offset=' . $pageOffset . '&amp;' . $area;

?>
<h1>Manage: <?php echo $areaName ?></h1>

<form method="post" action="scripts/manage-bulk.php">
	<input type="hidden" name="area" value="<?php echo $area ?>" />

<?php include( 'manage-filter-nav.php' ) ?>
<?php include( 'manage-bulk-action.php' ) ?>


<?php while( $entry = $list->next() ): ?>
	<?php $status = $entry->getStatus() ?>

	<?php if( $area == 'category' ): ?>


		<?php
			$linkEdit = 'admin.php?area=edit&amp;category=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?category=' . $entry->getId();
			$linkAvailable = $linkStatus . '&amp;status=' . ae_CategoryModel::STATUS_AVAILABLE;
			$linkTrash = $linkStatus . '&amp;status=' . ae_CategoryModel::STATUS_TRASH;
			$linkDelete = $linkStatus . '&amp;status=delete';
		?>
		<div class="manage-entry category-entry status-<?php echo $entry->getStatus() ?>">
			<input type="checkbox" name="entry[]" value="<?php echo $entry->getId() ?>" />

			<div class="entry-title">
				<span class="entry-id">#<?php echo $entry->getId() ?></span>
				<a href="<?php echo $entry->getLink( '../' ) ?>"><?php echo $entry->getTitle() ?></a>
			</div>

			<div class="entry-actions">
				<a title="edit" class="entry-edit icon-add-before icon-before-pen" href="<?php echo $linkEdit ?>"></a>
			<?php if( $status != ae_CategoryModel::STATUS_AVAILABLE ): ?>
				<a title="available" class="entry-available icon-add-before icon-before-check" href="<?php echo $linkAvailable ?>"></a>
			<?php endif ?>
			<?php if( $status != ae_CategoryModel::STATUS_TRASH ): ?>
				<a title="move to trash" class="entry-trash icon-add-before icon-before-trash" href="<?php echo $linkTrash ?>"></a>
			<?php else: ?>
				<a title="delete" class="entry-delete icon-add-before icon-before-trash" href="<?php echo $linkDelete ?>"></a>
			<?php endif ?>
			</div>
		</div>


	<?php elseif( $area == 'comment' ): ?>


		<?php
			$linkEdit = 'admin.php?area=edit&amp;comment=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?comment=' . $entry->getId();
			$linkApproved = $linkStatus . '&amp;status=' . ae_CommentModel::STATUS_APPROVED;
			$linkUnapproved = $linkStatus . '&amp;status=' . ae_CommentModel::STATUS_UNAPPROVED;
			$linkSpam = $linkStatus . '&amp;status=' . ae_CommentModel::STATUS_SPAM;
			$linkTrash = $linkStatus . '&amp;status=' . ae_CommentModel::STATUS_TRASH;
			$linkDelete = $linkStatus . '&amp;status=delete';

			$linkPost = '../?' . PERMALINK_GET_POST . '=' . $entry->getPostId() . '#comment-' . $entry->getId();
		?>
		<div class="manage-entry comment-entry status-<?php echo $entry->getStatus() ?>">
			<input type="checkbox" name="entry[]" value="<?php echo $entry->getId() ?>" />

			<div class="entry-title">
				<span class="entry-id">#<?php echo $entry->getId() ?></span>
				<span class="entry-date"><?php echo $entry->getDatetime( 'Y-m-d' ) ?></span>
				<a href="<?php echo $linkPost ?>"><?php echo $entry->getAuthorName() ?></a>
			<?php if( $entry->getUserId() > 0 ): ?>
				<span class="is-user icon-add-before icon-before-person icon-only" title="registered user (ID: <?php echo $entry->getUserId() ?>)"></span>
			<?php endif ?>
			<?php if( $entry->getAuthorUrl() != '' ): ?>
				<a class="url icon-add-before icon-before-home icon-only" href="<?php echo htmlspecialchars( $entry->getAuthorUrl() )?>"></a>
			<?php endif ?>
			<?php if( $entry->getAuthorEmail() != '' ): ?>
				<a class="url icon-add-before icon-before-mail icon-only" href="mailto:<?php echo htmlspecialchars( $entry->getAuthorEmail() )?>"></a>
			<?php endif ?>
			</div>

			<div class="entry-actions">
				<a title="edit" class="entry-edit icon-add-before icon-before-pen" href="<?php echo $linkEdit ?>"></a>
			<?php if( $status != ae_CommentModel::STATUS_APPROVED ): ?>
				<a title="approve" class="entry-approve icon-add-before icon-before-check" href="<?php echo $linkApproved ?>"></a>
			<?php endif ?>
			<?php if( $status != ae_CommentModel::STATUS_UNAPPROVED ): ?>
				<a title="unapprove" class="entry-unapprove icon-add-before icon-before-x" href="<?php echo $linkUnapproved ?>"></a>
			<?php endif ?>
			<?php if( $status != ae_CommentModel::STATUS_SPAM ): ?>
				<a title="spam" class="entry-spam icon-add-before icon-before-ban" href="<?php echo $linkSpam ?>"></a>
			<?php endif ?>
			<?php if( $status != ae_CommentModel::STATUS_TRASH ): ?>
				<a title="move to trash" class="entry-trash icon-add-before icon-before-trash" href="<?php echo $linkTrash ?>"></a>
			<?php else: ?>
				<a title="delete" class="entry-delete icon-add-before icon-before-trash" href="<?php echo $linkDelete ?>"></a>
			<?php endif ?>
			</div>
		</div>


	<?php elseif( $area == 'page' ): ?>


		<?php
			$linkEdit = 'admin.php?area=edit&amp;page=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?page=' . $entry->getId();
			$linkPublished = $linkStatus . '&amp;status=' . ae_PageModel::STATUS_PUBLISHED;
			$linkDraft = $linkStatus . '&amp;status=' . ae_PageModel::STATUS_DRAFT;
			$linkTrash = $linkStatus . '&amp;status=' . ae_PageModel::STATUS_TRASH;
			$linkDelete = $linkStatus . '&amp;status=delete';
		?>
		<div class="manage-entry page-entry status-<?php echo $entry->getStatus() ?>">
			<input type="checkbox" name="entry[]" value="<?php echo $entry->getId() ?>" />

			<div class="entry-title">
				<span class="entry-id">#<?php echo $entry->getId() ?></span>
				<span class="entry-date"><?php echo $entry->getDatetime( 'Y-m-d' ) ?></span>
				<?php echo htmlspecialchars( $entry->getTitle() ) ?>
			</div>

			<div class="entry-actions">
				<a title="edit" class="entry-edit icon-add-before icon-before-pen" href="<?php echo $linkEdit ?>"></a>
			<?php if( $status != ae_PostModel::STATUS_PUBLISHED ): ?>
				<a title="publish" class="entry-publish icon-add-before icon-before-check" href="<?php echo $linkPublished ?>"></a>
			<?php endif ?>
			<?php if( $status != ae_PostModel::STATUS_DRAFT ): ?>
				<a title="draft" class="entry-draft icon-add-before icon-before-script" href="<?php echo $linkDraft ?>"></a>
			<?php endif ?>
			<?php if( $status != ae_PostModel::STATUS_TRASH ): ?>
				<a title="move to trash" class="entry-trash icon-add-before icon-before-trash" href="<?php echo $linkTrash ?>"></a>
			<?php else: ?>
				<a title="delete" class="entry-delete icon-add-before icon-before-trash" href="<?php echo $linkDelete ?>"></a>
			<?php endif ?>
			</div>
		</div>


	<?php elseif( $area == 'post' ): ?>


		<?php
			$linkEdit = 'admin.php?area=edit&amp;post=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?post=' . $entry->getId();
			$linkPublished = $linkStatus . '&amp;status=' . ae_PostModel::STATUS_PUBLISHED;
			$linkDraft = $linkStatus . '&amp;status=' . ae_PostModel::STATUS_DRAFT;
			$linkTrash = $linkStatus . '&amp;status=' . ae_PostModel::STATUS_TRASH;
			$linkDelete = $linkStatus . '&amp;status=delete';
		?>
		<div class="manage-entry post-entry status-<?php echo $entry->getStatus() ?>">
			<input type="checkbox" name="entry[]" value="<?php echo $entry->getId() ?>" />

			<div class="entry-title">
				<span class="entry-id">#<?php echo $entry->getId() ?></span>
				<span class="entry-date"><?php echo $entry->getDatetime( 'Y-m-d' ) ?></span>
				<a href="<?php echo $entry->getLink( '../' ) ?>"><?php echo htmlspecialchars( $entry->getTitle() ) ?></a>
			</div>

			<div class="entry-actions">
				<a title="edit" class="entry-edit icon-add-before icon-before-pen" href="<?php echo $linkEdit ?>"></a>
			<?php if( $status != ae_PostModel::STATUS_PUBLISHED ): ?>
				<a title="publish" class="entry-publish icon-add-before icon-before-check" href="<?php echo $linkPublished ?>"></a>
			<?php endif ?>
			<?php if( $status != ae_PostModel::STATUS_DRAFT ): ?>
				<a title="draft" class="entry-draft icon-add-before icon-before-script" href="<?php echo $linkDraft ?>"></a>
			<?php endif ?>
			<?php if( $status != ae_PostModel::STATUS_TRASH ): ?>
				<a title="move to trash" class="entry-trash icon-add-before icon-before-trash" href="<?php echo $linkTrash ?>"></a>
			<?php else: ?>
				<a title="delete" class="entry-delete icon-add-before icon-before-trash" href="<?php echo $linkDelete ?>"></a>
			<?php endif ?>
			</div>
		</div>


	<?php elseif( $area == 'user' ): ?>


		<?php
			$linkEdit = 'admin.php?area=edit&amp;user=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?user=' . $entry->getId();
			$linkActive = $linkStatus . '&amp;status=' . ae_UserModel::STATUS_ACTIVE;
			$linkSuspended = $linkStatus . '&amp;status=' . ae_UserModel::STATUS_SUSPENDED;
			$linkDelete = $linkStatus . '&amp;status=delete';
		?>
		<div class="manage-entry user-entry status-<?php echo $entry->getStatus() ?>">
			<input type="checkbox" name="entry[]" value="<?php echo $entry->getId() ?>" />

			<div class="entry-title">
				<span class="entry-id">#<?php echo $entry->getId() ?></span>
				<a href="<?php echo $entry->getLink( '../' ) ?>"><?php echo htmlspecialchars( $entry->getNameInternal() ) ?></a>
			</div>

			<div class="entry-actions">
				<a title="edit" class="entry-edit icon-add-before icon-before-pen" href="<?php echo $linkEdit ?>"></a>
			<?php if( $status != ae_UserModel::STATUS_ACTIVE ): ?>
				<a title="activate" class="entry-active icon-add-before icon-before-check" href="<?php echo $linkActive ?>"></a>
			<?php endif ?>
			<?php if( $status != ae_UserModel::STATUS_SUSPENDED ): ?>
				<a title="suspend" class="entry-suspend icon-add-before icon-before-ban" href="<?php echo $linkSuspended ?>"></a>
			<?php endif ?>
			<?php if( $status == ae_UserModel::STATUS_SUSPENDED ): ?>
				<a title="delete" class="entry-delete icon-add-before icon-before-trash" href="<?php echo $linkDelete ?>"></a>
			<?php endif ?>
			</div>
		</div>


	<?php endif ?>


<?php endwhile ?>

</form>


<?php
	$numPages = ceil( $list->getTotalNumItems() / $itemsPerPage );
	$queryStr = preg_replace( '/[?&]offset=?[0-9]*/i', '', $_SERVER['QUERY_STRING'] );
	$linkBase = 'admin.php?' . htmlspecialchars( $queryStr ) . '&amp;offset=';
?>

<nav class="manage-page-navigation">
	<?php echo ae_SiteBuilder::pagination( $numPages, $pageOffset, $linkBase ) ?>
</nav>
