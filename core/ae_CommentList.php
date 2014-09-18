<?php

class ae_CommentList extends ae_List {


	protected $items = array();


	/**
	 * Constructor.
	 * Fetches all comments from the DB.
	 */
	public function __construct() {
		$stmt = '
			SELECT * FROM `' . ae_CommentModel::TABLE . '`
			ORDER BY co_datetime DESC
		';
		$result = ae_Database::query( $stmt );

		if( $result !== FALSE ) {
			foreach( $result as $item ) {
				$this->items[] = new ae_CommentModel( $item );
			}
		}

		reset( $this->items );
	}


}
