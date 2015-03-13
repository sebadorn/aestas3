<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php echo $title ?></title>
	<link rel="icon" href="<?php echo THEME_PATH ?>img/favicon-192.png" type="image/png" sizes="192x192" />
	<link rel="icon" href="<?php echo THEME_PATH ?>img/favicon-32.png" type="image/png" sizes="32x32" />
	<link rel="stylesheet" href="<?php echo THEME_PATH ?>css/style.css" />
	<link rel="alternate" type="application/rss+xml" title="Neue Einträge (RSS)" href="http://feeds2.feedburner.com/sebadorn" />
	<link rel="alternate" type="application/rss+xml" title="Neue Kommentare (RSS)" href="<?php echo URL ?>feed/comments.php" />
	<script src="<?php echo THEME_PATH ?>js/shl3/scripts/shCore.js"></script>
	<script src="<?php echo THEME_PATH ?>js/shl3/scripts/shAutoloader.js"></script>
<?php if( IS_SINGLE_POST ): ?>
	<script async src="<?php echo THEME_PATH ?>js/md5.min.js"></script>
	<script async src="<?php echo THEME_PATH ?>js/combined.js"></script>
<?php endif ?>
	<script>
		window.addEventListener( "load", function() {
			var shl3path = "<?php echo THEME_PATH ?>js/shl3/scripts/";

			SyntaxHighlighter.autoloader(
				"bash " + shl3path + "shBrushBash.js",
				"css " + shl3path + "shBrushCss.js",
				"cpp " + shl3path + "shBrushCpp.js",
				"java " + shl3path + "shBrushJava.js",
				"js javascript " + shl3path + "shBrushJScript.js",
				"php " + shl3path + "shBrushPhp.js",
				"plain " + shl3path + "shBrushPlain.js",
				"python " + shl3path + "shBrushPython.js",
				"sql " + shl3path + "shBrushSql.js",
				"xml " + shl3path + "shBrushXml.js"
			);
			SyntaxHighlighter.all();
<?php if( IS_SINGLE_POST ): ?>
			CommentPreview.init(
				"<?php echo GRAVATAR_BASE ?>",
				<?php echo GRAVATAR_SIZE ?>,
				"<?php echo COMMENT_DEFAULT_NAME ?>"
			);
			CommentValidate.init();
<?php endif ?>
		} );
	</script>
</head>
