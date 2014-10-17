<?php

class ae_Settings {


	const TABLE = AE_TABLE_SETTINGS;

	static protected $store = array();


	/**
	 * Get a settings.
	 * @param  {string}    $key Name of the settings.
	 * @return {string}         Value of the setting.
	 * @throws {Exception}      If a setting by this name does not exist.
	 */
	static public function get( $key ) {
		if( !isset( self::$store[$key] ) ) {
			$msg = sprintf( '[%s] Unknown setting: "%s"', get_class(), htmlspecialchars( $key ) );
			throw new Exception( $msg );
		}

		return self::$store[$key];
	}


	/**
	 * Get a list of the names of all theme directories.
	 * @param  {string} $themeDir Path to the theme directory.
	 * @return {array}            List of all theme names.
	 */
	static public function getListOfThemes( $themeDir = '../../themes/' ) {
		$themes = array();

		if( $handle = opendir( $themeDir ) ) {
			$ignore = array( '.', '..' );

			while( ( $file = readdir( $handle ) ) !== FALSE ) {
				if( !in_array( $file, $ignore ) ) {
					$themes[] = $file;
				}
			}

			closedir( $handle );
		}

		return $themes;
	}


	/**
	 * Check if mod_rewrite is enabled on the server.
	 * @return {boolean} TRUE, if mod_rewrite is enabled, FALSE otherwise.
	 */
	static public function isModRewriteEnabled() {
		$modRewrite = FALSE;

		if( OVERWRITE_MOD_REWRITE_ENABLED ) {
			$modRewrite = TRUE;
		}
		else if( function_exists( 'apache_get_modules' ) ) {
			$modRewrite = in_array( 'mod_rewrite', apache_get_modules() );
		}
		else if( getenv( 'HTTP_MOD_REWRITE' ) != '' ) {
			$modRewrite = ( getenv( 'HTTP_MOD_REWRITE' ) == 'On' ) ? TRUE : FALSE;
		}
		else {
			ob_start();
			phpinfo( INFO_MODULES );
			$contents = ob_get_contents();
			ob_end_clean();

			$modRewrite = ( strpos( $contents, 'mod_rewrite' ) !== FALSE );
		}

		return $modRewrite;
	}


	/**
	 * Load settings from DB.
	 */
	static public function load() {
		$stmt = '
			SELECT * FROM `' . self::TABLE . '`
		';
		$result = ae_Database::query( $stmt );

		if( $result === FALSE ) {
			$msg = sprintf( '[%s] Failed to load settings.', get_class() );
			throw new Exception( $msg );
		}

		foreach( $result as $row ) {
			self::$store[$row['s_key']] = $row['s_value'];
		}
	}


	/**
	 * Check if the PHP version is older than 5.3.
	 * @return {boolean} TRUE, if PHP version < 5.3, FALSE otherwise.
	 */
	static public function phpVersionBefore5_3() {
		$v = explode( '.', phpversion() );

		return ( $v[0] < 5 || ( $v[0] >= 5 && $v[1] <= 2 ) );
	}


}
