<?php

$filter = array(
	'LIMIT' => '0, 5',
	'ORDER BY' => 'co_datetime DESC',
	'WHERE' => '
		co_status = "' . ae_CommentModel::STATUS_APPROVED . '" AND (
			(
				SELECT po_status FROM `' . ae_PostModel::TABLE . '`
				WHERE po_id = co_post
			) = "' . ae_PostModel::STATUS_PUBLISHED . '"
		)
	'
);
$coList = new ae_CommentList( $filter, FALSE );

$filter = array(
	'LIMIT' => FALSE,
	'WHERE' => '( '
);
while( $co = $coList->next() ) {
	$filter['WHERE'] .= 'po_id = ' . $co->getPostId() . ' OR ';
}
$filter['WHERE'] = mb_substr( $filter['WHERE'], 0, -4 ) . ' )';
$poList = new ae_PostList( $filter );

$coList->reset();
$coList->reverse();

?>
<aside class="recent-comments icon-add-before icon-before-comment">
	<h6>Neue Kommentare</h6>

<?php while( $co = $coList->next() ): ?>
	<?php
		$gravUrl = GRAVATAR_BASE . md5( $co->getAuthorEmail() );
		$gravUrl .= '?d=mm';
		$gravUrl .= '&amp;s=' . GRAVATAR_SIZE;

		$p = $poList->find( $co->getPostId() );
		$postLink = URL . $p->getLink() . '#comment-' . $co->getId();
	?>
	<div>
		<a href="<?php echo $postLink ?>">
			<img alt="avatar" class="avatar avatar-<?php echo GRAVATAR_SIZE ?>" src="<?php echo $gravUrl ?>" />
		</a>
		<div class="recent-comment-meta">
		<?php if( $co->getAuthorUrl() != '' ): ?>
			<a class="author" href="<?php echo $co->getAuthorUrl() ?>"><?php echo $co->getAuthorName() ?></a>
		<?php else: ?>
			<span class="author"><?php echo $co->getAuthorName() ?></span>
		<?php endif ?>
			<a class="article" href="<?php echo $postLink ?>" title="<?php echo $postLink ?>"><?php echo $p->getTitle() ?></a>
			<time datetime="<?php echo $co->getDatetime( 'Y-m-d\TH:i:s' )?>"><?php echo $co->getDatetime( 'd.m.y \u\m H:i' ) ?> Uhr</time>
		</div>
	</div>
<?php endwhile ?>

</aside>