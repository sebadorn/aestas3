<?php

class ae_PageList extends ae_List {


	protected $items = array();


	/**
	 * Constructor.
	 * Fetches all pages from the DB.
	 */
	public function __construct() {
		$stmt = '
			SELECT * FROM `' . ae_PageModel::TABLE . '`
			ORDER BY pa_datetime DESC
		';
		$result = ae_Database::query( $stmt );

		if( $result !== FALSE ) {
			foreach( $result as $item ) {
				$this->items[] = new ae_PageModel( $item );
			}
		}

		reset( $this->items );
	}


}
