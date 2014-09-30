<?php

if( $content == 'single-post' ) {
	include( 'body-single-post.php' );
}
else if( $content == 'single-page' ) {
	include( 'body-page.php' );
}
else if( $content == '404' ) {
	include( '404.php' );
}
else {
	include( 'body-posts.php' );
}
