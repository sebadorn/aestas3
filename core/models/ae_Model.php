<?php

abstract class ae_Model {


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


}
