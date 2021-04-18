(function() {
	'use strict';


	let gPlTarget = null;


	function initCreate() {
		gPlTarget = document.querySelector( 'input.permalink' );

		if( gPlTarget ) {
			let plSource = document.getElementById( 'convert-to-permalink' );

			if( plSource && plSource.value == '' ) {
				plSource.addEventListener( 'keyup', convertToPermalink );
			}

			gPlTarget.addEventListener( 'keyup', convertToPermalink );
		}

		const textareas = document.querySelectorAll( 'textarea' );

		textareas.forEach( ta => {
			ta.addEventListener( 'keydown', ev => allowTabs( ta, ev ) );
		} );
	}


	function allowTabs( ta, ev ) {
		if( ev.key !== 'Tab' ) {
			return;
		}

		const tab = '\t';

		if (ta.selectionStart || ta.selectionStart == '0') {
			const startPos = ta.selectionStart;
			const endPos = ta.selectionEnd;

			let content= ta.value.substring( 0, startPos );
			content += tab;
			content += ta.value.substring( endPos, ta.value.length );

			ta.value = content;

			ta.selectionStart = startPos + tab.length;
			ta.selectionEnd = startPos + tab.length;
		}
		else {
			ta.value += tab;
		}

		ev.preventDefault();
	}


	function convertToPermalink( ev ) {
		let pl = ev.target.value.toLowerCase();

		pl = pl.replace( /ä/g, 'ae' );
		pl = pl.replace( /ö/g, 'oe' );
		pl = pl.replace( /ü/g, 'ue' );
		pl = pl.replace( /ß/g, 'ss' );
		pl = pl.replace( /<[\/]?[a-z0-9]+>/, '' );
		pl = pl.replace( /[^a-z0-9-+]/g, '-' );
		pl = pl.replace( /[-]+/g, '-' );

		gPlTarget.value = pl;
	}


	window.addEventListener( 'load', initCreate );
})();
