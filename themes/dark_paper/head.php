<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php if( isset( $page ) && $page !== FALSE && $page->getId() == 677 ): ?>
	<meta name="robots" content="noindex" />
<?php endif ?>
	<title><?php echo $title ?></title>
<?php if( isset( $post ) && $post !== FALSE ): ?>
<?php if( $post->hasDescription() ): ?>
	<meta name="description" content="<?php echo addslashes( $post->getDescription() ) ?>" />
<?php endif ?>
<?php if( isset( $_GET['p'] ) ): ?>
	<link rel="canonical" href="<?php echo $post->getLink() ?>" />
<?php endif ?>
<?php elseif( isset( $page ) && $page !== FALSE ): ?>
<?php if( $page->hasDescription() ): ?>
	<meta name="description" content="<?php echo addslashes( $page->getDescription() ) ?>" />
<?php endif ?>
<?php if( isset( $_GET['page'] ) ): ?>
	<link rel="canonical" href="<?php echo $page->getLink() ?>" />
<?php endif ?>
<?php endif ?>
	<link rel="icon" href="<?php echo THEME_PATH ?>img/favicon-192.png" type="image/png" sizes="192x192" />
	<link rel="icon" href="<?php echo THEME_PATH ?>img/favicon-32.png" type="image/png" sizes="32x32" />
	<link rel="stylesheet" href="<?php echo THEME_PATH ?>css/style.css?v=8" />
	<link rel="stylesheet" href="<?php echo THEME_PATH ?>js/highlight/styles/stackoverflow-dark.min.css" />
	<link rel="alternate" type="application/rss+xml" title="Posts on sebadorn.de (RSS)" href="<?php echo URL ?>feed/" />
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
