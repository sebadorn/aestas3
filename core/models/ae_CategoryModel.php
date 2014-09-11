<?php

class ae_CategoryModel {


	protected $id = FALSE;
	protected $parent = FALSE;
	protected $permalink = '';
	protected $title = '';


	/**
	 * Constructor.
	 * @param {array} $data Category data to initialize the model with. (Optional.)
	 */
	public function __construct( $data = array() ) {
		if( isset( $data['id'] ) ) {
			$this->setId( $data['id'] );
		}
		if( isset( $data['parent'] ) ) {
			$this->setParent( $data['parent'] );
		}
		if( isset( $data['permalink'] ) ) {
			$this->setPermalink( $data['permalink'] );
		}
		if( isset( $data['title'] ) ) {
			$this->setTitle( $data['title'] );
		}
	}


	/**
	 * Get category ID.
	 * @return {int} Category ID.
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * Get category parent ID.
	 * @return {int|boolean} Category parent ID or FALSE, if no parent exists.
	 */
	public function getParent() {
		return $this->parent;
	}


	/**
	 * Get category permalink.
	 * @return {string} Category permalink.
	 */
	public function getPermalink() {
		return $this->permalink;
	}


	/**
	 * Get category title.
	 * @return {string} Category title.
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * Set the category ID.
	 * @param  {int}       $id New category ID.
	 * @throws {Exception}     If $id is not valid.
	 */
	public function setId( $id ) {
		if( !ae_Validate::id( $id ) ) {
			throw new Exception( '[' . get_class() . '] Not a valid ID: ' . htmlspecialchars( $id ) );
		}

		$this->id = $id;
	}


	/**
	 * Set the category parent ID.
	 * @param  {int}       $id New category parent ID.
	 * @throws {Exception}     If $id is not valid.
	 */
	public function setParent( $id ) {
		if( !ae_Validate::id( $id ) ) {
			throw new Exception( '[' . get_class() . '] Not a valid ID: ' . htmlspecialchars( $id ) );
		}

		$this->id = $id;
	}


	/**
	 * Set the category permalink.
	 * @param {string} $permalink The new permalink.
	 */
	public function setPermalink( $permalink ) {
		$this->permalink = $permalink;
	}


	/**
	 * Set the category title.
	 * @param  {string}    $title New category title.
	 * @throws {Exception}        If $title is empty.
	 */
	public function setTitle( $title ) {
		if( strlen( $title ) == 0 ) {
			throw new Exception( '[' . get_class() . '] Category title cannot be empty.' );
		}

		$this->title = $title;
	}


}