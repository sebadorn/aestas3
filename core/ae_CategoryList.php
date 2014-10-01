<?php

class ae_CategoryList extends ae_List {


	const ITEM_CLASS = 'ae_CategoryModel';

	static protected $defaultFilter = array(
		'LIMIT' => '0, 20',
		'ORDER BY' => 'ca_id ASC'
	);


	/**
	 * Constructor.
	 * Fetches all categories from the DB.
	 * @param {array}   $filter     Array of filters to apply to the MySQL statement. (Optional.)
	 * @param {boolean} $countItems If TRUE, extends the DB query to count the number of items. (Optional, defaults to TRUE.)
	 */
	public function __construct( $filter = array(), $countItems = TRUE ) {
		parent::__construct( self::ITEM_CLASS, $filter, self::$defaultFilter, $countItems );
	}


}
