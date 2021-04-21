<?php

// comment filters
if( isset( $_GET['cofilter'] ) ) {
	$area = 'cofilter';
	$areaName = 'Comment filters';

	$pageOffset = ( isset( $_GET['offset'] ) && is_numeric( $_GET['offset'] ) ) ? $_GET['offset'] : 0;
	$itemsPerPage = 20;
	$status = isset( $_GET['status'] ) ? $_GET['status'] : FALSE;

	$filter = array();
	$filter['LIMIT'] = sprintf( '%d, %d', $pageOffset * $itemsPerPage, $itemsPerPage );

	if( ae_CommentfilterModel::isValidStatus( $status ) ) {
		$filter['WHERE'] = 'cf_status = "' . $status . '"';
	}

	$list = new ae_CommentfilterList( $filter );

	$urlBasis = '?area=settings&amp;offset=' . $pageOffset . '&amp;' . $area;
}
// general
else {
	$area = 'general';
	$areaName = 'General';
}

?>

<h1>Settings: <?php echo $areaName ?></h1>


<?php if( $area == 'general' ): ?>

<form class="settings" method="post" action="scripts/settings.php">

	<?php
		$themes = ae_Settings::getListOfThemes( '../themes/' );
		$theme_now = ae_Settings::get( 'theme' );
	?>

	<div class="setting">
		<label for="set-blog-title">Blog title</label>
		<input type="text" name="blog-title" id="set-blog-title" placeholder="Blog title" value="<?php echo ae_Settings::get( 'blog_title' ) ?>" />
	</div>

	<div class="setting">
		<label for="set-blog-description">Blog description</label>
		<input type="text" name="blog-description" id="set-blog-description" placeholder="Blog description" value="<?php echo ae_Settings::get( 'blog_description' ) ?>" />
	</div>

	<div class="setting">
		<label for="set-theme">Blog theme</label>
		<select name="theme">
		<?php foreach( $themes as $t ): ?>
			<?php $t = htmlspecialchars( $t ) ?>
			<option value="<?php echo $t ?>"<?php if( $theme_now == $t ) { echo ' selected'; } ?>><?php echo $t ?></option>
		<?php endforeach ?>
		</select>
	</div>

	<button type="submit" class="btn btn-publish">save</button>

</form>


<?php elseif( $area == 'cofilter' ): ?>


<?php

$actions = array(
	ae_CommentfilterModel::ACTION_SPAM => 'Move to spam',
	ae_CommentfilterModel::ACTION_TRASH => 'Move to trash',
	ae_CommentfilterModel::ACTION_DROP => 'Drop comment',
	ae_CommentfilterModel::ACTION_APPROVE => 'Approve comment',
	ae_CommentfilterModel::ACTION_UNAPPROVE => 'Unapprove comment'
);
$targets = array(
	ae_CommentfilterModel::TARGET_IP => 'Author IP',
	ae_CommentfilterModel::TARGET_NAME => 'Author name',
	ae_CommentfilterModel::TARGET_EMAIL => 'Author eMail',
	ae_CommentfilterModel::TARGET_URL => 'Author URL',
	ae_CommentfilterModel::TARGET_USERID => 'Author user ID',
	ae_CommentfilterModel::TARGET_CONTENT => 'Comment content'
);

?>

<form class="settings create-filter" method="post" action="scripts/create.php">
	<input type="hidden" name="area" value="cofilter" />

	<input type="text" name="cf-name" placeholder="Filter name" />:
	If <select name="cf-target">
	<?php foreach( $targets as $key => $val ): ?>
		<?php $key = htmlspecialchars( $key ) ?>
		<option value="<?php echo $key ?>"><?php echo $val ?></option>
	<?php endforeach ?>
	</select>
	matches <input type="text" name="cf-match" placeholder="Filter regex" />
	then <select name="cf-action">
	<?php foreach( $actions as $key => $val ): ?>
		<?php $key = htmlspecialchars( $key ) ?>
		<option value="<?php echo $key ?>"><?php echo $val ?></option>
	<?php endforeach ?>
	</select>

	<button type="submit" name="submit" class="btn btn-publish">save</button>
</form>


<form method="post" action="scripts/manage-bulk.php">
	<input type="hidden" name="area" value="<?php echo $area ?>" />

	<?php include( 'manage-filter-nav.php' ) ?>
	<?php include( 'manage-bulk-action.php' ) ?>

	<?php while( $entry = $list->next() ): ?>


		<?php
			$status = $entry->getStatus();
			$linkEdit = 'admin.php?area=edit&amp;cofilter=' . $entry->getId();
			$linkStatus = 'scripts/manage.php?cofilter=' . $entry->getId();
			$linkActive = $linkStatus . '&amp;status=' . ae_CommentfilterModel::STATUS_ACTIVE;
			$linkInactive = $linkStatus . '&amp;status=' . ae_CommentfilterModel::STATUS_INACTIVE;
			$linkDelete = $linkStatus . '&amp;status=delete';
		?>

	<div class="manage-entry cf-entry status-<?php echo $entry->getStatus() ?>">
		<input type="checkbox" name="entry[]" value="<?php echo $entry->getId() ?>" />

		<div class="entry-title">
			<span class="entry-name"><?php echo htmlspecialchars( $entry->getName() ) ?></span>:
			If <span class="entry-target"><?php echo htmlspecialchars( $targets[$entry->getMatchTarget()] ) ?></span>
			matches <span class="entry-match"><code><?php echo htmlspecialchars( $entry->getMatchRule() ) ?></code></span>
			then <span class="entry-action"><?php echo htmlspecialchars( $actions[$entry->getAction()] ) ?></span>
		</div>

		<div class="entry-actions">
			<a title="edit" class="entry-edit icon-add-before icon-before-pen" href="<?php echo $linkEdit ?>"></a>
		<?php if( $status != ae_CommentfilterModel::STATUS_ACTIVE ): ?>
			<a title="set active" class="entry-approve icon-add-before icon-before-check" href="<?php echo $linkActive ?>"></a>
		<?php endif ?>
		<?php if( $status != ae_CommentfilterModel::STATUS_INACTIVE ): ?>
			<a title="set inactive" class="entry-spam icon-add-before icon-before-ban" href="<?php echo $linkInactive ?>"></a>
		<?php endif ?>
			<a title="delete" class="entry-delete icon-add-before icon-before-trash" href="<?php echo $linkDelete ?>"></a>
		</div>
	</div>

	<?php endwhile ?>

	<?php
		$numPages = ceil( $list->getTotalNumItems() / $itemsPerPage );
		$queryStr = preg_replace( '/[?&]offset=?[0-9]*/i', '', $_SERVER['QUERY_STRING'] );
		$linkBase = 'admin.php?' . htmlspecialchars( $queryStr ) . '&amp;offset=';
	?>

</form>

<nav class="manage-page-navigation">
	<?php echo ae_SiteBuilder::pagination( $numPages, $pageOffset, $linkBase ) ?>
</nav>


<?php endif ?>
