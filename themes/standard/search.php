<?php

$search = new ae_Search( 25 );
$search->search( $_GET[PERMALINK_GET_SEARCH] );

?>

<?php if( $search->getNumItems() == 0 ): ?>

<article class="post single-post">
	<header class="post-header">
		<h2>Keine Treffer</h2>
	</header>

	<div class="content">
		<p>Die Suche nach <code><?php echo htmlspecialchars( $_GET[PERMALINK_GET_SEARCH] ) ?></code> ergab leider keine Treffer.</p>
	</div>
</article>

<?php endif ?>


<?php while( $post = $search->next() ): ?>

	<?php
	$class = 'search-result post post-' . $post->getStatus();
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
			<a href="<?php echo $post->getLink() ?>#comments"><?php echo $post->getNumComments() ?></a>
		</div>
	</header>

	<footer class="post-footer">
		<?php $sb->render( 'post-tags.php', $post->getTags() ) ?>
	</footer>
</article>

<?php endwhile ?>