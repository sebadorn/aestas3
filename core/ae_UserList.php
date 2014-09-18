<?php

class ae_UserList extends ae_List {


	protected $items = array();


	/**
	 * Constructor.
	 * Fetches all users from the DB.
	 */
	public function __construct() {
		$stmt = '
			SELECT * FROM `' . ae_UserModel::TABLE . '`
			ORDER BY u_id ASC
		';
		$result = ae_Database::query( $stmt );

		if( $result !== FALSE ) {
			foreach( $result as $item ) {
				$this->items[] = new ae_UserModel( $item );
			}
		}

		reset( $this->items );
	}


}
