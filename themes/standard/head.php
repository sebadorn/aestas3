<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php if( isset( $page ) && $page !== FALSE && $page->getId() == 677 ): ?>
	<meta name="robots" content="noindex" />
<?php endif ?>
	<title><?php echo $title ?></title>
	<link rel="icon" href="<?php echo THEME_PATH ?>img/favicon-192.png" type="image/png" sizes="192x192" />
	<link rel="icon" href="<?php echo THEME_PATH ?>img/favicon-32.png" type="image/png" sizes="32x32" />
	<link rel="stylesheet" href="<?php echo THEME_PATH ?>css/style.css" />
	<link rel="stylesheet" href="<?php echo THEME_PATH ?>js/highlight/styles/github.css" />
	<link rel="alternate" type="application/rss+xml" title="Neue Einträge (RSS)" href="<?php echo URL ?>feed/" />
	<link rel="alternate" type="application/rss+xml" title="Neue Kommentare (RSS)" href="<?php echo URL ?>feed/comments.php" />
	<script src="<?php echo THEME_PATH ?>js/highlight/highlight.pack.js"></script>
<?php if( IS_SINGLE_POST ): ?>
	<script src="<?php echo THEME_PATH ?>js/combined.js"></script>
<?php endif ?>
	<script src="<?php echo THEME_PATH ?>js/init.js" id="script-init" data-is-single-post="<?php echo ( IS_SINGLE_POST ? '1' : '0' ) ?>" data-default-name="<?php echo COMMENT_DEFAULT_NAME ?>"></script>
</head>
