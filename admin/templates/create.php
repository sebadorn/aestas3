<?php

$createArea = 'Post';

if( isset( $_GET['page'] ) ) {
	$createArea = 'Page';
}
else if( isset( $_GET['user'] ) ) {
	$createArea = 'User';
}

?>
<h1>Create: <?php echo $createArea ?></h1>

<form method="post" action="scripts/create.php" class="form-create">
	<input type="hidden" name="area" value="<?php echo strtolower( $createArea ) ?>" />

<?php if( $createArea == 'Page' ): ?>



<?php elseif( $createArea == 'Post' ): ?>

	<aside>
		<div class="input-group input-group-datetime">
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
		<div class="input-group">
			<input id="post-schedule" type="checkbox" name="post-schedule" value="1" />
			<label for="post-schedule">Schedule post</label>
		</div>

		<div class="submit-buttons">
			<button type="submit" class="btn btn-draft" name="submit" value="draft">save draft</button>
			<button type="submit" class="btn btn-publish" name="submit" value="publish">publish</button>
		</div>
	</aside>

	<div class="main-content">
		<div class="input-group">
			<input type="text" name="post-title" placeholder="Title" />
		</div>
		<div class="input-group">
			<textarea name="post-content" placeholder="Content"></textarea>
		</div>
		<div class="input-group">
			<input type="text" name="post-tags" placeholder="Tags" />
		</div>
	</div>

<?php elseif( $createArea == 'User' ): ?>



<?php endif ?>

</form>
