<?php

$class = 'post single-post post-' . $page->getStatus();
$class .= ( $page->getDatetime( 'YmdHis' ) > date( 'YmdHis' ) ) ? ' post-future' : '';

?>
<article class="<?php echo $class ?>" id="page-<?php echo $page->getId() ?>">
	<header class="post-header">
		<h2><?php echo $page->getTitle() ?></h2>
	</header>

	<div class="content"><?php echo $page->getContent() ?></div>
</article>
