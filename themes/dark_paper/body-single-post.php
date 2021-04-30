<?php

$post->loadCategories();

$class = 'post single-post post-' . $post->getStatus();
$class .= ( $post->getDatetime( 'YmdHis' ) > date( 'YmdHis' ) ) ? ' post-future' : '';

?>
<article class="<?php echo $class ?>" id="post-<?php echo $post->getId() ?>">
	<header class="post-header">
		<h2><a href="<?php echo $post->getLink() ?>"><?php echo $post->getTitle() ?></a></h2>

		<time class="published" datetime="<?php echo $post->getDatetime( 'Y-m-d' ) ?>">
			<span class="icon far fa-clock"></span>
			<span><?php echo $post->getDatetime( 'd.m.Y' ) ?></span>
		</time>

		<?php $sb->render( 'post-categories.php', $post->getCategories() ) ?>
	</header>

	<div class="content"><?php echo $post->getContent() ?></div>

	<footer class="post-footer">
		<?php $sb->render( 'post-tags.php', $post->getTags() ) ?>
	</footer>
</article>
