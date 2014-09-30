<?php

class ae_Permalink {


	const REGEX_PAGE = '/^\/[^\/]+$/i';
	const REGEX_POST = '/^\/[0-9]{4}\/[0-9]{2}\/[0-9]{2}\/[^\/]+$/i';

	static protected $url = '';


	/**
	 * Load the page model to the permalink.
	 * @return {ae_PageModel} The loaded page model.
	 */
	static public function getPageModel() {
		if( !self::isPage() ) {
			$msg = sprintf( '[%s] Permalink does not represent a page.', get_class() );
			throw new Exception( $msg );
		}

		$permalink = mb_substr( self::$url, 1 );

		$model = new ae_PageModel();

		if( !$model->loadFromPermalink( $permalink ) ) {
			return FALSE;
		}

		return $model;
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

		$permalink = mb_substr( self::$url, 1 );

		$model = new ae_PostModel();

		if( !$model->loadFromPermalink( $permalink ) ) {
			return FALSE;
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
		return preg_match( self::REGEX_PAGE, self::$url );
	}


	/**
	 * Check if current URL represents a post permalink.
	 * @return {boolean} TRUE, if URL fits a post permalink, FALSE otherwise.
	 */
	static public function isPost() {
		return preg_match( self::REGEX_POST, self::$url );
	}


}
