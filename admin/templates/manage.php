<?php

$manageArea = 'Comments';

if( isset( $_GET['category'] ) ) {
	$manageArea = 'Categories';
}
else if( isset( $_GET['page'] ) ) {
	$manageArea = 'Pages';
}
else if( isset( $_GET['post'] ) ) {
	$manageArea = 'Posts';
}
else if( isset( $_GET['user'] ) ) {
	$manageArea = 'Users';
}

?>
<h1>Manage: <?php echo $manageArea ?></h1>

<?php if( $manageArea == 'Categories' ): ?>


	<?php $caList = new ae_CategoryList() ?>

	<?php while( $entry = $caList->next() ): ?>
		<div class="manage-entry category-entry">
			<input type="checkbox" name="categories[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo $entry->getTitle() ?></span>

			<div class="entry-actions">
				<a class="entry-edit" href="admin.php?area=edit&amp;category=<?php echo $entry->getId() ?>">edit</a>
				<a class="entry-delete" href="scripts/manage.php?category&amp;delete=<?php echo $entry->getId() ?>">delete</a>
			</div>
		</div>
	<?php endwhile ?>


<?php elseif( $manageArea == 'Comments' ): ?>


	<?php $coList = new ae_CommentList() ?>

	<?php while( $entry = $coList->next() ): ?>
		<div class="manage-entry comment-entry">
			<input type="checkbox" name="comments[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo $entry->getAuthor() ?></span>

			<div class="entry-actions">
				<a class="entry-edit" href="admin.php?area=edit&amp;comment=<?php echo $entry->getId() ?>">edit</a>
				<a class="entry-spam" href="scripts/manage.php?comment&amp;spam=<?php echo $entry->getId() ?>">spam</a>
				<a class="entry-delete" href="scripts/manage.php?comment&amp;trash=<?php echo $entry->getId() ?>">trash</a>
			</div>
		</div>
	<?php endwhile ?>


<?php elseif( $manageArea == 'Pages' ): ?>


	<?php $paList = new ae_PageList() ?>

	<?php while( $entry = $paList->next() ): ?>
		<div class="manage-entry page-entry">
			<input type="checkbox" name="pages[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo $entry->getTitle() ?></span>

			<div class="entry-actions">
				<a class="entry-edit" href="admin.php?area=edit&amp;page=<?php echo $entry->getId() ?>">edit</a>
				<a class="entry-delete" href="scripts/manage.php?page&amp;delete=<?php echo $entry->getId() ?>">delete</a>
			</div>
		</div>
	<?php endwhile ?>


<?php elseif( $manageArea == 'Posts' ): ?>


	<?php $poList = new ae_PostList() ?>

	<?php while( $entry = $poList->next() ): ?>
		<div class="manage-entry post-entry">
			<input type="checkbox" name="posts[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo $entry->getTitle() ?></span>

			<div class="entry-actions">
				<a class="entry-edit" href="admin.php?area=edit&amp;post=<?php echo $entry->getId() ?>">edit</a>
				<a class="entry-delete" href="scripts/manage.php?post&amp;delete=<?php echo $entry->getId() ?>">delete</a>
			</div>
		</div>
	<?php endwhile ?>


<?php elseif( $manageArea == 'Users' ): ?>


	<?php $uList = new ae_UserList() ?>

	<?php while( $entry = $uList->next() ): ?>
		<div class="manage-entry user-entry">
			<input type="checkbox" name="users[]" value="<?php echo $entry->getId() ?>" />
			<span class="entry-title"><?php echo $entry->getNameInternal() ?></span>

			<div class="entry-actions">
				<a class="entry-edit" href="admin.php?area=edit&amp;user=<?php echo $entry->getId() ?>">edit</a>
				<a class="entry-delete" href="scripts/manage.php?user&amp;suspend=<?php echo $entry->getId() ?>">suspend</a>
				<a class="entry-delete" href="scripts/manage.php?user&amp;delete=<?php echo $entry->getId() ?>">delete</a>
			</div>
		</div>
	<?php endwhile ?>


<?php endif ?>