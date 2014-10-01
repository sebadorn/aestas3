<?php

class ae_CommentList extends ae_List {


	const ITEM_CLASS = 'ae_CommentModel';

	static protected $defaultFilter = array(
		'LIMIT' => '0, 20',
		'ORDER BY' => 'co_datetime DESC'
	);


	/**
	 * Constructor.
	 * Fetches all comments from the DB.
	 * @param {array}   $filter     Array of filters to apply to the MySQL statement. (Optional.)
	 * @param {boolean} $countItems If TRUE, extends the DB query to count the number of items. (Optional, defaults to TRUE.)
	 */
	public function __construct( $filter = array(), $countItems = TRUE ) {
		parent::__construct( self::ITEM_CLASS, $filter, self::$defaultFilter, $countItems );
	}


}
