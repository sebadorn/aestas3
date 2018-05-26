"use strict";


var CommentValidate = {


	commentForm: null,
	commentContent: null,
	commentEmail: null,


	highlightError: function( ele ) {
		ele.className += ' invalid';
	},


	init: function() {
		this.commentForm = document.getElementById( 'comment-form' );
		this.commentContent = this.commentForm.querySelector( '[name=comment-content]' );
		this.commentEmail = this.commentForm.querySelector( '[name=comment-author-email]' );

		this.commentForm.addEventListener( 'submit', this.validate.bind( this ) );
	},


	resetStatus: function() {
		this.commentContent.className = this.commentContent.className.replace( ' invalid', '' );
		this.commentEmail.className = this.commentEmail.className.replace( ' invalid', '' );
	},


	validate: function( ev ) {
		this.resetStatus();

		if( this.commentContent.value.length <= 0 ) {
			ev.preventDefault();
			this.highlightError( this.commentContent );
		}

		if(
			this.commentEmail.value.length > 0 &&
			this.commentEmail.value.match( /^[^\s@]+@[^\s@]+\.[^\s@]+$/ ) == null
		) {
			ev.preventDefault();
			this.highlightError( this.commentEmail );
		}
	}


};
