<?php

class ae_Search {


	protected $items = array();
	protected $numResults = 20;


	public function __construct( $numResults = 20 ) {
		$this->numResults = $numResults;
	}


	public function search( $term ) {
		$filter = array(
			'WHERE' => '
				po_status = :status AND
				po_datetime <= :date AND
				po_title LIKE :term
			',
			'ORDER BY' => 'po_datetime DESC',
			'LIMIT' => '0, ' . $this->numResults
		);
		$params = array(
			':date' => date( 'Y-m-d H:i:s' ),
			':status' => ae_PostModel::STATUS_PUBLISHED,
			':term' => '%' . $term . '%'
		);
		$poList = new ae_PostList( $filter, $params );

		$this->items = $poList->getItems();
	}


}
