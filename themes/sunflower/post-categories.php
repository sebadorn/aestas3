<?php

$caLinks = array();

foreach( $data as $ca ) {
	$link = sprintf( '<a class="category" href="%s">%s</a>', $ca->getLink(), $ca->getTitle() );
	$caLinks[$ca->getTitle()] = $link;
}

ksort( $caLinks );

?>
<div class="post-categories icon-add-before icon-before-inbox">
	<?php
		foreach( $caLinks as $link ) {
			echo $link;
		}
	?>
</div>