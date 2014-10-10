"use strict";


var gPlTarget;


function initCreate() {
	gPlTarget = document.querySelector( "input.permalink" );

	if( gPlTarget ) {
		var plSource = document.getElementById( "convert-to-permalink" );

		if( plSource && plSource.value == "" ) {
			plSource.addEventListener( "keyup", convertToPermalink );
		}

		gPlTarget.addEventListener( "keyup", convertToPermalink );
	}
}


function convertToPermalink( ev ) {
	var pl = ev.target.value.toLowerCase();

	pl = pl.replace( /ä/g, "ae" );
	pl = pl.replace( /ö/g, "oe" );
	pl = pl.replace( /ü/g, "ue" );
	pl = pl.replace( /ß/g, "ss" );
	pl = pl.replace( /<[\/]?[a-z0-9]+>/, "" );
	pl = pl.replace( /[^a-z0-9-+]/g, "-" );
	pl = pl.replace( /[-]+/g, "-" );

	gPlTarget.value = pl;
}


window.addEventListener( "load", initCreate );
