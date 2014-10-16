<?php

class ae_Search extends ae_PostList {


	protected $items = array();
	protected $numResults = 20;


	/**
	 * Constructor.
	 * @param {integer} $numResults Number of results to fetch.
	 */
	public function __construct( $numResults = 20 ) {
		$this->numResults = $numResults;
	}


	/**
	 * Search for the given term.
	 * @param {string} $term Search term.
	 */
	public function search( $term ) {
		$term = trim( $term );

		if( mb_strlen( $term ) == 0 ) {
			return FALSE;
		}

		$filter = array(
			'WHERE' => '
				po_status = :status AND
				po_datetime <= :date AND (
					po_title LIKE :term OR
					po_tags LIKE :term
				)
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

		$this->loadCategories();
		$this->loadNumComments();
	}


}
