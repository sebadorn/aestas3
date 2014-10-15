<section class="comments" id="comments">

<?php if( $coList->getTotalNumItems() > 0 ): ?>
	<h4>Kommentare</h4>

	<hr />
<?php endif ?>

<?php while( $co = $coList->next() ): ?>
	<?php
		$gravUrl = GRAVATAR_BASE . md5( $co->getAuthorEmail() );
		$gravUrl .= '?d=mm';
		$gravUrl .= '&amp;s=' . GRAVATAR_SIZE;

		$authorName = ( $co->getAuthorName() == '' ) ? COMMENT_AUTHOR_DEFAULT_NAME : $co->getAuthorName();

		$postLink = URL . $post->getLink() . '#comment-' . $co->getId();
	?>
	<div class="comment" id="comment-<?php echo $co->getId() ?>">
		<div class="comment-meta">
			<img alt="avatar" class="avatar avatar-<?php echo GRAVATAR_SIZE ?>" src="<?php echo $gravUrl ?>" />
		<?php if( $co->getAuthorUrl() != '' ): ?>
			<a class="comment-author" href="<?php echo $co->getAuthorUrl() ?>"><?php echo $authorName ?></a>
		<?php else: ?>
			<span class="comment-author"><?php echo $authorName ?></span>
		<?php endif ?>
			<a href="#comment-<?php echo $co->getId() ?>">
				<time class="comment-time" datetime="<?php echo $co->getDatetime( 'Y-m-d' ) ?>"><?php echo $co->getDatetime( 'd.m.y \u\m H:i' ) ?></time>
			</a>
		</div>
		<div class="comment-content"><?php echo $co->getContent() ?></div>
	</div>

	<hr />
<?php endwhile ?>

</section>

<section class="comments comment-form">
	<h4>Schreib’ was</h4>

	<?php
		$placeholders = array(
			'author-name' => 'Name (optional)',
			'author-email' => 'E-Mail (optional)',
			'author-url' => 'Website (optional)',
			'content' => 'Kommentar'
		);
		echo ae_SiteBuilder::commentForm( $post->getId(), 'absenden', $placeholders )
	?>

	<div class="comment-code">
		<span>Verwendbares HTML</span>
		<code>&lt;a href=""&gt;</code>, <code>&lt;blockquote&gt;</code>, <code>&lt;code&gt;</code>, <code>&lt;del&gt;</code>, <code>&lt;em&gt;</code>, <code>&lt;strong&gt;</code>
	</div>
</section>