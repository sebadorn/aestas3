<?php

class ae_PageList extends ae_List {


	const ITEM_CLASS = 'ae_PageModel';

	static protected $defaultFilter = array(
		'LIMIT' => '0, 20',
		'ORDER BY' => 'pa_datetime DESC'
	);


	/**
	 * Constructor.
	 * Fetches all pages from the DB.
	 * @param {array} $filter Array of filters to apply to the MySQL statement. (Optional.)
	 */
	public function __construct( $filter = array() ) {
		parent::__construct( self::ITEM_CLASS, $filter, self::$defaultFilter );
	}


}
