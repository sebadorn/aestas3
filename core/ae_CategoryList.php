<?php

class ae_CategoryList extends ae_List {


	protected $items = array();


	/**
	 * Constructor.
	 * Fetches all categories from the DB.
	 */
	public function __construct() {
		$stmt = sprintf( 'SELECT * FROM `%s` ORDER BY ca_id ASC', AE_TABLE_CATEGORIES );
		$result = ae_Database::query( $stmt );

		if( $result !== FALSE ) {
			foreach( $result as $ca ) {
				$this->items[] = new ae_CategoryModel( $ca );
			}
		}

		reset( $this->items );
	}


}
