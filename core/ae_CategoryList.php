<?php

class ae_CategoryList extends ae_List {


	protected $items = array();


	/**
	 * Constructor.
	 * Fetches all categories from the DB.
	 */
	public function __construct() {
		$stmt = '
			SELECT * FROM `' . ae_CategoryModel::TABLE . '`
			ORDER BY ca_id ASC
		';
		$result = ae_Database::query( $stmt );

		if( $result !== FALSE ) {
			foreach( $result as $item ) {
				$this->items[] = new ae_CategoryModel( $item );
			}
		}

		reset( $this->items );
	}


}
