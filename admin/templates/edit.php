<?php

if( isset( $_GET['category'] ) && ae_Validate::id( $_GET['category'] ) ) {
	$editArea = 'Category';
	$areaId = 'category';
	$model = new ae_CategoryModel();
	$model->load( $_GET['category'] );
}
else if( isset( $_GET['cofilter'] ) && ae_Validate::id( $_GET['cofilter'] ) ) {
	$editArea = 'Comment filter';
	$areaId = 'cofilter';
	$model = new ae_CommentfilterModel();
	$model->load( $_GET['cofilter'] );
}
else if( isset( $_GET['comment'] ) && ae_Validate::id( $_GET['comment'] ) ) {
	$editArea = 'Comment';
	$areaId = 'comment';
	$model = new ae_CommentModel();
	$model->load( $_GET['comment'] );
}
else if( isset( $_GET['media'] ) && ae_Validate::id( $_GET['media'] ) ) {
	$editArea = 'Media';
	$areaId = 'media';
	$model = new ae_MediaModel();
	$model->load( $_GET['media'] );
}
else if( isset( $_GET['page'] ) && ae_Validate::id( $_GET['page'] ) ) {
	$editArea = 'Page';
	$areaId = 'page';
	$model = new ae_PageModel();
	$model->load( $_GET['page'] );
}
else if( isset( $_GET['post'] ) && ae_Validate::id( $_GET['post'] ) ) {
	$editArea = 'Post';
	$areaId = 'post';
	$model = new ae_PostModel();
	$model->load( $_GET['post'] );
	$model->loadCategoryIds();
}
else if( isset( $_GET['user'] ) && ae_Validate::id( $_GET['user'] ) ) {
	$editArea = 'User';
	$areaId = 'user';
	$model = new ae_UserModel();
	$model->load( $_GET['user'] );
}
else {
	header( 'Location: admin.php?error=unknown_edit_area' );
	exit;
}

?>
<h1>Edit: <?php echo $editArea ?></h1>

<form method="post" action="scripts/create.php" class="form-create">
	<input type="hidden" name="area" value="<?php echo $areaId ?>" />
	<input type="hidden" name="edit-id" value="<?php echo $model->getId() ?>" />

<?php if( $editArea == 'Comment' ): ?>
	<?php
		$content = str_replace( '<br />', '', $model->getContent() );
		$content = htmlspecialchars( $content );

		$userList = new ae_UserList();
	?>

	<aside>
		<div class="input-group">
			<h3>IP</h3>

			<input type="text" value="<?php echo htmlspecialchars( $model->getAuthorIp() ) ?>" placeholder="Author IP" readonly />
		</div>

		<div class="input-group">
			<h3>User</h3>

			<select name="comment-user">
				<option value="0">none</option>
			<?php while( $u = $userList->next() ): ?>
				<?php $select = ( $u->getId() == $model->getUserId() ) ? ' selected' : '' ?>
				<option value="<?php echo $u->getId() ?>"<?php echo $select ?>>
					<?php echo $u->getId() . ': ' . htmlspecialchars( $u->getNameExternal() ) ?>
				</option>
			<?php endwhile ?>
			</select>
		</div>

		<div class="submit-buttons">
			<button type="submit" class="btn btn-publish" name="submit" value="publish">save</button>
			<span class="clear"></span>
		</div>
	</aside>

	<div class="main-content">
		<div class="input-group">
			<input type="text" name="comment-author-name" value="<?php echo htmlspecialchars( $model->getAuthorName() ) ?>" placeholder="Author name" />
		</div>
		<div class="input-group">
			<input type="text" name="comment-author-email" value="<?php echo htmlspecialchars( $model->getAuthorEmail() ) ?>" placeholder="Author eMail" />
		</div>
		<div class="input-group">
			<input type="text" name="comment-author-url" value="<?php echo htmlspecialchars( $model->getAuthorUrl() ) ?>" placeholder="Author URL" />
		</div>

		<div class="input-group">
			<textarea name="comment-content" placeholder="Content"><?php echo $content ?></textarea>
		</div>
	</div>

