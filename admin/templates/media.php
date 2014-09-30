<?php

$area = 'media';
$itemsPerPage = 20;
$pageOffset = ( isset( $_GET['offset'] ) && is_numeric( $_GET['offset'] ) ) ? $_GET['offset'] : 0;
$status = isset( $_GET['status'] ) ? $_GET['status'] : FALSE;
$urlBasis = '?area=media&amp;offset=' . $pageOffset;

$filter = array();
$filter['LIMIT'] = sprintf( '%d, %d', $pageOffset * $itemsPerPage, $itemsPerPage );

if( ae_MediaModel::isValidStatus( $status ) ) {
	$filter['WHERE'] = 'm_status = "' . $status . '"';
}

$mediaList = new ae_MediaList( $filter );

?>
<h1>Media</h1>

<form class="media-file-upload" action="scripts/upload.php" method="post" enctype="multipart/form-data">
	<input type="file" name="upload[]" />
	<button type="submit" class="btn btn-publish">upload</button>
</form>


<form method="post" action="scripts/manage-bulk.php">
	<input type="hidden" name="area" value="media" />


<?php include( 'manage-filter-nav.php' ) ?>
<?php include( 'manage-bulk-action.php' ) ?>


<?php while( $entry = $mediaList->next() ): ?>

	<?php
		$status = $entry->getStatus();
		$dt = explode( ' ', $entry->getDatetime() );
		$date = str_replace( '-', '/', $dt[0] );
		$basePath = '../media/' . $date . '/';
		$path = $basePath . $entry->getName();
		$meta = $entry->getMetaInfo();

		$type = htmlspecialchars( $entry->getType() );
		$icon = ae_Forms::getIcon( $entry->getType() );

		$linkEdit = 'admin.php?area=edit&amp;media=' . $entry->getId();
		$linkStatus = 'scripts/manage.php?media=' . $entry->getId();
		$linkAvailable = $linkStatus . '&amp;status=' . ae_MediaModel::STATUS_AVAILABLE;
		$linkTrash = $linkStatus . '&amp;status=' . ae_MediaModel::STATUS_TRASH;
		$linkDelete = $linkStatus . '&amp;status=delete';
	?>
	<div class="manage-entry category-entry status-<?php echo $entry->getStatus() ?>">
		<input type="checkbox" name="entry[]" value="<?php echo $entry->getId() ?>" />

		<div class="preview-image">
		<?php if( $entry->isImage() ): ?>
			<img src="<?php echo $basePath . 'tiny/' . $entry->getName() ?>" alt="" />
		<?php endif ?>
		</div>

		<div class="entry-title">
			<a href="<?php echo $path ?>"><?php echo $entry->getName() ?></a>
			<span class="upload-date"><?php echo $entry->getDatetime( 'Y-m-d' ) ?></span>
		<?php if( $entry->isImage() && isset( $meta['image_width'] ) ): ?>
			<span class="image-size"><?php echo $meta['image_width'] ?> × <?php echo $meta['image_height'] ?> pixels</span>
		<?php endif ?>
			<span class="file-size"><?php echo ae_Forms::formatSize( $meta['file_size'] ) ?></span>
			<span class="file-type icon-add-before <?php echo $icon ?>" title="<?php echo $type ?>"></span>
		</div>

		<div class="entry-actions">
			<a title="edit" class="entry-edit icon-add-before icon-before-pen" href="<?php echo $linkEdit ?>"></a>
		<?php if( $status != ae_MediaModel::STATUS_AVAILABLE ): ?>
			<a title="available" class="entry-available icon-add-before icon-before-check" href="<?php echo $linkAvailable ?>"></a>
		<?php endif ?>
		<?php if( $status != ae_MediaModel::STATUS_TRASH ): ?>
			<a title="move to trash" class="entry-trash icon-add-before icon-before-trash" href="<?php echo $linkTrash ?>"></a>
		<?php else: ?>
			<a title="delete" class="entry-delete icon-add-before icon-before-trash" href="<?php echo $linkDelete ?>"></a>
		<?php endif ?>
		</div>
	</div>

<?php endwhile ?>

</form>


<?php
	$numPages = ceil( $mediaList->getTotalNumItems() / $itemsPerPage );
	$queryStr = preg_replace( '/[?&]offset=?[0-9]*/i', '', $_SERVER['QUERY_STRING'] );
	$linkBase = 'admin.php?' . htmlspecialchars( $queryStr ) . '&amp;offset=';
?>

<nav class="manage-page-navigation">
	<?php echo ae_SiteBuilder::pagination( $numPages, $pageOffset, $linkBase ) ?>
</nav>