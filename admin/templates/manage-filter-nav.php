<nav class="filter-status-nav">

<?php if( $area == 'category' ): ?>

	<a class="filter-status-none" href="<?php echo $urlBasis ?>"><em>default</em></a>,
	<a class="filter-status-available" href="<?php echo $urlBasis ?>&amp;status=available">available</a>,
	<a class="filter-status-trash" href="<?php echo $urlBasis ?>&amp;status=trash">trash</a>

<?php elseif( $area == 'comment' ): ?>

	<a class="filter-status-none" href="<?php echo $urlBasis ?>"><em>default</em></a>,
	<a class="filter-status-approved" href="<?php echo $urlBasis ?>&amp;status=approved">approved</a>,
	<a class="filter-status-unapproved" href="<?php echo $urlBasis ?>&amp;status=unapproved">unapproved</a>,
	<a class="filter-status-spam" href="<?php echo $urlBasis ?>&amp;status=spam">spam</a>,
	<a class="filter-status-trash" href="<?php echo $urlBasis ?>&amp;status=trash">trash</a>

<?php elseif( $area == 'page' ): ?>

	<a class="filter-status-none" href="<?php echo $urlBasis ?>"><em>default</em></a>,
	<a class="filter-status-published" href="<?php echo $urlBasis ?>&amp;status=published">published</a>,
	<a class="filter-status-draft" href="<?php echo $urlBasis ?>&amp;status=draft">draft</a>,
	<a class="filter-status-trash" href="<?php echo $urlBasis ?>&amp;status=trash">trash</a>

<?php elseif( $area == 'post' ): ?>

	<a class="filter-status-none" href="<?php echo $urlBasis ?>"><em>default</em></a>,
	<a class="filter-status-published" href="<?php echo $urlBasis ?>&amp;status=published">published</a>,
	<a class="filter-status-draft" href="<?php echo $urlBasis ?>&amp;status=draft">draft</a>,
	<a class="filter-status-trash" href="<?php echo $urlBasis ?>&amp;status=trash">trash</a>

<?php elseif( $area == 'user' ): ?>

	<a class="filter-status-none" href="<?php echo $urlBasis ?>"><em>default</em></a>,
	<a class="filter-status-active" href="<?php echo $urlBasis ?>&amp;status=active">active</a>,
	<a class="filter-status-suspended" href="<?php echo $urlBasis ?>&amp;status=suspended">suspended</a>

<?php endif ?>

</nav>
