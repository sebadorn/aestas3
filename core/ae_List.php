<?php

abstract class ae_List {


	protected $items = array();
	protected $totalItems = 0; // Number of items in the DB


	/**
	 * Constructor.
	 * @param {string} $itemClass     Name of the class to use for the items.
	 * @param {array}  $filter        Given filter.
	 * @param {array}  $defaultFilter Default filter.
	 */
	public function __construct( $itemClass, $filter, $defaultFilter ) {
		$filter = self::buildFilter( $filter, $defaultFilter );
		$numFilter = $filter;
		unset( $numFilter['LIMIT'] );

		$table = constant( $itemClass . '::TABLE' );

		// SQL statement for number of items
		$numStmt = '
			SELECT COUNT( * ) FROM `' . $table . '`
		';
		$numStmt = self::buildStatement( $numStmt, $numFilter );

		// Combined statement
		$base = '
			SELECT *, ( ' . $numStmt . ' ) AS num_entries
			FROM `' . $table . '`
		';
		$stmt = self::buildStatement( $base, $filter );

		$result = ae_Database::query( $stmt );

		if( $result === FALSE || empty( $result ) ) {
			return;
		}

		// Initialize items
		foreach( $result as $item ) {
			$this->items[] = new $itemClass( $item );
		}

		reset( $this->items );
		$this->totalItems = $result[0]['num_entries'];
	}


	/**
	 * Build the filter array by filling it with missing default values.
	 * @param  {array} $filter        Received filter array.
	 * @param  {array} $defaultFilter Default filter values.
	 * @return {array}                Complete filter array.
	 */
	static protected function buildFilter( $filter, $defaultFilter ) {
		foreach( $defaultFilter as $key => $value ) {
			if( !isset( $filter[$key] ) ) {
				$filter[$key] = $value;
			}
		}

		return $filter;
	}


	/**
	 * Build the MySQL statement by using given filter options.
	 * @param  {string} $base   The MySQL SELECT FROM part.
	 * @param  {array}  $filter Filter to add.
	 * @return {string}         The statement.
	 */
	static protected function buildStatement( $base, $filter ) {
		$stmt = $base;

		if( isset( $filter['WHERE'] ) && $filter['WHERE'] !== FALSE ) {
			$stmt .= ' WHERE ' . $filter['WHERE'];
		}
		if( isset( $filter['ORDER BY'] ) && $filter['ORDER BY'] !== FALSE ) {
			$stmt .= ' ORDER BY ' . $filter['ORDER BY'];
		}
		if( isset( $filter['LIMIT'] ) && $filter['LIMIT'] !== FALSE ) {
			$stmt .= ' LIMIT ' . $filter['LIMIT'];
		}

		return $stmt;
	}


	/**
	 * Get the current item.
	 * @return {mixed} The current item.
	 */
	public function current() {
		return current( $this->items );
	}


	/**
	 * Get the number of loaded items.
	 * @return {int} Number of loaded items.
	 */
	public function getNumItems() {
		return count( $this->items );
	}


	/**
	 * Get the total number of items in the DB.
	 * @return {int} Total number of items.
	 */
	public function getTotalNumItems() {
		return $this->totalItems;
	}


	/**
	 * Check, if there is a next item in the list.
	 * @return {boolean} TRUE, if there is at least one more item, FALSE otherwise.
	 */
	public function hasNext() {
		return ( current( $this->items ) !== FALSE );
	}


	/**
	 * Get the current item and advance the internal pointer.
	 * @return {mixed} The current item.
	 */
	public function next() {
		$item = $this->current();
		next( $this->items );

		return $item;
	}


	/**
	 * Reset internal pointer back to the beginning.
	 */
	public function reset() {
		reset( $this->items );
	}


}
