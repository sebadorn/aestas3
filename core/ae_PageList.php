<?php

class ae_PageList extends ae_List {


	static protected $defaultFilter = array(
		'LIMIT' => '0, 25',
		'ORDER BY' => 'pa_datetime DESC'
	);


	/**
	 * Constructor.
	 * Fetches all pages from the DB.
	 * @param {array} $filter Array of filters to apply to the MySQL statement. (Optional.)
	 */
	public function __construct( $filter = array() ) {
		$filter = self::buildFilter( $filter, self::$defaultFilter );

		$numStmt = '
			SELECT COUNT( pa_id ) FROM `' . ae_PageModel::TABLE . '`
		';
		$numStmt = self::buildStatement( $numStmt, $filter );

		$base = '
			SELECT *, ( ' . $numStmt . ' ) AS num_entries
			FROM `' . ae_PageModel::TABLE . '`
		';
		$stmt = self::buildStatement( $base, $filter );

		$result = ae_Database::query( $stmt );

		if( $result === FALSE || empty( $result ) ) {
			return;
		}

		foreach( $result as $item ) {
			$this->items[] = new ae_PageModel( $item );
		}

		reset( $this->items );
		$this->totalItems = $result[0]['num_entries'];
	}


}
