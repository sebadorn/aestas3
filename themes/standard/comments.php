<section class="comments" id="comments">

	<h4>Kommentare</h4>

	<hr />

<?php while( $co = $coList->next() ): ?>
	<?php
		$gravUrl = GRAVATAR_BASE . md5( $co->getAuthorEmail() );
		$gravUrl .= '?d=mm';
		$gravUrl .= '&amp;s=' . GRAVATAR_SIZE;

		$postLink = URL . $post->getLink() . '#comment-' . $co->getId();
	?>
	<div class="comment" id="comment-<?php echo $co->getId() ?>">
		<div class="comment-meta">
			<img alt="avatar" class="avatar avatar-<?php echo GRAVATAR_SIZE ?>" src="<?php echo $gravUrl ?>" />
		<?php if( $co->getAuthorUrl() != '' ): ?>
			<a class="comment-author" href="<?php echo $co->getAuthorUrl() ?>"><?php echo $co->getAuthorName() ?></a>
		<?php else: ?>
			<span class="comment-author"><?php echo $co->getAuthorName() ?></span>
		<?php endif ?>
			<a href="#comment-<?php echo $co->getId() ?>">
				<time class="comment-time" datetime="<?php echo $co->getDatetime( 'Y-m-d' ) ?>"><?php echo $co->getDatetime( 'd.m.y \u\m H:i' ) ?></time>
			</a>
		</div>
		<div class="comment-content"><?php echo $co->getContent() ?></div>
	</div>

	<hr />
<?php endwhile ?>

	<form action="<?php echo URL ?>scripts/comment.php" method="post">
	</form>

</section>