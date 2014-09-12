<?php

abstract class ae_Model {


	/**
	 * Generate a permalink from the title.
	 * @param  {string} $title Title to convert into a permalink.
	 * @return {string}        The permalink.
	 */
	static public function generatePermalink( $title ) {
		$permalink = strtolower( $title );
		$permalink = str_replace( ' ', '-', $permalink );
		$permalink = str_replace( '/', '-', $permalink );
		$permalink = preg_replace( '/[-]+/', '-', $permalink );
		$permalink = preg_replace( '/[?&#\\\\]/', '', $permalink );

		return $permalink;
	}


}
