<?php

require_once( 'core/autoload.php' );
require_once( 'core/config.php' );

$filterPages = array(
	'WHERE' => 'pa_status = :status AND pa_datetime <= :date',
	'LIMIT' => '0, 9999', // +1 root URL
	'ORDER BY' => 'pa_datetime DESC'
);
$paramsPages = array(
	':status' => ae_PageModel::STATUS_PUBLISHED,
	':date' => date( 'Y-m-d H:i:s' )
);
$pageList = new ae_PageList( $filterPages, $paramsPages );


$filterPosts = array(
	'WHERE' => 'po_status = :status AND po_datetime <= :date',
	'LIMIT' => '0, 40000',
	'ORDER BY' => 'po_datetime DESC'
);
$paramsPosts = array(
	':status' => ae_PostModel::STATUS_PUBLISHED,
	':date' => date( 'Y-m-d H:i:s' )
);
$postList = new ae_PostList( $filterPosts, $paramsPosts );

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