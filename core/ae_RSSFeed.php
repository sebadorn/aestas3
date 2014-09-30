<?php

class ae_RSSFeed {


	protected $items = array();


	/**
	 * Constructor.
	 * @param {array} $items Items for the feed.
	 */
	public function __construct( $items ) {
		if( !is_array( $items ) ) {
			$msg = sprintf( '[%s] Expected array.', get_class() );
			throw new Exception( $msg );
		}

		$this->items = $items;
		reset( $this->items );
	}


	/**
	 * Get the current item and move the internal pointer forward.
	 * @return {ae_Model} Current item.
	 */
	public function nextItem() {
		if( current( $this->items ) === FALSE ) {
			return FALSE;
		}

		$item = current( $this->items );
		next( $this->items );

		return $item;
	}


}
