<?php

$post->loadCategories();

$class = 'post single-post post-' . $post->getStatus();
$class .= ( $post->getDatetime( 'YmdHis' ) > date( 'YmdHis' ) ) ? ' post-future' : '';

?>
<article class="<?php echo $class ?>" id="post-<?php echo $post->getId() ?>">
	<header class="post-header">
		<h2><a href="<?php echo $post->getLink() ?>"><?php echo $post->getTitle() ?></a></h2>
		<div class="post-info">
			<time class="published" datetime="<?php echo $post->getDatetime( 'Y-m-d' ) ?>"><?php echo $post->getDatetime( 'd.m.Y' ) ?>			</time>
		</div>
	</header>

	<div class="content"><?php echo $post->getContent() ?></div>

	<footer class="post-footer">
		<?php $sb->render( 'post-tags.php', $post->getTags() ) ?>
		<?php echo $post->getVGWortTracker() ?>
	</footer>
</article>
