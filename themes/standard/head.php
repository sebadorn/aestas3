<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
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
	<script>
		window.addEventListener( 'load', function() {
			var codeBlocks = document.querySelectorAll( 'pre' );

			for( var i = 0; i < codeBlocks.length; i++ ) {
				var block = codeBlocks[i];
				var cls = block.className;

				if( cls.indexOf( 'hljs' ) < 0 ) {
					block.className = cls.replace( 'brush:', '' ).trim();
					hljs.highlightBlock( block );
				}
			}

<?php if( IS_SINGLE_POST ): ?>
			CommentPreview.init( '<?php echo COMMENT_DEFAULT_NAME ?>' );
			CommentValidate.init();
<?php endif ?>
		} );
	</script>
</head>
