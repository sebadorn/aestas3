<nav class="header-nav">

<?php foreach( $data as $category => $subnav ): ?>

	<?php if( isset( $subnav['link'] ) ): ?>
		<?php
			$class = 'nav-category icon-add-before icon-before-' . $subnav['icon'];
			if( $subnav['active'] ) {
				$class .= ' active';
			}
		?>

		<a href="admin.php?area=<?php echo htmlentities( $subnav['link'] ) ?>" class="<?php echo $class ?>"><?php echo $category ?></a>

	<?php else: ?>
		<?php
			$class = 'sub-nav-header nav-category icon-add-before icon-before-' . $subnav['icon'];
			if( $subnav['active'] ) {
				$class .= ' active';
			}
		?>

		<div class="sub-nav-container">
			<span class="<?php echo $class ?>"><?php echo $category ?></span>
			<nav class="sub-nav">
				<div class="wrapper">

		<?php foreach( $subnav as $title => $subdata ): ?>
			<?php if( is_array( $subdata ) ): ?>
				<?php $class = $subdata['active'] ? ' class="active"' : ''; ?>

				<a href="admin.php?area=<?php echo htmlentities( $subdata['link'] ) ?>"<?php echo $class ?>><?php echo $title ?></a>

			<?php endif ?>
		<?php endforeach ?>

				</div>
			</nav>
		</div>

	<?php endif ?>

<?php endforeach ?>

	<a href="scripts/logout.php" class="nav-logout icon-add-before icon-before-logout" title="logout"></a>

</nav>
