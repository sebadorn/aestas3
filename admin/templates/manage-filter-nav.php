<?php

$s = $list->countStatuses();

?>

<nav class="filter-status-nav">

	<a class="filter-status-none" href="<?php echo $urlBasis ?>"><em>default</em></a>,

<?php if( $area == 'category' ): ?>

	<?php
	$numAvailable = isset( $s[ae_CategoryModel::STATUS_AVAILABLE] ) ? $s[ae_CategoryModel::STATUS_AVAILABLE] : 0;
	$numTrash = isset( $s[ae_CategoryModel::STATUS_TRASH] ) ? $s[ae_CategoryModel::STATUS_TRASH] : 0;
	?>

	<a class="filter-status-available" href="<?php echo $urlBasis ?>&amp;status=available">available (<?php echo $numAvailable ?>)</a>,
	<a class="filter-status-trash" href="<?php echo $urlBasis ?>&amp;status=trash">trash (<?php echo $numTrash ?>)</a>

<?php elseif( $area == 'comment' ): ?>

	<?php
	$numApproved = isset( $s[ae_CommentModel::STATUS_APPROVED] ) ? $s[ae_CommentModel::STATUS_APPROVED] : 0;
	$numUnapproved = isset( $s[ae_CommentModel::STATUS_UNAPPROVED] ) ? $s[ae_CommentModel::STATUS_UNAPPROVED] : 0;
	$numSpam = isset( $s[ae_CommentModel::STATUS_SPAM] ) ? $s[ae_CommentModel::STATUS_SPAM] : 0;
	$numTrash = isset( $s[ae_CommentModel::STATUS_TRASH] ) ? $s[ae_CommentModel::STATUS_TRASH] : 0;
	?>

	<a class="filter-status-approved" href="<?php echo $urlBasis ?>&amp;status=approved">approved (<?php echo $numApproved ?>)</a>,
	<a class="filter-status-unapproved" href="<?php echo $urlBasis ?>&amp;status=unapproved">unapproved (<?php echo $numUnapproved ?>)</a>,
	<a class="filter-status-spam" href="<?php echo $urlBasis ?>&amp;status=spam">spam (<?php echo $numSpam ?>)</a>,
	<a class="filter-status-trash" href="<?php echo $urlBasis ?>&amp;status=trash">trash (<?php echo $numTrash ?>)</a>

<?php elseif( $area == 'media' ): ?>

	<?php
	$numAvailable = isset( $s[ae_MediaModel::STATUS_AVAILABLE] ) ? $s[ae_MediaModel::STATUS_AVAILABLE] : 0;
	$numTrash = isset( $s[ae_MediaModel::STATUS_TRASH] ) ? $s[ae_MediaModel::STATUS_TRASH] : 0;
	?>

	<a class="filter-status-available" href="<?php echo $urlBasis ?>&amp;status=available">available (<?php echo $numAvailable ?>)</a>,
	<a class="filter-status-trash" href="<?php echo $urlBasis ?>&amp;status=trash">trash (<?php echo $numTrash ?>)</a>

<?php elseif( $area == 'page' ): ?>

	<?php
	$numPublished = isset( $s[ae_PageModel::STATUS_PUBLISHED] ) ? $s[ae_PageModel::STATUS_PUBLISHED] : 0;
	$numDraft = isset( $s[ae_PageModel::STATUS_DRAFT] ) ? $s[ae_PageModel::STATUS_DRAFT] : 0;
	$numTrash = isset( $s[ae_PageModel::STATUS_TRASH] ) ? $s[ae_PageModel::STATUS_TRASH] : 0;
	?>

	<a class="filter-status-published" href="<?php echo $urlBasis ?>&amp;status=published">published (<?php echo $numPublished ?>)</a>,
	<a class="filter-status-draft" href="<?php echo $urlBasis ?>&amp;status=draft">draft (<?php echo $numDraft ?>)</a>,
	<a class="filter-status-trash" href="<?php echo $urlBasis ?>&amp;status=trash">trash (<?php echo $numTrash ?>)</a>

<?php elseif( $area == 'post' ): ?>

	<?php
	$numPublished = isset( $s[ae_PostModel::STATUS_PUBLISHED] ) ? $s[ae_PostModel::STATUS_PUBLISHED] : 0;
	$numDraft = isset( $s[ae_PostModel::STATUS_DRAFT] ) ? $s[ae_PostModel::STATUS_DRAFT] : 0;
	$numTrash = isset( $s[ae_PostModel::STATUS_TRASH] ) ? $s[ae_PostModel::STATUS_TRASH] : 0;
	?>

	<a class="filter-status-published" href="<?php echo $urlBasis ?>&amp;status=published">published (<?php echo $numPublished ?>)</a>,
	<a class="filter-status-draft" href="<?php echo $urlBasis ?>&amp;status=draft">draft (<?php echo $numDraft ?>)</a>,
	<a class="filter-status-trash" href="<?php echo $urlBasis ?>&amp;status=trash">trash (<?php echo $numTrash ?>)</a>

<?php elseif( $area == 'user' ): ?>

	<?php
	$numActive = isset( $s[ae_UserModel::STATUS_ACTIVE] ) ? $s[ae_UserModel::STATUS_ACTIVE] : 0;
	$numSuspended = isset( $s[ae_UserModel::STATUS_SUSPENDED] ) ? $s[ae_UserModel::STATUS_SUSPENDED] : 0;
	?>

	<a class="filter-status-active" href="<?php echo $urlBasis ?>&amp;status=active">active (<?php echo $numActive ?>)</a>,
	<a class="filter-status-suspended" href="<?php echo $urlBasis ?>&amp;status=suspended">suspended (<?php echo $numSuspended ?>)</a>

<?php endif ?>

</nav>
