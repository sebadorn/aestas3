<?php

class ae_CategoryList extends ae_List {


	static protected $defaultFilter = array(
		'LIMIT' => '0, 25',
		'ORDER BY' => 'ca_id ASC'
	);


	/**
	 * Constructor.
	 * Fetches all categories from the DB.
	 * @param {array} $filter Array of filters to apply to the MySQL statement. (Optional.)
	 */
	public function __construct( $filter = array() ) {
		$filter = self::buildFilter( $filter, self::$defaultFilter );

		$numStmt = '
			SELECT COUNT( ca_id ) FROM `' . ae_CategoryModel::TABLE . '`
		';
		$numStmt = self::buildStatement( $numStmt, $filter );

		$base = '
			SELECT *, ( ' . $numStmt . ' ) AS num_entries
			FROM `' . ae_CategoryModel::TABLE . '`
		';
		$stmt = self::buildStatement( $base, $filter );

		$result = ae_Database::query( $stmt );

		if( $result === FALSE || empty( $result ) ) {
			return;
		}

		foreach( $result as $item ) {
			$this->items[] = new ae_CategoryModel( $item );
		}

		reset( $this->items );
		$this->totalItems = $result[0]['num_entries'];
	}


}
