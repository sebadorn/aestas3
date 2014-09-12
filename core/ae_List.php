<?php

abstract class ae_List {


	protected $items = array();


	/**
	 * Get the current item.
	 * @return {mixed} The current item.
	 */
	public function current() {
		return current( $this->items );
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
