<?php

class ae_UserList extends ae_List {


	const ITEM_CLASS = 'ae_UserModel';

	static protected $defaultFilter = array(
		'LIMIT' => '0, 25',
		'ORDER BY' => 'u_id ASC'
	);


	/**
	 * Constructor.
	 * Fetches all users from the DB.
	 * @param {array} $filter Array of filters to apply to the MySQL statement. (Optional.)
	 */
	public function __construct( $filter = array() ) {
		parent::__construct( self::ITEM_CLASS, $filter, self::$defaultFilter );
	}


}
