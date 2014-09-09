<?php

class ae_SiteBuilder {


	public function __construct() {
		//
	}


	public function render( $template, $data = NULL ) {
		include( $template );
	}


}