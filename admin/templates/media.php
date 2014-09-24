<?php

$filter = array(
);
$mediaList = new ae_MediaList( $filter );

?>
<h1>Media</h1>

<form class="media-file-upload" action="scripts/upload.php" method="post" enctype="multipart/form-data">
	<input type="file" name="upload[]" />
</form>


<form method="post" action="scripts/media-bulk.php">

<?php while( $entry = $mediaList->next() ): ?>

	<div class="manage-entry category-entry status-<?php echo $entry->getStatus() ?>">
		<input type="checkbox" name="entry[]" value="<?php echo $entry->getId() ?>" />
		<span class="entry-title"><?php echo $entry->getTitle() ?></span>

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

<?php endwhile ?>

</form>
