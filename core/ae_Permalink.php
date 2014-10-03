<?php

class ae_Permalink {


	const GET_OFFSET = 'offset';
	const GET_PAGE = 'page';
	const GET_POST = 'p';
	const REGEX_PAGE = '/^\/[^\/]+$/i';
	const REGEX_POST = '/^\/[0-9]{4}\/[0-9]{2}\/[0-9]{2}\/[^\/]+$/i';

	static protected $url = '';


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
		$permalink = preg_replace( '/<[\/]?[a-z0-9]+>/i', '', $permalink );
		$permalink = preg_replace( '/[^a-zA-Z0-9-+]/', '-', $permalink );
		$permalink = preg_replace( '/[-]+/', '-', $permalink );

		return $permalink;
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

		if( isset( $_GET[self::GET_PAGE] ) && ae_Validate::id( $_GET[self::GET_PAGE] ) ) {
			if( !$model->load( $_GET[self::GET_PAGE] ) ) {
				return FALSE;
			}
		}
		else {
			$permalink = mb_substr( self::$url, 1 );

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

		if( preg_match( '/\/page\/[0-9]+\/?$/', self::$url ) ) {
			$offset = explode( '/', self::$url );
			$offset = array_reverse( $offset );
			$offset = ( $offset[0] == '' ) ? $offset[1] : $offset[0];
		}
		else if( isset( $_GET[self::GET_OFFSET] ) && ae_Validate::integer( $_GET[self::GET_OFFSET] ) ) {
			$offset = $_GET[self::GET_OFFSET];
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

		if( isset( $_GET[self::GET_POST] ) && ae_Validate::id( $_GET[self::GET_POST] ) ) {
			if( !$model->load( $_GET[self::GET_POST] ) ) {
				return FALSE;
			}
		}
		else {
			$permalink = mb_substr( self::$url, 1 );

			if( !$model->loadFromPermalink( $permalink ) ) {
				return FALSE;
			}
		}

		return $model;
	}


	/**
	 * Initialize by setting the revelant URL part.
	 */
	static public function init() {
		$urlBase = explode( '/', $_SERVER['PHP_SELF'] );
		array_pop( $urlBase );
		$urlBase = implode( '/', $urlBase );

		self::$url = str_replace( $urlBase, '', $_SERVER['REQUEST_URI'] );
	}


	/**
	 * Check if current URL represents a page permalink.
	 * @return {boolean} TRUE, if URL fits a page permalink, FALSE otherwise.
	 */
	static public function isPage() {
		$modRewrite = preg_match( self::REGEX_PAGE, self::$url );
		$get = ( isset( $_GET[self::GET_PAGE] ) && ae_Validate::id( $_GET[self::GET_PAGE] ) );

		return $modRewrite || $get;
	}


	/**
	 * Check if current URL represents a post permalink.
	 * @return {boolean} TRUE, if URL fits a post permalink, FALSE otherwise.
	 */
	static public function isPost() {
		$modRewrite = preg_match( self::REGEX_POST, self::$url );
		$get = ( isset( $_GET[self::GET_POST] ) && ae_Validate::id( $_GET[self::GET_POST] ) );

		return $modRewrite || $get;
	}


}
