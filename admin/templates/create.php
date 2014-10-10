<?php

$createArea = 'Post';

if( isset( $_GET['category'] ) ) {
	$createArea = 'Category';
}
else if( isset( $_GET['page'] ) ) {
	$createArea = 'Page';
}
else if( isset( $_GET['user'] ) ) {
	$createArea = 'User';
}

?>
<h1>Create: <?php echo $createArea ?></h1>

<form method="post" action="scripts/create.php" class="form-create">
	<input type="hidden" name="area" value="<?php echo strtolower( $createArea ) ?>" />

<?php if( $createArea == 'Category' ): ?>

	<aside>
		<div class="input-group">
			<h3>Parent category</h3>

			<?php echo ae_Forms::categories( 'category-parent', ae_Forms::INPUT_RADIO ) ?>
		</div>

		<div class="submit-buttons">
			<button type="submit" class="btn btn-publish" name="submit" value="publish">save</button>
			<span class="clear"></span>
		</div>
	</aside>

	<div class="main-content">
		<div class="input-group">
			<input type="text" name="category-title" id="convert-to-permalink" placeholder="Category title" />
			<input type="text" name="category-permalink" class="permalink" placeholder="Permalink" />
		</div>
	</div>

<?php elseif( $createArea == 'Page' ): ?>

	<aside>
		<div class="input-group">
			<h3>Schedule</h3>

			<div class="input-group-datetime">
				<span class="icon-add-before icon-before-clock" title="Datetime to publish this page"></span>
				<?php echo ae_Forms::monthSelect( 'page-publish-month' ) ?>
				<input type="text" name="page-publish-day" value="<?php echo date( 'd' ) ?>" />
				<span class="comma">,</span>
				<input type="text" name="page-publish-year" value="<?php echo date( 'Y' ) ?>" />
				<span class="at">at</span>
				<input type="text" name="page-publish-hour" value="<?php echo date( 'H' ) ?>" />
				<span class="colon">:</span>
				<input type="text" name="page-publish-minute" value="<?php echo date( 'i' ) ?>" />
			</div>

			<input id="page-schedule" type="checkbox" name="page-schedule" value="1" />
			<label for="page-schedule">Schedule page</label>
		</div>

		<div class="input-group">
			<h3>Comments</h3>

			<?php echo ae_Forms::postCommentsStatus( 'page-comments-status', ae_PageModel::COMMENTS_OPEN ) ?>
		</div>

		<div class="submit-buttons">
			<button type="submit" class="btn btn-draft" name="submit" value="draft">save draft</button>
			<button type="submit" class="btn btn-publish" name="submit" value="publish">publish</button>
		</div>
	</aside>

	<div class="main-content">
		<div class="input-group">
			<input type="text" name="page-title" id="convert-to-permalink" placeholder="Title" />
			<input type="text" name="page-permalink" class="permalink" placeholder="Permalink" />
		</div>
		<div class="input-group">
			<textarea name="page-content" placeholder="Content"></textarea>
		</div>
	</div>

<?php elseif( $createArea == 'Post' ): ?>

	<aside>
		<div class="input-group">
			<h3>Categories</h3>

			<?php echo ae_Forms::categories( 'post-categories', ae_Forms::INPUT_CHECKBOX ) ?>
		</div>

		<div class="input-group">
			<h3>Schedule</h3>

			<div class="input-group-datetime">
				<span class="icon-add-before icon-before-clock" title="Datetime to publish this post"></span>
				<?php echo ae_Forms::monthSelect( 'post-publish-month' ) ?>
				<input type="text" name="post-publish-day" value="<?php echo date( 'd' ) ?>" />
				<span class="comma">,</span>
				<input type="text" name="post-publish-year" value="<?php echo date( 'Y' ) ?>" />
				<span class="at">at</span>
				<input type="text" name="post-publish-hour" value="<?php echo date( 'H' ) ?>" />
				<span class="colon">:</span>
				<input type="text" name="post-publish-minute" value="<?php echo date( 'i' ) ?>" />
			</div>

			<input id="post-schedule" type="checkbox" name="post-schedule" value="1" />
			<label for="post-schedule">Schedule post</label>
		</div>

		<div class="input-group">
			<h3>Comments</h3>

			<?php echo ae_Forms::postCommentsStatus( 'post-comments-status', ae_PageModel::COMMENTS_OPEN ) ?>
		</div>

		<div class="submit-buttons">
			<button type="submit" class="btn btn-draft" name="submit" value="draft">save draft</button>
			<button type="submit" class="btn btn-publish" name="submit" value="publish">publish</button>
		</div>
	</aside>

	<div class="main-content">
		<div class="input-group">
			<input type="text" name="post-title" id="convert-to-permalink" placeholder="Title" />
			<input type="text" name="post-permalink" class="permalink" placeholder="Permalink" />
		</div>
		<div class="input-group">
			<textarea name="post-content" placeholder="Content"></textarea>
		</div>
		<div class="input-group">
			<input type="text" name="post-tags" placeholder="Tags" />
		</div>
	</div>

<?php elseif( $createArea == 'User' ): ?>

	<aside>
		<div class="input-group">
			<input type="checkbox" id="user-status" name="user-status" value="disabled" />
			<label for="user-status-suspended">Suspend account</label>
		</div>

		<div class="submit-buttons">
			<button type="submit" class="btn btn-publish" name="submit" value="publish">save</button>
			<span class="clear"></span>
		</div>
	</aside>

	<div class="main-content">
		<div class="input-group">
			<input type="text" name="user-name-internal" placeholder="Internal name, used for login" />
		</div>
		<div class="input-group">
			<input type="text" name="user-name-external" id="convert-to-permalink" placeholder="External name, displayed under posts" />
			<input type="text" name="user-permalink" class="permalink" placeholder="Permalink" />
		</div>
		<div class="input-group">
			<input type="password" name="user-password" placeholder="Password" />
		</div>
	</div>

<?php endif ?>

</form>