<?php elseif( $editArea == 'Comment filter' ): ?>

	<?php
		$actions = array(
			ae_CommentfilterModel::ACTION_SPAM => 'Move to spam',
			ae_CommentfilterModel::ACTION_TRASH => 'Move to trash',
			ae_CommentfilterModel::ACTION_DROP => 'Drop comment',
			ae_CommentfilterModel::ACTION_APPROVE => 'Approve comment',
			ae_CommentfilterModel::ACTION_UNAPPROVE => 'Unapprove comment'
		);
		$targets = array(
			ae_CommentfilterModel::TARGET_IP => 'Author IP',
			ae_CommentfilterModel::TARGET_NAME => 'Author name',
			ae_CommentfilterModel::TARGET_EMAIL => 'Author eMail',
			ae_CommentfilterModel::TARGET_URL => 'Author URL',
			ae_CommentfilterModel::TARGET_USERID => 'Author user ID',
			ae_CommentfilterModel::TARGET_CONTENT => 'Comment content'
		);
		$statuses = ae_CommentfilterModel::listStatuses();
	?>

	<aside>
		<div class="input-group">
			<h3>Status</h3>

			<select name="cf-status">
			<?php foreach( $statuses as $s ): ?>
				<?php $sel = ( $s == $model->getStatus() ) ? ' selected' : '' ?>
				<option value="<?php echo htmlspecialchars( $s ) ?>"<?php echo $sel ?>><?php echo $s ?></option>
			<?php endforeach ?>
			</select>
		</div>

		<div class="submit-buttons">
			<button type="submit" class="btn btn-publish" name="submit" value="publish">save</button>
			<span class="clear"></span>
		</div>
	</aside>

	<div class="main-content">
		<div class="input-group">
			<input type="text" name="cf-name" placeholder="Filter name" value="<?php echo htmlspecialchars( $model->getName() ) ?>" />
		</div>

		<div class="input-group">
			If <select name="cf-target">
			<?php foreach( $targets as $key => $val ): ?>
				<?php
					$sel = ( $key == $model->getMatchTarget() ) ? ' selected' : '';
					$key = htmlspecialchars( $key );
				?>
				<option value="<?php echo $key ?>"<?php echo $sel ?>><?php echo $val ?></option>
			<?php endforeach ?>
			</select>
			matches <input type="text" name="cf-match" placeholder="Filter regex" value="<?php echo htmlspecialchars( $model->getMatchRule() )?>" />
			then <select name="cf-action">
			<?php foreach( $actions as $key => $val ): ?>
				<?php
					$sel = ( $key == $model->getAction() ) ? ' selected' : '';
					$key = htmlspecialchars( $key );
				?>
				<option value="<?php echo $key ?>"<?php echo $sel ?>><?php echo $val ?></option>
			<?php endforeach ?>
			</select>
		</div>
	</div>

<?php elseif( $editArea == 'Category' ): ?>

	<aside>
		<div class="input-group">
			<h3>Parent category</h3>

			<?php
				echo ae_Forms::categories(
					'category-parent',
					ae_Forms::INPUT_RADIO,
					array( $model->getParent() ),
					array_merge( array( $model->getId() ), $model->getChildren() )
				)
			?>
		</div>

		<div class="submit-buttons">
			<button type="submit" class="btn btn-publish" name="submit" value="publish">save</button>
			<span class="clear"></span>
		</div>
	</aside>

	<div class="main-content">
		<div class="input-group">
			<input type="text" name="category-title" id="convert-to-permalink" placeholder="Category title" value="<?php echo htmlspecialchars( $model->getTitle() ) ?>" />
			<input type="text" name="category-permalink" class="permalink" placeholder="Permalink" value="<?php echo $model->getPermalink() ?>" />
		</div>
	</div>

