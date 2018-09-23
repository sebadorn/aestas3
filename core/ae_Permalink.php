<?php

class ae_Permalink {


	static protected $regex = array(
		'category' => ';^/%CATEGORY_BASE%[^/]+$;i',
		'offset' => ';/%OFFSET_BASE%[0-9]+/?$;i',
		'page' => ';^/%PAGE_BASE%[^/]+$;i',
		'post' => ';^/%POST_BASE%[0-9]{4}/[0-9]{2}/[0-9]{2}/[^/]+$;i',
		'tag' => ';^/%TAG_BASE%[^/]+$;i',
		'user' => ';^/%USER_BASE%[^/]+$;i',
	);

	static protected $url = '';
	static protected $urlNoOffset = '';


	/**
	 * Generate a permalink from the title.
	 * @param  {string} $title Title to convert into a permalink.
	 * @return {string}        The permalink.
	 */
	static public function generatePermalink( $title ) {
		$search = array( 'ä', 'ö', 'ü', 'ß' );
		$replace = array( 'ae', 'oe', 'ue', 'ss' );

		$permalink = strtolower( $title );
		$permalink = str_replace( $search, $replace, $permalink );
		$permalink = preg_replace( ';<[/]?[a-z0-9]+>;i', '', $permalink );
		$permalink = preg_replace( ';[^a-zA-Z0-9-+];', '-', $permalink );
		$permalink = preg_replace( ';[-]+;', '-', $permalink );

		return $permalink;
	}


	/**
	 * Load the category model to the permalink.
	 * @return {ae_CategoryModel} The loaded category model.
	 */
	static public function getCategoryModel() {
		if( !self::isCategory() ) {
			$msg = sprintf( '[%s] Permalink does not represent a category.', get_class() );
			throw new Exception( $msg );
		}

		$model = new ae_CategoryModel();

		if( isset( $_GET[PERMALINK_GET_CATEGORY] ) && ae_Validate::id( $_GET[PERMALINK_GET_CATEGORY] ) ) {
			if( !$model->load( $_GET[PERMALINK_GET_CATEGORY] ) ) {
				return FALSE;
			}
		}
		else {
			$permalink = mb_substr( self::$urlNoOffset, 1 );
			$permalink = preg_replace( ';^' . PERMALINK_BASE_CATEGORY . ';i', '', $permalink );

			if( !$model->loadFromPermalink( $permalink, TRUE ) ) {
				return FALSE;
			}
		}

		return $model;
	}


	/**
	 * Load the page model to the permalink.
	 * @return {ae_PageModel} The loaded page model.
	 */
	static public function getPageModel() {
		if( !self::isPage() ) {
			$msg = sprintf( '[%s] Permalink does not represent a page.', get_class() );
			throw new Exception( $msg );
		}

		$model = new ae_PageModel();

		if( isset( $_GET[PERMALINK_GET_PAGE] ) && ae_Validate::id( $_GET[PERMALINK_GET_PAGE] ) ) {
			if( !$model->load( $_GET[PERMALINK_GET_PAGE] ) ) {
				return FALSE;
			}
		}
		else {
			$permalink = mb_substr( self::$url, 1 );
			$permalink = preg_replace( ';^' . PERMALINK_BASE_PAGE . ';i', '', $permalink );

			if( !$model->loadFromPermalink( $permalink ) ) {
				return FALSE;
			}
		}

		return $model;
	}


	/**
	 * Get the current offset for posts.
	 * @return {int} Offset (page of posts to display).
	 */
	static public function getPostOffset() {
		$offset = 0;

		if( preg_match( self::$regex['offset'], self::$url ) ) {
			$offset = explode( '/', self::$url );
			$offset = array_reverse( $offset );
			$offset = ( $offset[0] == '' ) ? $offset[1] : $offset[0];
		}
		else if( isset( $_GET[PERMALINK_GET_OFFSET] ) ) {
			$offset = $_GET[PERMALINK_GET_OFFSET];
		}

		if( !ae_Validate::integer( $offset ) ) {
			$offset = 0;
		}

		return $offset;
	}


	/**
	 * Load the post model to the permalink.
	 * @return {ae_PostModel} The loaded post model.
	 */
	static public function getPostModel() {
		if( !self::isPost() ) {
			$msg = sprintf( '[%s] Permalink does not represent a post.', get_class() );
			throw new Exception( $msg );
		}

		$model = new ae_PostModel();

		if( isset( $_GET[PERMALINK_GET_POST] ) && ae_Validate::id( $_GET[PERMALINK_GET_POST] ) ) {
			if( !$model->load( $_GET[PERMALINK_GET_POST] ) ) {
				return FALSE;
			}
		}
		else {
			$permalink = mb_substr( self::$url, 1 );
			$permalink = preg_replace( ';^' . PERMALINK_BASE_POST . ';i', '', $permalink );

			if( !$model->loadFromPermalink( $permalink ) ) {
				return FALSE;
			}
		}

		return $model;
	}


