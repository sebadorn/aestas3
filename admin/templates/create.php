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
		<button type="submit" class="btn" name="submit" value="draft">save draft</button>
		<button type="submit" class="btn" name="submit" value="publish">publish</button>
	</aside>

	<div class="main-content">
		<div class="input-group">
			<input type="text" name="post-title" placeholder="Title" />
		</div>
		<div class="input-group">
			<textarea name="post-content" placeholder="Content"></textarea>
		</div>
	</div>

<?php elseif( $createArea == 'User' ): ?>



<?php endif ?>

</form>
