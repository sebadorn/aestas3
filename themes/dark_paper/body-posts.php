<?php

$filter = array(
	'WHERE' => 'po_status = :status AND po_datetime <= :date',
	'LIMIT' => ( POSTS_OFFSET * POSTS_PER_PAGE ) . ', ' . POSTS_PER_PAGE
);
$params = array(
	':status' => ae_PostModel::STATUS_PUBLISHED,
	':date' => date( 'Y-m-d H:i:s' )
);

if( ae_Security::isLoggedIn() ) {
	$filter['WHERE'] = '1';
	unset( $params[':status'] );
	unset( $params[':date'] );
}

// Filter by category
if( ae_Permalink::isCategory() ) {
	$ca = ae_Permalink::getCategoryModel();

	if( $ca !== FALSE ) {
		$filter['WHERE'] .= '
			AND po_id IN (
				SELECT DISTINCT pc_post FROM `' . AE_TABLE_POSTS2CATEGORIES . '`
				WHERE pc_category = :catId
			)
		';
		$params[':catId'] = $ca->getId();
	}
}
// Filter by tag
else if( ae_Permalink::isTag() ) {
	$tag = ae_Permalink::getTagName();
	$tag = '%' . $tag . '%';

	$filter['WHERE'] .= ' AND po_tags LIKE :tag';
	$params[':tag'] = $tag;
}
// Filter by user
else if( ae_Permalink::isUser() ) {
	if( ( $userId = ae_Permalink::getUserId() ) !== FALSE ) {
		$filter['WHERE'] .= ' AND po_user = :user';
		$params[':user'] = $userId;
	}
}

$postList = new ae_PostList( $filter, $params );
$postList->loadCategories();

?>

<?php while( $post = $postList->next() ): ?>

	<?php
	$class = 'post post-' . $post->getStatus();
	$class .= ( $post->getDatetime( 'YmdHis' ) > date( 'YmdHis' ) ) ? ' post-future' : '';
	?>

<article class="<?php echo $class ?>" id="post-<?php echo $post->getId() ?>">
	<header class="post-header">
		<h2><a href="<?php echo $post->getLink() ?>"><?php echo $post->getTitle() ?></a></h2>

		<div class="post-info">
			<time class="published icon-add-before icon-before-clock" datetime="<?php echo $post->getDatetime( 'Y-m-d' ) ?>">
				<span><?php echo $post->getDatetime( 'd.m.Y' ) ?></span>
			</time>

			<?php $sb->render( 'post-categories.php', $post->getCategories() ) ?>
		</div>
	</header>

	<div class="content"><?php echo $post->getContentSnippet() ?></div>

<?php if( $post->hasSnippet() ): ?>
	<a class="read-more" href="<?php echo $post->getLink() ?>">Read more</a>
<?php endif ?>

	<footer class="post-footer">
		<?php $sb->render( 'post-tags.php', $post->getTags() ) ?>
	</footer>
</article>

<hr>

<?php endwhile ?>


<?php
	$numPages = ceil( $postList->getTotalNumItems() / POSTS_PER_PAGE );
	$linkBase = preg_replace( ';page/[0-9]+$;i', '', $_SERVER['REQUEST_URI'] );

	if( $linkBase[mb_strlen( $linkBase ) - 1] !== '/' ) {
		$linkBase .= '/';
	}

	$linkBase .= 'page/';
?>

<nav class="page-navigation">
	<?php echo ae_SiteBuilder::pagination( $numPages, POSTS_OFFSET + 1, $linkBase, 9, 1 ) ?>
</nav>
