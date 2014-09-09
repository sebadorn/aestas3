<?php

class ae_Timer {


	static protected $endTimes = array();
	static protected $startTimes = array();


	/**
	 * Format the time.
	 * @param  {float}  $time Time in seconds.
	 * @return {string}       Formatted time.
	 */
	static public function format( $time ) {
		return sprintf( '%.4f', $time );
	}


	/**
	 * Get the elapsed time since start.
	 * @param  {string}       $id     Timer ID.
	 * @param  {boolean}      $format Set to TRUE if output shall be formatted. (Optional. Default: TRUE.)
	 * @return {string|float}         The elapsed time in seconds. String if formatted, float otherwise.
	 */
	static public function getElapsed( $id, $format = TRUE ) {
		if( !isset( self::$startTimes[$id] ) ) {
			$msg = '[' . get_class() . '] Calling getElapsed(), but the timer "' . $id . '" has no start value.';
			throw new Exception( $msg );
		}

		$current = isset( self::$endTimes[$id] ) ? self::$endTimes[$id] : microtime( TRUE );
		$diff = $current - self::$startTimes[$id];

		return ( $format ? self::format( $diff ) : $diff );
	}


	/**
	 * Start a timer.
	 * @param {string} $id Timer ID.
	 */
	static public function start( $id ) {
		self::$startTimes[$id] = microtime( TRUE );
	}


	/**
	 * Stop a timer.
	 * @param  {string}       $id     Timer ID.
	 * @param  {boolean}      $format Set to TRUE if output shall be formatted. (Optional. Default: TRUE.)
	 * @return {string|float}         The elapsed time in seconds. String if formatted, float otherwise.
	 */
	static public function stop( $id, $format = TRUE ) {
		self::$endTimes[$id] = microtime( TRUE );

		return self::getElapsed( $id, $format );
	}


}