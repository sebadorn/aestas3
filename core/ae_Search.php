<?php

class ae_Search {


	protected $items = array();


	public function __construct() {
		//
	}


	public function search( $term ) {
		$filter = array();
		$poList = new ae_PostList( $filter );

		$this->items = $poList->getItems();
	}


}
