<?php

class ae_Timer {


	static protected $endTimes = array();
	static protected $startTimes = array();


	static public function format( $time ) {
		return sprintf( '%.4f', $time );
	}


	static public function getElapsed( $id, $format = TRUE ) {
		if( !isset( self::$startTimes[$id] ) ) {
			$msg = '[' . get_class() . '] Calling getElapsed(), but the timer "' . $id . '" has no start value.';
			throw new Exception( $msg );
		}

		$current = isset( self::$endTimes[$id] ) ? self::$endTimes[$id] : microtime( TRUE );
		$diff = $current - self::$startTimes[$id];

		return ( $format ? self::format( $diff ) : $diff );
	}


	static public function start( $id ) {
		self::$startTimes[$id] = microtime( TRUE );
	}


	static public function stop( $id, $format = TRUE ) {
		self::$endTimes[$id] = microtime( TRUE );

		return self::getElapsed( $id, $format );
	}


}