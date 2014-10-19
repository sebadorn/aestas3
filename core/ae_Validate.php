<?php

class ae_Validate {


	/**
	 * Validate datetime with the expected format: yyyy-mm-dd hh:ii:ss
	 * @param  {string}  $input Datetime to validate.
	 * @return {boolean}        TRUE, if $datetime matches, FALSE otherwise.
	 */
	static public function datetime( $input ) {
		return ( preg_match( '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $input ) === 1 );
	}


	/**
	 * Validate eMail address.
	 * @param  {string}  $input Mail address to validate.
	 * @return {boolean}        TRUE, if matches an eMail address, FALSE otherwise.
	 */
	static public function emailSloppy( $input ) {
		return ( preg_match( '/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $input ) === 1 );
	}


	/**
	 * Validate ID. (A positive, non-zero integer.)
	 * @param  {int|string} $input ID to validate.
	 * @return {boolean}           TRUE, if matches an ID, FALSE otherwise.
	 */
	static public function id( $input ) {
		return ( preg_match( '/^[1-9][0-9]*$/', $input ) === 1 );
	}


	/**
	 * Validate integer (not by type), positive or negative.
	 * @param  {int|string} $input Integer to validate.
	 * @return {boolean}           TRUE, if matches an integer, FALSE otherwise.
	 */
	static public function integer( $input ) {
		return ( preg_match( '/^-?[0-9]+$/', $input ) === 1 );
	}


	/**
	 * Validate IP. (IPv4 and IPv6.)
	 * @param  {string}  $input IP to validate.
	 * @return {boolean}        TRUE, if matches an IP, FALSE otherwise.
	 */
	static public function ip( $input ) {
		return ( filter_var( $input, FILTER_VALIDATE_IP ) !== FALSE );
	}


	/**
	 * Sloppy URL validation.
	 * @param  {string}  $input URL to validate.
	 * @return {boolean}        TRUE, if $input somewhat resamples an URL, FALSE otherwise.
	 */
	static public function urlSloppy( $input ) {
		return ( preg_match( '/^(http|ftp)s?:\/\/[^\s"\']+$/', $input ) === 1 );
	}


}