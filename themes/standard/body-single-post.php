<?php

$post->loadCategories();

$filter = array(
	'WHERE' => 'co_post = ' . $post->getId() . ' AND co_status = "' . ae_CommentModel::STATUS_APPROVED . '"',
	'ORDER BY' => 'co_datetime ASC',
	'LIMIT' => FALSE
);
$coList = new ae_CommentList( $filter );

?>
<article class="post">
	<header class="post-header">
		<h2><a href="<?php echo URL . $post->getLink() ?>"><?php echo $post->getTitle() ?></a></h2>

		<time class="published icon-add-before icon-before-clock" datetime="<?php echo $post->getDatetime( 'Y-m-d' ) ?>">
			<span><?php echo $post->getDatetime( 'd.m.Y' ) ?></span>
		</time>

		<?php $sb->render( 'post-categories.php', $post->getCategories() ) ?>

		<div class="post-num-comments icon-add-before icon-before-comment">
			<a href="<?php echo URL . $post->getLink() ?>#comments"><?php echo $coList->getTotalNumItems() ?></a>
		</div>
	</header>

	<div class="content"><?php echo $post->getContent() ?></div>

	<footer class="post-footer">
		<?php $sb->render( 'post-tags.php', $post->getTags() ) ?>
	</footer>
</article>

<?php include( 'comments.php' ) ?>
