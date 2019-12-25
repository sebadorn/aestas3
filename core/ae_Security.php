<?php

class ae_Security {


	static protected $cfg = array(
		'allowed_tags' => array( 'a', 'blockquote', 'code', 'del', 'em', 'strong' ),
		'hash_iterations' => '04'
	);
	static protected $validAreas = array(
		'create', 'credits', 'dashboard', 'edit', 'manage', 'media', 'settings'
	);
	static protected $validSubAreas = array(
		'create' => array( 'category', 'cofilter', 'comment', 'media', 'page', 'post', 'user' ),
		'credits' => array(),
		'dashboard' => array(),
		'manage' => array( 'category', 'comment', 'media', 'page', 'post', 'user' ),
		'media' => array(),
		'settings' => array( 'general', 'cofilters' )
	);


	/**
	 * Initialize.
	 * @param {array} $settings The settings. (Optional.)
	 */
	static public function init( $settings = array() ) {
		foreach( self::$cfg as $key => $value ) {
			if( isset( $settings[$key] ) ) {
				self::$cfg[$key] = $settings[$key];
			}
		}

		if( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			ae_Log::warning( '[' . get_class() . '] <code>$_SERVER["HTTP_USER_AGENT"] has no value.</code>' );
		}
		if( empty( $_SERVER['REMOTE_ADDR'] ) ) {
			ae_Log::warning( '[' . get_class() . '] <code>$_SERVER["REMOTE_ADDR"] has no value.</code>' );
		}
	}


	/**
	 * Start the session if none is started yet.
	 */
	static public function initSession() {
		if( session_id() == '' ) {
			$sessParams = session_get_cookie_params();

			session_set_cookie_params(
				$sessParams['lifetime'],
				$sessParams['path'],
				$sessParams['domain'],
				$sessParams['secure'],
				TRUE // no access per JavaScript
			);
			session_name( 'aestas3' );
			session_start();

			$_SESSION['last_action'] = time();
		}
	}


	/**
	 * Get the user ID of the current user.
	 * @return {int|boolean} The user ID, if a user is logged in, FALSE otherwise.
	 */
	static public function getCurrentUserId() {
		return isset( $_SESSION['ae_user'] ) ? $_SESSION['ae_user'] : FALSE;
	}


	/**
	 * Create a kind of user specific value to set in
	 * the session for verifying purposes.
	 * @return {string} Value for the session.
	 */
	static public function getSessionVerify() {
		return hash( 'sha256', $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] );
	}


	/**
	 * Generate a hash.
	 * @param  {string} $input Input to hash.
	 * @return {string}        Generated hash.
	 */
	static public function hash( $input ) {
		$phpass = new PasswordHash( self::$cfg['hash_iterations'], FALSE );

		return $phpass->HashPassword( $input );
	}


	/**
	 * Check if the user is logged in.
	 * @return {boolean} True, if the user has a session, false otherwise.
	 */
	static public function isLoggedIn() {
		return (
			isset( $_SESSION['ae_user'], $_SESSION['ae_verify'] ) &&
			$_SESSION['ae_user'] >= 0 &&
			$_SESSION['ae_verify'] == self::getSessionVerify()
		);
	}


	/**
	 * Check if an area by the given name exists.
	 * @param  {string} $area Name of the area.
	 * @return {boolean}      TRUE, if it exists, FALSE otherwise.
	 */
	static public function isValidArea( $area ) {
		return in_array( $area, self::$validAreas, TRUE );
	}


	/**
	 * Check if a sub area by the given name exists.
	 * @param  {string}  $area    The area to check if it has a sub area by this name.
	 * @param  {string}  $subArea Name of the sub area.
	 * @return {boolean}          TRUE, if $area has the given $subArea, FALSE otherwise.
	 * @throws {Exception}        If $area does not exist.
	 */
	static public function isValidSubArea( $area, $subArea ) {
		if( !self::isValidArea( $area ) ) {
			throw new Exception( '[' . get_class() . '] Unknown area "' . htmlspecialchars( $area ) . '".' );
		}

		return in_array( $subArea, self::$validSubAreas[$area], TRUE );
	}


	/**
	 * Log an user in by setting his ID in the session.
	 * @param {int} $userID User ID.
	 */
	static public function login( $userID ) {
		self::initSession();

		$_SESSION['ae_user'] = $userID;

		// As opposed to the crypt() function for passwords,
		// we just use the faster hash() function here.
		$_SESSION['ae_verify'] = self::getSessionVerify();
	}


	/**
	 * Log the user out by destroying the session.
	 */
	static public function logout() {
		ae_Security::initSession();
		$_SESSION = array();
		session_destroy();
	}


	/**
	 * Sanitize HTML input. Only allow certain tags.
	 * @param  {string} $input Input to sanitize.
	 * @return {string}        Sanitized output.
	 */
	static public function sanitizeHTML( $input ) {
		$tags = implode( '|', self::$cfg['allowed_tags'] );

		$input = htmlspecialchars( $input, ENT_NOQUOTES );
		$input = preg_replace( '/&lt;(' . $tags . ')&gt;/i', '<$1>', $input );
		$input = preg_replace( '/&lt;\/(' . $tags . ')&gt;/i', '</$1>', $input );

		if( in_array( 'a', self::$cfg['allowed_tags'] ) ) {
			$input = preg_replace( '/&lt;a href="([^\'"]+)"&gt;/i', '<a href="$1">', $input );
		}

		return $input;
	}


	/**
	 * Verify an input against its supposed hash.
	 * @param  {string} $input Input to verify.
	 * @param  {string} $hash  Hash to verify the input against.
	 * @return {boolean}       TRUE, if input matches hash, FALSE otherwise.
	 */
	static public function verify( $input, $hash ) {
		$phpass = new PasswordHash( self::$cfg['hash_iterations'], FALSE );

		return $phpass->CheckPassword( $input, $hash );
	}


}