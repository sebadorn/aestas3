<?php

define( 'IS_RSS', TRUE );

require_once( '../core/autoload.php' );
require_once( '../core/config.php' );

$filterUsers = array(
	'LIMIT' => '0, 25'
);
$filterPosts = array(
	'LIMIT' => '0, 25',
	'ORDER BY' => 'po_datetime DESC',
	'WHERE' => 'po_status = "' . ae_PostModel::STATUS_PUBLISHED . '"'
);

$userList = new ae_UserList( $filterUsers );
$postList = new ae_PostList( $filterPosts );

$linkBase = RSS_PROTOCOL . ':' . URL;
$rssLink = $linkBase . 'feed/';
$image = FALSE;

if( file_exists( '../themes/' . THEME . '/img/favicon.png' ) ) {
	$image = $linkBase . 'themes/' . THEME . '/img/favicon.png';
}

echo '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;

?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
	<channel>
		<title><?php echo ae_Settings::get( 'blog_title' ) ?></title>
		<description><?php echo ae_Settings::get( 'blog_description' ) ?></description>
	<?php if( $image !== FALSE ): ?>
		<image>
			<url><?php echo $image ?></url>
			<title><?php echo ae_Settings::get( 'blog_title' ) ?></title>
			<link><?php echo $linkBase ?></link>
		</image>
	<?php endif ?>
		<pubDate><?php echo date( 'D, d M Y H:i:s O' ); ?></pubDate>
		<link><?php echo $rssLink; ?></link>
		<atom:link href="<?php echo $rssLink; ?>" rel="self" type="application/rss+xml" />
	<?php while( $post = $postList->next() ): ?>
		<?php $user = $userList->find( $post->getUserId() ) ?>
		<item>
			<title><?php echo htmlspecialchars( $post->getTitle() ) ?></title>
			<pubDate><?php echo $post->getDatetime( 'D, d M Y H:i:s O' ) ?></pubDate>
			<link><?php echo $linkBase . $post->getLink() ?></link>
			<guid><?php echo $linkBase . $post->getLink() ?></guid>
			<dc:creator><?php echo $user->getNameExternal() ?></dc:creator>
			<description><?php echo htmlspecialchars( $post->getContent() ) ?></description>
		</item>
	<?php endwhile ?>
	</channel>
</rss>