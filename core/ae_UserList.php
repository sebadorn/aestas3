<?php

class ae_UserList extends ae_List {


	protected $items = array();


	/**
	 * Constructor.
	 * Fetches all users from the DB.
	 */
	public function __construct() {
		$stmt = '
			SELECT * FROM `' . AE_TABLE_USERS . '`
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
