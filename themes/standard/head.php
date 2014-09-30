<head>
	<meta charset="utf-8" />
	<title>sebadorn</title>
	<link rel="icon" href="<?php echo THEME_PATH ?>img/favicon-192.png" type="image/png" sizes="192x192" />
	<link rel="icon" href="<?php echo THEME_PATH ?>img/favicon-32.png" type="image/png" sizes="32x32" />
	<link rel="stylesheet" href="<?php echo THEME_PATH ?>css/screen.css" />
	<link rel="stylesheet" href="<?php echo THEME_PATH ?>js/shl3/styles/shCore.css" />
	<link rel="stylesheet" href="<?php echo THEME_PATH ?>css/shThemeSeba.css" />
	<link rel="alternate" type="application/rss+xml" title="Neue Einträge (RSS)" href="http://feeds2.feedburner.com/sebadorn" />
	<link rel="alternate" type="application/rss+xml" title="Neue Kommentare (RSS)" href="<?php echo URL ?>feed/comments.php" />
	<script src="<?php echo THEME_PATH ?>js/shl3/scripts/shCore.js"></script>
	<script src="<?php echo THEME_PATH ?>js/shl3/scripts/shAutoloader.js"></script>
	<script>
		window.addEventListener( "load", function() {
			var shl3path = "<?php echo THEME_PATH ?>js/shl3/scripts/";

			SyntaxHighlighter.autoloader(
				"bash " + shl3path + "shBrushBash.js",
				"css " + shl3path + "shBrushCss.js",
				"java " + shl3path + "shBrushJava.js",
				"js javascript " + shl3path + "shBrushJScript.js",
				"php " + shl3path + "shBrushPhp.js",
				"plain " + shl3path + "shBrushPlain.js",
				"python " + shl3path + "shBrushPython.js",
				"sql " + shl3path + "shBrushSql.js",
				"xml " + shl3path + "shBrushXml.js"
			);
			SyntaxHighlighter.all();
		} );
	</script>
</head>

<header class="main-header">
	<h1><a href="<?php echo URL ?>">sebadorn</a></h1>
</header>
