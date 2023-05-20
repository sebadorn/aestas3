<?php

require_once( 'core/autoload.php' );
require_once( 'core/config.php' );

$filterPages = array(
	'LIMIT' => '0, 9999', // +1 root URL
	'ORDER BY' => 'pa_datetime DESC',
	'WHERE' => 'pa_status = "' . ae_PageModel::STATUS_PUBLISHED . '"'
);
$pageList = new ae_PageList( $filterPages );

$filterPosts = array(
	'LIMIT' => '0, 40000',
	'ORDER BY' => 'po_datetime DESC',
	'WHERE' => 'po_status = "' . ae_PostModel::STATUS_PUBLISHED . '"'
);
$postList = new ae_PostList( $filterPosts );

function buildURLItem( $loc, $lastmod ) {
	$item = '<url>';
	$item .= '<loc>' . $loc . '</loc>';

	if( is_string( $lastmod ) ) {
		$item .= '<lastmod>' . $lastmod . '</lastmod>';
	}

	$item .= '</url>';

	return $item;
}

const LASTMOD_FORMAT = 'Y-m-d';

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
echo buildURLItem( URL, null );

while( $page = $pageList->next() ) {
	echo buildURLItem( $page->getLink(), $page->getEditDatetime( LASTMOD_FORMAT ) );
}

while( $post = $postList->next() ) {
	echo buildURLItem( $post->getLink(), $post->getEditDatetime( LASTMOD_FORMAT ) );
}

?>
</urlset>