<?php elseif( $editArea == 'Media' ): ?>

	<?php
		$type = htmlspecialchars( $model->getType() );
		$icon = ae_Forms::getIcon( $type );
		$meta = $model->getMetaInfo();
		$fsize = ae_Forms::formatSize( $meta['file_size'] );
	?>

	<aside>
		<div class="input-group">
			<h3>Type</h3>
			<span class="icon-add-before <?php echo $icon ?>"><?php echo $type ?></span>
		</div>
		<div class="input-group">
			<h3>File size</h3>
			<span><?php echo $fsize ?></span>
		</div>
		<?php if( $model->isImage() && isset( $meta['image_width'] ) ): ?>
		<div class="input-group">
			<h3>Image size</h3>
			<span class="image-size"><?php echo $meta['image_width'] ?> × <?php echo $meta['image_height'] ?> pixels</span>
		</div>
		<?php endif; ?>

		<div class="submit-buttons">
			<button type="submit" class="btn btn-publish" name="submit" value="publish">save</button>
			<span class="clear"></span>
		</div>
	</aside>

	<div class="main-content">
		<div class="input-group">
			<input type="text" name="media-name" placeholder="Media name" value="<?php echo htmlspecialchars( $model->getName() ) ?>" />
		</div>
	</div>

<?php elseif( $editArea == 'Page' ): ?>

	<?php
		$datetime = explode( ' ', $model->getDatetime() );
		$date = explode( '-', $datetime[0] );
		$time = explode( ':', $datetime[1] );
	?>

	<aside>
		<div class="input-group">
			<h3>Schedule</h3>

			<div class="input-group-datetime">
				<span class="icon-add-before icon-before-clock" title="Datetime to publish this page"></span>
				<?php echo ae_Forms::monthSelect( 'page-publish-month', $date[1] ) ?>
				<input type="text" name="page-publish-day" value="<?php echo $date[2] ?>" />
				<span class="comma">,</span>
				<input type="text" name="page-publish-year" value="<?php echo $date[0] ?>" />
				<span class="at">at</span>
				<input type="text" name="page-publish-hour" value="<?php echo $time[0] ?>" />
				<span class="colon">:</span>
				<input type="text" name="page-publish-minute" value="<?php echo $time[1] ?>" />
			</div>

			<input id="page-schedule" type="checkbox" name="page-schedule" value="1" checked />
			<label for="page-schedule">Schedule page</label>
		</div>

		<div class="input-group">
			<h3>Comments</h3>

			<?php echo ae_Forms::postCommentsStatus( 'page-comments-status', $model->getCommentsStatus() ) ?>
		</div>

		<div class="submit-buttons">
			<button type="submit" class="btn btn-draft" name="submit" value="draft">save draft</button>
			<button type="submit" class="btn btn-publish" name="submit" value="publish">publish</button>
		</div>
	</aside>

	<div class="main-content">
		<div class="input-group">
			<input type="text" name="page-title" id="convert-to-permalink" placeholder="Title" value="<?php echo htmlspecialchars( $model->getTitle() ) ?>" />
			<input type="text" name="page-permalink" class="permalink" placeholder="Permalink" value="<?php echo $model->getPermalink() ?>" />
		</div>
		<div class="input-group">
			<textarea name="page-content" placeholder="Content"><?php echo htmlspecialchars( $model->getContent() ) ?></textarea>
		</div>
		<div class="input-group">
			<textarea name="page-desc" class="desc" placeholder="Description"><?php echo htmlspecialchars( $model->getDescription() ) ?></textarea>
		</div>
	</div>

