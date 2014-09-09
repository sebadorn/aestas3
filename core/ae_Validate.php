<?php

class ae_Validate {


	/**
	 * Validate integer (not by type), positive or negative.
	 * @param  {int|string} $input Integer to validate.
	 * @return {boolean}           TRUE, if matches an integer, FALSE otherwise.
	 */
	static public function integer( $input ) {
		return ( preg_match( '/^-?[0-9]+$/', $input ) === 1 );
	}


	/**
	 * Validate ID. (A positive integer.)
	 * @param  {int|string} $input ID to validate.
	 * @return {boolean}           TRUEm if matches an ID, FALSE otherwise.
	 */
	static public function id( $input ) {
		return ( preg_match( '/^[0-9]+$/', $input ) === 1 );
	}


}