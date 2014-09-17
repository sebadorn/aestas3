<?php

if( isset( $_GET['category'] ) && ae_Validate::id( $_GET['category'] ) ) {
	$editArea = 'Category';
	$model = new ae_CategoryModel();
	$model->load( $_GET['category'] );
}
else if( isset( $_GET['comment'] ) && ae_Validate::id( $_GET['comment'] ) ) {
	$editArea = 'Comment';
	$model = new ae_CommentModel();
	$model->load( $_GET['comment'] );
}
else if( isset( $_GET['page'] ) && ae_Validate::id( $_GET['page'] ) ) {
	$editArea = 'Page';
	$model = new ae_PageModel();
	$model->load( $_GET['page'] );
}
else if( isset( $_GET['post'] ) && ae_Validate::id( $_GET['post'] ) ) {
	$editArea = 'Post';
	$model = new ae_PostModel();
	$model->load( $_GET['post'] );
}
else if( isset( $_GET['user'] ) && ae_Validate::id( $_GET['user'] ) ) {
	$editArea = 'User';
	$model = new ae_UserModel();
	$model->load( $_GET['user'] );
}

?>
<h1>Edit: <?php echo $editArea ?></h1>

<form method="post" action="scripts/create.php" class="form-create">
	<input type="hidden" name="area" value="<?php echo strtolower( $editArea ) ?>" />
	<input type="hidden" name="edit-id" value="<?php echo $model->getId() ?>" />

<?php if( $editArea == 'Comment' ): ?>

	<aside>
		<div class="submit-buttons">
			<button type="submit" class="btn btn-publish" name="submit" value="publish">save</button>
			<span class="clear"></span>
		</div>
	</aside>

	<div class="main-content">
		<div class="input-group">

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
			<input type="text" name="category-title" placeholder="Category title" value="<?php echo htmlspecialchars( $model->getTitle() ) ?>" />
			<input type="hidden" name="category-permalink" placeholder="Permalink" value="<?php echo $model->getPermalink() ?>" />
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
			<input type="text" name="page-title" placeholder="Title" value="<?php echo htmlspecialchars( $model->getTitle() ) ?>" />
			<input type="hidden" name="page-permalink" placeholder="Permalink" value="<?php echo $model->getPermalink() ?>" />
		</div>
		<div class="input-group">
			<textarea name="page-content" placeholder="Content"><?php echo htmlspecialchars( $model->getContent() ) ?></textarea>
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
					$model->getCategories()
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
			<input type="text" name="post-title" placeholder="Title" value="<?php echo htmlspecialchars( $model->getTitle() ) ?>" />
			<input type="hidden" name="post-permalink" placeholder="Permalink" value="<?php echo $model->getPermalink() ?>" />
		</div>
		<div class="input-group">
			<textarea name="post-content" placeholder="Content"><?php echo htmlspecialchars( $model->getContent() ) ?></textarea>
		</div>
		<div class="input-group">
			<input type="text" name="post-tags" placeholder="Tags" value="<?php echo htmlspecialchars( $model->getTagsString() ) ?>" />
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
			<input type="text" name="user-name-external" placeholder="External name, displayed under posts" value="<?php echo htmlspecialchars( $model->getNameExternal() ) ?>" />
			<input type="hidden" name="user-permalink" placeholder="Permalink" value="<?php echo $model->getPermalink() ?>" />
		</div>
		<div class="input-group">
			<input type="password" name="user-password" placeholder="Password" />
		</div>
	</div>

<?php endif ?>

</form>