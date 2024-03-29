<?php

$tags = array();

foreach( $data as $tag ) {
	if( $tag == '' ) {
		continue;
	}

	$link = sprintf(
		'<a class="tag" href="%s"><span class="icon fa fa-tag"></span>%s</a>',
		URL . PERMALINK_BASE_TAG . ae_Permalink::prepareTag( $tag ), $tag
	);
	$tags[$tag] = $link;
}

ksort( $tags, SORT_FLAG_CASE | SORT_NATURAL );

?>
<div class="post-tags">
	<?php
		foreach( $tags as $link ) {
			echo $link;
		}
	?>
</div>