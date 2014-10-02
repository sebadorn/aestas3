<?php

$tags = array();

foreach( $data as $tag ) {
	$link = sprintf(
		'<a class="tag icon-add-before icon-before-tag" href="%s">%s</a>',
		URL . 'tag/' . ae_Permalink::generatePermalink( $tag ), $tag
	);
	$tags[$tag] = $link;
}

ksort( $tags );

?>
<div class="post-tags">
	<?php
		foreach( $tags as $link ) {
			echo $link;
		}
	?>
</div>