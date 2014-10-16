<?php

$post->loadCategories();

$filter = array(
	'WHERE' => 'co_post = :postId AND co_status = :status',
	'ORDER BY' => 'co_datetime ASC',
	'LIMIT' => FALSE
);
$params = array(
	':postId' => $post->getId(),
	':status' => ae_CommentModel::STATUS_APPROVED
);

if( ae_Security::isLoggedIn() ) {
	$filter['WHERE'] = 'co_post = :postId';
	unset( $params[':status'] );
}

$coList = new ae_CommentList( $filter, $params );


$class = 'post single-post post-' . $post->getStatus();
$class .= ( $post->getDatetime( 'YmdHis' ) > date( 'YmdHis' ) ) ? ' post-future' : '';

?>
<article class="<?php echo $class ?>" id="post-<?php echo $post->getId() ?>">
	<header class="post-header">
		<h2><a href="<?php echo $post->getLink() ?>"><?php echo $post->getTitle() ?></a></h2>

		<time class="published icon-add-before icon-before-clock" datetime="<?php echo $post->getDatetime( 'Y-m-d' ) ?>">
			<span><?php echo $post->getDatetime( 'd.m.Y' ) ?></span>
		</time>

		<?php $sb->render( 'post-categories.php', $post->getCategories() ) ?>

		<div class="post-num-comments icon-add-before icon-before-comment">
			<a href="<?php echo $post->getLink() ?>#comments"><?php echo $coList->getTotalNumItems() ?></a>
		</div>
	</header>

	<div class="content"><?php echo $post->getContent() ?></div>

	<footer class="post-footer">
		<?php $sb->render( 'post-tags.php', $post->getTags() ) ?>
	</footer>
</article>

<?php include( 'comments.php' ) ?>
