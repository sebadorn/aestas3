<?php

$filter = array(
	'LIMIT' => '0, 5',
	'ORDER BY' => 'co_datetime DESC',
	'WHERE' => '
		co_status = :coStatus AND (
			(
				SELECT po_status FROM `' . ae_PostModel::TABLE . '`
				WHERE po_id = co_post AND po_datetime <= :date
			) = :poStatus
		)
	'
);
$params = array(
	':coStatus' => ae_CommentModel::STATUS_APPROVED,
	':poStatus' => ae_PostModel::STATUS_PUBLISHED,
	':date' => date( 'Y-m-d H:i:s' )
);
$coList = new ae_CommentList( $filter, $params, FALSE );

$i = 0;
$filter = array(
	'LIMIT' => FALSE,
	'WHERE' => '( '
);
$params = array();

while( $co = $coList->next() ) {
	$filter['WHERE'] .= 'po_id = :id' . $i . ' OR ';
	$params[':id' . $i] = $co->getId();
	$i++;
}

$filter['WHERE'] = mb_substr( $filter['WHERE'], 0, -4 ) . ' )';

$poList = new ae_PostList( $filter, $params, FALSE );

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
		$postLink = $p->getLink() . '#comment-' . $co->getId();
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