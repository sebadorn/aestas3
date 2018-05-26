'use strict';


var CommentPreview = {


	commentForm: null,
	defaultName: '',
	previewContent: null,
	previewName: null,


	/**
	 * Add the event listeners for the comment form.
	 */
	addListeners: function() {
		var ele;

		ele = this.commentForm.querySelector( '[name=comment-author-name]' );
		ele.addEventListener( 'keyup', this.update.bind( this ) );

		ele = this.commentForm.querySelector( '[name=comment-author-url]' );
		ele.addEventListener( 'change', this.update.bind( this ) );

		ele = this.commentForm.querySelector( '[name=comment-content]' );
		ele.addEventListener( 'keyup', this.update.bind( this ) );
	},


	/**
	 * Create the preview HTML and add it to the site.
	 */
	createPreview: function() {
		var avatarHead = document.createElement( 'span' );
		avatarHead.className = 'avatar-head';

		var avatarBody = document.createElement( 'span' );
		avatarBody.className = 'avatar-body';

		var previewAvatar = document.createElement( 'div' );
		previewAvatar.className = 'avatar';
		previewAvatar.appendChild( avatarHead );
		previewAvatar.appendChild( document.createElement( 'br' ) );
		previewAvatar.appendChild( avatarBody );

		this.previewName = document.createElement( 'span' );
		this.previewName.className = 'comment-author';
		this.previewName.textContent = this.defaultName;

		var time = document.createElement( 'span' );
		time.className = 'comment-time';
		time.textContent = 'preview';

		var meta = document.createElement( 'div' );
		meta.className = 'comment-meta';
		meta.appendChild( previewAvatar );
		meta.appendChild( this.previewName );
		meta.appendChild( time );

		this.previewContent = document.createElement( 'div' );
		this.previewContent.className = 'comment-content';

		var container = document.createElement( 'div' );
		container.className = 'comment preview';
		container.appendChild( meta );
		container.appendChild( this.previewContent );

		var preview = document.createDocumentFragment();
		preview.appendChild( container );
		preview.appendChild( document.createElement( 'hr' ) );

		var section = document.getElementById( 'comments' );
		section.appendChild( preview );
	},


	/**
	 * Initialize.
	 * @param {string} defaultName Default name for nameless comments.
	 */
	init: function( defaultName ) {
		this.defaultName = defaultName;
		this.commentForm = document.getElementById( 'comment-form' );

		if( this.commentForm == null ) {
			return;
		}

		this.addListeners();
	},


	/**
	 * Callback for the listeners.
	 * @param {Event} ev
	 */
	update: function( ev ) {
		if( this.previewContent == null ) {
			this.createPreview();
		}

		switch( ev.target.name ) {

			case 'comment-author-name':
				this.updateName( ev );
				break;

			case 'comment-author-url':
				this.updateUrl( ev );
				break;

			case 'comment-content':
				this.updateContent( ev );
				break;

		}
	},


	/**
	 * Update the preview because of a content change.
	 * @param {KeyupEvent} ev
	 */
	updateContent: function( ev ) {
		var content = ev.target.value;

		content = content.replace( /&/g, '&amp;' );
		content = content.replace( /</g, '&lt;' );
		content = content.replace( />/g, '&gt;' );

		content = content.replace( /&lt;(blockquote|code|del|em|strong)&gt;/gi, '<$1>' );
		content = content.replace( /&lt;\/(a|blockquote|code|del|em|strong)&gt;/gi, '</$1>' );
		content = content.replace( /&lt;a href="([^\'"]+)"&gt;/gi, '<a href="$1">' );
		content = content.replace( /&lt;(br) \/&gt;/gi, '<$1 />' );

		content = content.replace( /\r\n|\n\r|\r|\n/g, '<br />' );

		this.previewContent.innerHTML = content;
	},


	/**
	 * Update the preview because of a name change.
	 * @param {KeyupEvent} ev
	 */
	updateName: function( ev ) {
		var name = ev.target.value.trim();

		if( name.length == 0 ) {
			name = this.defaultName;
		}

		this.previewName.textContent = name;
	},


	/**
	 * Update the preview because of an URL change.
	 * @param {ChangeEvent} ev
	 */
	updateUrl: function( ev ) {
		var url = ev.target.value.toLowerCase();
		var newName;

		if( this.previewName.textContent.length == 0 ) {
			this.previewName.textContent = this.defaultName;
		}

		if( url.length <= 0 || url.match( /^((http|ftp)s?:\/\/)?[^\s"']+$/ ) == null ) {
			if( this.previewName.tagName == 'span' ) {
				return;
			}

			newName = document.createElement( 'span' );
		}
		else {
			if( url.match( /^(http|ftp)s?:\/\// ) == null ) {
				url = 'http://' + url;
			}

			newName = document.createElement( 'a' );
			newName.href = url;
		}

		newName.className = 'comment-author';
		newName.textContent = this.previewName.textContent;

		this.previewName.parentNode.replaceChild( newName, this.previewName );
		this.previewName = newName;
	}


};
