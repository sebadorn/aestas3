<?php

abstract class ae_List {


	protected $items = array();
	protected $totalItems = 0; // Number of items in the DB


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

		if( isset( $filter['WHERE'] ) ) {
			$stmt .= ' WHERE ' . $filter['WHERE'];
		}
		if( isset( $filter['ORDER BY'] ) ) {
			$stmt .= ' ORDER BY ' . $filter['ORDER BY'];
		}
		if( isset( $filter['LIMIT'] ) ) {
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
