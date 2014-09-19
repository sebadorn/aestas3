<?php

class ae_CommentList extends ae_List {


	static protected $defaultFilter = array(
		'LIMIT' => '0, 50',
		'ORDER BY' => 'co_datetime DESC'
	);


	/**
	 * Constructor.
	 * Fetches all comments from the DB.
	 * @param {array} $filter Array of filters to apply to the MySQL statement. (Optional.)
	 */
	public function __construct( $filter = array() ) {
		$filter = self::buildFilter( $filter, self::$defaultFilter );

		$numStmt = '
			SELECT COUNT( co_id ) FROM `' . ae_CommentModel::TABLE . '`
		';
		$numStmt = self::buildStatement( $numStmt, $filter );

		$base = '
			SELECT *, ( ' . $numStmt . ' ) AS num_entries
			FROM `' . ae_CommentModel::TABLE . '`
		';
		$stmt = self::buildStatement( $base, $filter );

		$result = ae_Database::query( $stmt );

		if( $result === FALSE || empty( $result ) ) {
			return;
		}

		foreach( $result as $item ) {
			$this->items[] = new ae_CommentModel( $item );
		}

		reset( $this->items );
		$this->totalItems = $result[0]['num_entries'];
	}


}
