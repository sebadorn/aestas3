'use strict';


window.addEventListener( 'load', function() {
	hljs.registerAliases( ['plain'], { languageName: 'plaintext' } );

	var codeBlocks = document.querySelectorAll( 'pre' );

	for( var i = 0; i < codeBlocks.length; i++ ) {
		var block = codeBlocks[i];
		var cls = block.className;

		if( cls.indexOf( 'hljs' ) < 0 ) {
			block.className = cls.replace( /brush:[ ]?/, 'language-' ).trim();
			hljs.highlightElement( block );
		}
	}
} );