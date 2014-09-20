<?php

class ae_CategoryList extends ae_List {


	const ITEM_CLASS = 'ae_CategoryModel';

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
		parent::__construct( self::ITEM_CLASS, $filter, self::$defaultFilter );
	}


}
