<head>
	<meta charset="utf-8" />
	<title>sebadorn</title>
	<link rel="stylesheet" href="<?php echo THEME_PATH ?>css/screen.css" />
	<link rel="stylesheet" href="<?php echo THEME_PATH ?>js/shl3/styles/shCore.css" />
	<link rel="stylesheet" href="<?php echo THEME_PATH ?>css/shThemeSeba.css" />
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