<?php elseif( $editArea == 'Post' ): ?>

	<?php
		$datetime = explode( ' ', $model->getDatetime() );
		$date = explode( '-', $datetime[0] );
		$time = explode( ':', $datetime[1] );
	?>

	<aside>
		<div class="input-group">
			<h3>Categories</h3>

			<?php
				echo ae_Forms::categories(
					'post-categories',
					ae_Forms::INPUT_CHECKBOX,
					$model->getCategoryIds()
				)
			?>
		</div>

		<div class="input-group">
			<h3>Schedule</h3>

			<div class="input-group-datetime">
				<span class="icon-add-before icon-before-clock" title="Datetime to publish this post"></span>
				<?php echo ae_Forms::monthSelect( 'post-publish-month', $date[1] ) ?>
				<input type="text" name="post-publish-day" value="<?php echo $date[2] ?>" />
				<span class="comma">,</span>
				<input type="text" name="post-publish-year" value="<?php echo $date[0] ?>" />
				<span class="at">at</span>
				<input type="text" name="post-publish-hour" value="<?php echo $time[0] ?>" />
				<span class="colon">:</span>
				<input type="text" name="post-publish-minute" value="<?php echo $time[1] ?>" />
			</div>

			<input id="post-schedule" type="checkbox" name="post-schedule" value="1" checked />
			<label for="post-schedule">Schedule post</label>
		</div>

		<div class="input-group">
			<h3>Comments</h3>

			<?php echo ae_Forms::postCommentsStatus( 'post-comments-status', $model->getCommentsStatus() ) ?>
		</div>

		<div class="submit-buttons">
			<button type="submit" class="btn btn-draft" name="submit" value="draft">save draft</button>
			<button type="submit" class="btn btn-publish" name="submit" value="publish">publish</button>
		</div>
	</aside>

	<div class="main-content">
		<div class="input-group">
			<input type="text" name="post-title" id="convert-to-permalink" placeholder="Title" value="<?php echo htmlspecialchars( $model->getTitle() ) ?>" />
			<input type="text" name="post-permalink" class="permalink" placeholder="Permalink" value="<?php echo $model->getPermalink() ?>" />
		</div>
		<div class="input-group">
			<textarea name="post-content" placeholder="Content"><?php echo htmlspecialchars( $model->getContent() ) ?></textarea>
		</div>
		<div class="input-group">
			<textarea name="post-desc" class="desc" placeholder="Description"><?php echo htmlspecialchars( $model->getDescription() ) ?></textarea>
		</div>
		<div class="input-group">
			<input type="text" name="post-tags" placeholder="Tags" value="<?php echo htmlspecialchars( $model->getTagsString() ) ?>" />
		</div>
		<div class="input-group">
			<input type="text" name="post-vgwort" placeholder="VG WORT Tracker" value="<?php echo htmlspecialchars( $model->getVGWortTracker() ) ?>" />
		</div>
	</div>

<?php elseif( $editArea == 'User' ): ?>

	<?php $status = ( $model->getStatus() == ae_UserModel::STATUS_SUSPENDED ) ? ' checked' : '' ?>

	<aside>
		<div class="input-group">
			<input type="checkbox" id="user-status-suspended" name="user-status-suspended" value="disabled"<?php echo $status ?>/>
			<label for="user-status-suspended">Account suspended</label>
		</div>

		<div class="submit-buttons">
			<button type="submit" class="btn btn-publish" name="submit" value="publish">save</button>
			<span class="clear"></span>
		</div>
	</aside>

	<div class="main-content">
		<div class="input-group">
			<input type="text" name="user-name-internal" placeholder="Internal name, used for login" value="<?php echo htmlspecialchars( $model->getNameInternal() ) ?>" />
		</div>
		<div class="input-group">
			<input type="text" name="user-name-external" id="convert-to-permalink" placeholder="External name, displayed under posts" value="<?php echo htmlspecialchars( $model->getNameExternal() ) ?>" />
			<input type="text" name="user-permalink" class="permalink" placeholder="Permalink" value="<?php echo $model->getPermalink() ?>" />
		</div>
		<div class="input-group">
			<input type="password" name="user-password" placeholder="Password" />
		</div>
	</div>

<?php endif ?>

</form>
