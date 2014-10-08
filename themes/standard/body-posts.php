<?php

$filter = array(
	'WHERE' => 'po_status = :status AND po_datetime <= :date',
	'LIMIT' => ( POSTS_OFFSET * POSTS_PER_PAGE ) . ', ' . POSTS_PER_PAGE
);
$params = array(
	':status' => ae_PostModel::STATUS_PUBLISHED,
	':date' => date( 'Y-m-d H:i:s' )
);

if( ae_Permalink::isCategory() ) {
	$ca = ae_Permalink::getCategoryModel();

	$filter['WHERE'] .= '
		AND po_id IN (
			SELECT DISTINCT pc_post FROM `' . AE_TABLE_POSTS2CATEGORIES . '`
			WHERE pc_category = :catId
		)
	';
	$params[':catId'] = $ca->getId();
}

$postList = new ae_PostList( $filter, $params );
$postList->loadCategories();
$postList->loadNumComments();

?>

<?php while( $post = $postList->next() ): ?>

<article class="post" id="post-<?php echo $post->getId() ?>">
	<header class="post-header">
		<h2><a href="<?php echo $post->getLink() ?>"><?php echo $post->getTitle() ?></a></h2>

		<time class="published icon-add-before icon-before-clock" datetime="<?php echo $post->getDatetime( 'Y-m-d' ) ?>">
			<span><?php echo $post->getDatetime( 'd.m.Y' ) ?></span>
		</time>

		<?php $sb->render( 'post-categories.php', $post->getCategories() ) ?>

		<div class="post-num-comments icon-add-before icon-before-comment">
			<a href="<?php echo $post->getLink() ?>#comments"><?php echo $post->getNumComments() ?></a>
		</div>
	</header>

	<div class="content"><?php echo $post->getContentSnippet() ?></div>

<?php if( $post->hasSnippet() ): ?>
	<a class="read-more" href="<?php echo $post->getLink() ?>">weiterlesen</a>
<?php endif ?>

	<footer class="post-footer">
		<?php $sb->render( 'post-tags.php', $post->getTags() ) ?>
	</footer>
</article>

<?php endwhile ?>


<?php
	$numPages = ceil( $postList->getTotalNumItems() / POSTS_PER_PAGE );
	// $linkBase = URL . 'page/';
	$linkBase = preg_replace( ';page/[0-9]+$;i', '', $_SERVER['REQUEST_URI'] );
	$linkBase .= 'page/';
?>

<nav class="page-navigation">
	<?php echo ae_SiteBuilder::pagination( $numPages, POSTS_OFFSET + 1, $linkBase, 9, 1 ) ?>
</nav>
