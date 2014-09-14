<?php

class ae_Settings {


	/**
	 * Check if mod_rewrite is enabled on the server.
	 * @return {boolean} TRUE, if mod_rewrite is enabled, FALSE otherwise.
	 */
	static public function isModRewriteEnabled() {
		return in_array( 'mod_rewrite', apache_get_modules() );
	}


}
