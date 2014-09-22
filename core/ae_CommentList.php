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
	 * @param {array} $filter Array of filters to apply to the MySQL statement. (Optional.)
	 */
	public function __construct( $filter = array() ) {
		parent::__construct( self::ITEM_CLASS, $filter, self::$defaultFilter );
	}


}
