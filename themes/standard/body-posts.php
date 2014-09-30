<?php
	$filter = array(
		'WHERE' => 'po_status = "published"',
		'LIMIT' => '0, 5'
	);
	$postList = new ae_PostList( $filter );
?>

<?php while( $post = $postList->next() ): ?>

<article class="post">
	<header>
		<h2><a href="<?php echo URL . $post->getLink() ?>"><?php echo $post->getTitle() ?></a></h2>
	</header>

	<div class="content"><?php echo $post->getContentSnippet() ?></div>

	<footer>
	</footer>
</article>

<?php endwhile ?>