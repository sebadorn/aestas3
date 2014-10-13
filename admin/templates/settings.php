<?php

$themes = ae_Settings::getListOfThemes( '../themes/' );

?>

<h1>Settings</h1>


<form class="settings" method="post" action="scripts/settings.php">

	<div class="setting">
		<label for="set-blog-title">Blog title</label>
		<input type="text" name="blog-title" id="set-blog-title" placeholder="Blog title" value="<?php echo ae_Settings::get( 'blog_title' ) ?>" />
	</div>

	<div class="setting">
		<label for="set-blog-description">Blog description</label>
		<input type="text" name="blog-description" id="set-blog-description" placeholder="Blog description" value="<?php echo ae_Settings::get( 'blog_description' ) ?>" />
	</div>

	<div class="setting">
		<label for="set-theme">Blog theme</label>
		<select name="theme">
		<?php foreach( $themes as $t ): ?>
			<?php $t = htmlspecialchars( $t ) ?>
			<option value="<?php echo $t ?>"><?php echo $t ?></option>
		<?php endforeach ?>
		</select>
	</div>

	<button type="submit" class="btn btn-publish">save</button>

</form>
