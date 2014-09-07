<?php

class ae_Log {


	static protected $logged = array(
		'errors' => array(),
		'warnings' => array()
	);
	static protected $cfg = array(
		'enabled' => true
	);


	/**
	 * Check if errors have been logged.
	 * @return {int} Number of logged errors.
	 */
	static public function hasErrors() {
		return count( self::$logged['errors'] );
	}


	/**
	 * Check if anything has been logged.
	 * @return {int} Number of logged messages.
	 */
	static public function hasMessages() {
		return self::hasErrors() + self::hasWarnings();
	}


	/**
	 * Check if warnings have been logged.
	 * @return {int} Number of logged warnings.
	 */
	static public function hasWarnings() {
		return count( self::$logged['warnings'] );
	}


	/**
	 * Initialize the logger.
	 * @param {array} $settings Settings.
	 */
	static public function init( $settings ) {
		foreach( self::$cfg as $key => $value ) {
			if( isset( $settings[$key] ) ) {
				self::$cfg[$key] = $settings[$key];
			}
		}
	}


	/**
	 * Log an error.
	 * @param {string} $msg Error message to log.
	 */
	static public function error( $msg ) {
		if( self::$cfg['enabled'] ) {
			self::$logged['errors'][] = $msg;
		}
	}


	/**
	 * Print all logged messages as HTML.
	 * @param  {boolean} $echo If true (default) the HTML will be directly printed.
	 *                         Otherwise it will be returned as string.
	 * @return {string}        The HTML, but only if $echo == FALSE.
	 */
	static public function printAll( $echo = TRUE ) {
		$out = '';
		$out .= self::printErrors( FALSE );
		$out .= self::printWarnings( FALSE );

		if( !$echo ) {
			return $out;
		}

		echo $out;
	}


	/**
	 * Print all logged errors as HTML.
	 * @param  {boolean} $echo If true (default) the HTML will be directly printed.
	 *                         Otherwise it will be returned as string.
	 * @return {string}        The HTML, but only if $echo == FALSE.
	 */
	static public function printErrors( $echo = TRUE ) {
		$out = '';

		if( self::hasErrors() ) {
			$out .= '<ol class="log log-errors">' . PHP_EOL;

			foreach( self::$logged['errors'] as $err ) {
				$out .= "\t" . '<li class="log-entry log-entry-errors">' . $err . '</li>' . PHP_EOL;
			}

			$out .= '</ol>' . PHP_EOL;
		}

		if( !$echo ) {
			return $out;
		}

		echo $out;
	}


	/**
	 * Print all logged warnings as HTML.
	 * @param  {boolean} $echo If true (default) the HTML will be directly printed.
	 *                         Otherwise it will be returned as string.
	 * @return {string}        The HTML, but only if $echo == FALSE.
	 */
	static public function printWarnings( $echo = TRUE ) {
		$out = '';

		if( self::hasWarnings() ) {
			$out .= '<ol class="log log-warnings">' . PHP_EOL;

			foreach( self::$logged['warnings'] as $err ) {
				$out .= "\t" . '<li class="log-entry log-entry-warnings">' . $err . '</li>' . PHP_EOL;
			}

			$out .= '</ol>' . PHP_EOL;
		}

		if( !$echo ) {
			return $out;
		}

		echo $out;
	}


	/**
	 * Log a warning.
	 * @param {string} $msg Warning message to log.
	 */
	static public function warning( $msg ) {
		if( self::$cfg['enabled'] ) {
			self::$logged['warnings'][] = $msg;
		}
	}


}