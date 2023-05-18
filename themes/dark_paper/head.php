<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php if( isset( $page ) && $page !== FALSE && $page->getId() == 677 ): ?>
	<meta name="robots" content="noindex" />
<?php endif ?>
	<title><?php echo $title ?></title>
<?php if( isset( $post ) && $post !== FALSE && $post->hasDescription() ): ?>
	<meta name="description" content="<?php echo addslashes( $post->getDescription() ) ?>" />
<?php elseif( isset( $page ) && $page !== FALSE && $page->hasDescription() ): ?>
	<meta name="description" content="<?php echo addslashes( $page->getDescription() ) ?>" />
<?php endif ?>
	<link rel="icon" href="<?php echo THEME_PATH ?>img/favicon-192.png" type="image/png" sizes="192x192" />
	<link rel="icon" href="<?php echo THEME_PATH ?>img/favicon-32.png" type="image/png" sizes="32x32" />
	<link rel="stylesheet" href="<?php echo THEME_PATH ?>css/style.css?v=8" />
	<link rel="stylesheet" href="<?php echo THEME_PATH ?>js/highlight/styles/stackoverflow-dark.min.css" />
	<link rel="alternate" type="application/rss+xml" title="New posts (RSS)" href="<?php echo URL ?>feed/" />
<?php

$p = NULL;

if( isset( $post ) && $post !== FALSE ) {
	$p = $post;
}
else if( isset( $page ) && $page !== FALSE ) {
	$p = $page;
}

if( $p !== NULL ) {
	$p->loadSocial();
	$p->renderSocial( 'twitter' );
}

?>
	<script src="<?php echo THEME_PATH ?>js/highlight/highlight.min.js"></script>
	<script src="<?php echo THEME_PATH ?>js/init.js"></script>
	<script src="<?php echo THEME_PATH ?>js/matomo.js"></script>
</head>
