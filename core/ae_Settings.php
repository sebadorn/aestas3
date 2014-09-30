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
	 * Check if mod_rewrite is enabled on the server.
	 * @return {boolean} TRUE, if mod_rewrite is enabled, FALSE otherwise.
	 */
	static public function isModRewriteEnabled() {
		return in_array( 'mod_rewrite', apache_get_modules() );
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


}
