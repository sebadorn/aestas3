<?php

class ae_PostList extends ae_List {


	const ITEM_CLASS = 'ae_PostModel';

	static protected $defaultFilter = array(
		'LIMIT' => '0, 20',
		'ORDER BY' => 'po_datetime DESC'
	);


	/**
	 * Constructor.
	 * Fetches all posts from the DB.
	 * @param {array} $filter Array of filters to apply to the MySQL statement. (Optional.)
	 */
	public function __construct( $filter = array() ) {
		parent::__construct( self::ITEM_CLASS, $filter, self::$defaultFilter );
	}


}
