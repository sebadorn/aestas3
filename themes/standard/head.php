<?php

$filter = array(
	'LIMIT' => '0, 5',
	'ORDER BY' => 'co_datetime DESC',
	'WHERE' => '
		co_status = "' . ae_CommentModel::STATUS_APPROVED . '" AND (
			(
				SELECT po_status FROM `' . ae_PostModel::TABLE . '`
				WHERE po_id = co_post
			) = "' . ae_PostModel::STATUS_PUBLISHED . '"
		)
	'
);
$coList = new ae_CommentList( $filter, FALSE );

$filter = array(
	'LIMIT' => FALSE,
	'WHERE' => '( '
);
while( $co = $coList->next() ) {
	$filter['WHERE'] .= 'po_id = ' . $co->getPostId() . ' OR ';
}
$filter['WHERE'] = mb_substr( $filter['WHERE'], 0, -4 ) . ' )';
$poList = new ae_PostList( $filter );

$coList->reset();
$coList->reverse();

?>
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
	<h1><a href="<?php echo URL ?>" title="Home">sebadorn</a></h1>

	<ul class="icons">
		<li class="icon-twitter">
			<a href="http://twitter.com/sebadorn" title="Twitter"></a>
		</li>
		<li class="icon-github">
			<a href="https://github.com/sebadorn" title="GitHub"></a>
		</li>
		<li class="icon-rss">
			<a href="http://feeds2.feedburner.com/sebadorn" title="RSS: Artikel"></a>
		</li>
	</ul>
</header>

<aside class="recent-comments">
	<h6>Kommentare</h6>
<?php while( $co = $coList->next() ): ?>
	<?php
		$gravUrl = GRAVATAR_BASE . md5( $co->getAuthorEmail() );
		$gravUrl .= '?d=';
		$gravUrl .= '&amp;s=' . GRAVATAR_SIZE;

		$p = $poList->find( $co->getPostId() );
		$postLink = URL . $p->getLink() . '#comment-' . $co->getId();
	?>
	<div>
		<a href="<?php echo $postLink ?>">
			<img alt="avatar" class="avatar avatar-<?php echo GRAVATAR_SIZE ?>" src="<?php echo $gravUrl ?>" />
		</a>
		<div class="comment-meta">
		<?php if( $co->getAuthorUrl() != '' ): ?>
			<a class="author" href="<?php echo $co->getAuthorUrl() ?>"><?php echo $co->getAuthorName() ?></a>
		<?php else: ?>
			<span class="author"><?php echo $co->getAuthorName() ?></span>
		<?php endif ?>
			<a class="article" href="<?php echo $postLink ?>" title="<?php echo $postLink ?>"><?php echo $p->getTitle() ?></a>
			<time datetime="<?php echo $co->getDatetime( 'Y-m-d\TH:i:s' )?>"><?php echo $co->getDatetime( 'd.m.y \u\m H:i' ) ?> Uhr</time>
		</div>
	</div>
<?php endwhile ?>
</aside>