	/**
	 * Get the tag name.
	 * @return {string} The given tag.
	 */
	static public function getTagName() {
		if( !self::isTag() ) {
			$msg = sprintf( '[%s] Permalink does not represent a tag.', get_class() );
			throw new Exception( $msg );
		}

		if( isset( $_GET[PERMALINK_GET_TAG] ) ) {
			$tag = $_GET[PERMALINK_GET_TAG];
		}
		else {
			$tag = mb_substr( self::$urlNoOffset, 1 );
			$tag = preg_replace( ';^' . PERMALINK_BASE_TAG . ';i', '', $tag );
		}

		return rawurldecode( $tag );
	}


	/**
	 * Get the user ID.
	 * @return {int|boolean} The user ID or FALSE on failure.
	 */
	static public function getUserId() {
		if( !self::isUser() ) {
			$msg = sprintf( '[%s] Permalink does not represent a user.', get_class() );
			throw new Exception( $msg );
		}

		$model = new ae_UserModel();

		if( isset( $_GET[PERMALINK_GET_USER] ) ) {
			$permalink = $_GET[PERMALINK_GET_USER];
		}
		else {
			$permalink = mb_substr( self::$url, 1 );
			$permalink = preg_replace( ';^' . PERMALINK_BASE_USER . ';i', '', $permalink );
		}

		if( !$model->loadFromPermalink( $permalink ) ) {
			return FALSE;
		}

		return $model->getId();
	}


	/**
	 * Initialize by setting the revelant URL part.
	 */
	static public function init() {
		$urlBase = explode( '/', $_SERVER['PHP_SELF'] );
		array_pop( $urlBase );
		$urlBase = implode( '/', $urlBase );

		self::$regex['category'] = str_replace( '%CATEGORY_BASE%', PERMALINK_BASE_CATEGORY, self::$regex['category'] );
		self::$regex['offset'] = str_replace( '%OFFSET_BASE%', PERMALINK_BASE_OFFSET, self::$regex['offset'] );
		self::$regex['page'] = str_replace( '%PAGE_BASE%', PERMALINK_BASE_PAGE, self::$regex['page'] );
		self::$regex['post'] = str_replace( '%POST_BASE%', PERMALINK_BASE_POST, self::$regex['post'] );
		self::$regex['tag'] = str_replace( '%TAG_BASE%', PERMALINK_BASE_TAG, self::$regex['tag'] );
		self::$regex['user'] = str_replace( '%USER_BASE%', PERMALINK_BASE_USER, self::$regex['user'] );

		self::$url = str_replace( '?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI'] );
		self::$url = str_replace( $urlBase, '', self::$url );
		self::$url = preg_replace( ';/index.php$;', '', self::$url );
		self::$url = preg_replace( ';/$;', '', self::$url );

		self::$urlNoOffset = preg_replace( self::$regex['offset'], '', self::$url );
	}


	/**
	 * Check if current URL represents a category permalink.
	 * @return {boolean} TRUE, if URL fits a category permalink, FALSE otherwise.
	 */
	static public function isCategory() {
		$modRewrite = preg_match( self::$regex['category'], self::$urlNoOffset );
		$get = ( isset( $_GET[PERMALINK_GET_CATEGORY] ) && ae_Validate::id( $_GET[PERMALINK_GET_CATEGORY] ) );

		return $modRewrite || $get;
	}


	/**
	 * Check if current URL represents a page permalink.
	 * @return {boolean} TRUE, if URL fits a page permalink, FALSE otherwise.
	 */
	static public function isPage() {
		$modRewrite = preg_match( self::$regex['page'], self::$url );
		$get = ( isset( $_GET[PERMALINK_GET_PAGE] ) && ae_Validate::id( $_GET[PERMALINK_GET_PAGE] ) );

		return $modRewrite || $get;
	}


	/**
	 * Check if current URL represents a post permalink.
	 * @return {boolean} TRUE, if URL fits a post permalink, FALSE otherwise.
	 */
	static public function isPost() {
		$modRewrite = preg_match( self::$regex['post'], self::$url );
		$get = ( isset( $_GET[PERMALINK_GET_POST] ) && ae_Validate::id( $_GET[PERMALINK_GET_POST] ) );

		return $modRewrite || $get;
	}


	/**
	 * Check if current URL represents a tag permalink.
	 * @return {boolean} TRUE, if URL fits a tag permalink, FALSE otherwise.
	 */
	static public function isTag() {
		$modRewrite = preg_match( self::$regex['tag'], self::$urlNoOffset );
		$get = isset( $_GET[PERMALINK_GET_TAG] );

		return $modRewrite || $get;
	}


	/**
	 * Check if current URL represents a user permalink.
	 * @return {boolean} TRUE, if URL fits a user permalink, FALSE otherwise.
	 */
	static public function isUser() {
		$modRewrite = preg_match( self::$regex['user'], self::$urlNoOffset );
		$get = isset( $_GET[PERMALINK_GET_USER] );

		return $modRewrite || $get;
	}


	/**
	 * Prepare a tag for use in a link.
	 * @param  {string} $tag Original tag.
	 * @return {string}      Prepared tag.
	 */
	static public function prepareTag( $tag ) {
		$tag = str_replace( '/', '_', $tag );
		$tag = rawurlencode( $tag );

		return $tag;
	}


}
