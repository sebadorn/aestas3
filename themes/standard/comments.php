<section class="comments" id="comments">

	<h4>Comments</h4>
<?php if( $coList->getTotalNumItems() > 0 ): ?>
	<hr />
<?php endif ?>

<?php while( $co = $coList->next() ): ?>
	<?php
		$authorName = ( $co->getAuthorName() == '' ) ? COMMENT_DEFAULT_NAME : $co->getAuthorName();

		$postLink = URL . $post->getLink() . '#comment-' . $co->getId();

		$class = 'comment';
		$class .= ( $co->getUserId() > 0 ) ? ' comment-registered-user' : '';
	?>
	<div class="<?php echo $class ?>" id="comment-<?php echo $co->getId() ?>">
		<div class="comment-meta">
			<div class="avatar">
			<?php if( $co->getUserId() == 1 ): ?>
				<img class="avatar-image" src="<?php echo THEME_PATH ?>img/avatar_seba.jpg" />
			<?php else: ?>
				<span class="avatar-head"></span><br /><span class="avatar-body"></span>
			<?php endif ?>
			</div>
		<?php if( $co->getAuthorUrl() != '' ): ?>
			<a class="comment-author" href="<?php echo $co->getAuthorUrl() ?>"><?php echo $authorName ?></a>
		<?php else: ?>
			<span class="comment-author"><?php echo $authorName ?></span>
		<?php endif ?>
			<a href="#comment-<?php echo $co->getId() ?>">
				<time class="comment-time" datetime="<?php echo $co->getDatetime( 'Y-m-d' ) ?>"><?php echo $co->getDatetime( 'd.m.y \a\t H:i' ) ?></time>
			</a>
		</div>
		<div class="comment-content"><?php echo $co->getContent() ?></div>
	</div>

	<hr />
<?php endwhile ?>

</section>

<section class="comments comment-form">
	<?php
		$placeholders = array(
			'author-name' => 'Name (optional)',
			'author-email' => 'eMail (optional)',
			'author-url' => 'Website (optional)',
			'content' => 'Comment'
		);
		echo ae_SiteBuilder::commentForm( $post->getId(), 'Send', $placeholders )
	?>

	<div class="comment-code">
		<span>Allowed HTML</span>
		<code>&lt;a href=""&gt;</code>, <code>&lt;blockquote&gt;</code>, <code>&lt;code&gt;</code>, <code>&lt;del&gt;</code>, <code>&lt;em&gt;</code>, <code>&lt;strong&gt;</code>
	</div>
</section>