<?php

class ae_SiteBuilder {


	/**
	 * Constructor.
	 */
	public function __construct() {
		//
	}


	/**
	 * Render a template.
	 * @param {string} $template Path to file.
	 * @param {mixed}  $data     Data to make available.
	 */
	public function render( $template, $data = NULL ) {
		if( !include( $template ) ) {
			$msg = sprintf(
				'[%s] Failed to include file <code>"%s"</code>.',
				get_class(), htmlspecialchars( $template )
			);
			ae_Log::error( $msg );
		}
	}


}