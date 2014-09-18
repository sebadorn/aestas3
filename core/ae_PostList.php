<?php

class ae_PostList extends ae_List {


	protected $items = array();


	/**
	 * Constructor.
	 * Fetches all posts from the DB.
	 */
	public function __construct() {
		$stmt = '
			SELECT * FROM `' . ae_PostModel::TABLE . '`
			ORDER BY po_datetime DESC
		';
		$result = ae_Database::query( $stmt );

		if( $result !== FALSE ) {
			foreach( $result as $item ) {
				$this->items[] = new ae_PostModel( $item );
			}
		}

		reset( $this->items );
	}


}
