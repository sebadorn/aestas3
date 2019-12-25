'use strict';


window.addEventListener( 'load', function() {
	var script = document.getElementById( 'script-init' );
	var dataIsSinglePost = script.getAttribute( 'data-is-single-post' );
	var dataCommentDefaultName = script.getAttribute( 'data-default-name' );

	if( dataIsSinglePost === '1' ) {
		CommentPreview.init( dataCommentDefaultName );
		CommentValidate.init();
	}

	var codeBlocks = document.querySelectorAll( 'pre' );

	for( var i = 0; i < codeBlocks.length; i++ ) {
		var block = codeBlocks[i];
		var cls = block.className;

		if( cls.indexOf( 'hljs' ) < 0 ) {
			block.className = cls.replace( 'brush:', '' ).trim();
			hljs.highlightBlock( block );
		}
	}
} );