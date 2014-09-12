<?php

class ae_CategoryModel extends ae_Model {


	protected $id = FALSE;
	protected $parent = 0;
	protected $permalink = '';
	protected $title = '';


	/**
	 * Constructor.
	 * @param {array} $data Category data to initialize the model with. (Optional.)
	 */
	public function __construct( $data = array() ) {
		if( isset( $data['ca_id'] ) ) {
			$this->setId( $data['ca_id'] );
		}
		if( isset( $data['ca_parent'] ) ) {
			$this->setParent( $data['ca_parent'] );
		}
		if( isset( $data['ca_permalink'] ) ) {
			$this->setPermalink( $data['ca_permalink'] );
		}
		if( isset( $data['ca_title'] ) ) {
			$this->setTitle( $data['ca_title'] );
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
	 * Load a category with the given ID.
	 * @param  {int}     $id ID of the category to load.
	 * @return {boolean}     TRUE, if loading succeeded, FALSE otherwise.
	 */
	public function load( $id ) {
		$this->setId( $id );

		if( $this->id === FALSE ) {
			return FALSE;
		}

		$stmt = 'SELECT * FROM ' . AE_TABLE_CATEGORIES . ' WHERE ca_id = :id';
		$params = array(
			':id' => $id
		);
		$result = ae_Database::query( $stmt, $params );

		if( $result === FALSE ) {
			return FALSE;
		}

		$this->setTitle( $result[0]['ca_title'] );
		$this->setPermalink( $result[0]['ca_permalink'] );
		$this->setParent( $result[0]['ca_parent'] );

		return TRUE;
	}


	/**
	 * Save the category to DB. If an ID is set, it will update
	 * the category, otherwise it will create a new one.
	 * @throws {Exception} If title is not valid.
	 * @return {boolean}   TRUE, if saving is successful, FALSE otherwise.
	 */
	public function save() {
		if( $this->title == '' ) {
			throw new Exception( '[' . get_class() . '] Cannot save category. Invalid title.' );
		}

		if( $this->permalink == '' ) {
			$this->permalink = self::generatePermalink( $this->title );
		}

		$params = array(
			':title' => $this->title,
			':permalink' => $this->permalink,
			':parent' => $this->parent
		);

		// Create new category
		if( $this->id === FALSE ) {
			$stmt = 'INSERT INTO ' . AE_TABLE_CATEGORIES . ' ( ca_title, ca_permalink, ca_parent )';
			$stmt .= ' VALUES ( :title, :permalink, :parent )';
		}
		// Update existing one
		else {
			$stmt = 'UPDATE ' . AE_TABLE_CATEGORIES . ' SET';
			$stmt .= ' ca_title = :title, ca_permalink = :permalink, ca_parent = :parent';
			$stmt .= ' WHERE ca_id = :id';
			$params[':id'] = $this->id;
		}

		if( ae_Database::query( $stmt, $params ) === FALSE ) {
			return FALSE;
		}

		// If a new category was created, get the new ID
		if( $this->id === FALSE ) {
			$stmt = 'SELECT DISTINCT LAST_INSERT_ID() as ca_id FROM ' . AE_TABLE_CATEGORIES;
			$result = ae_Database::query( $stmt );

			if( $result === FALSE ) {
				return FALSE;
			}

			$this->setId( $result[0]['ca_id'] );
		}

		return TRUE;
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
	 * @param  {int}       $parent New category parent ID.
	 * @throws {Exception}         If $parent is not valid.
	 */
	public function setParent( $parent ) {
		if( !ae_Validate::id( $parent ) ) {
			throw new Exception( '[' . get_class() . '] Not a valid ID: ' . htmlspecialchars( $parent ) );
		}

		$this->parent = $parent;
	}


	/**
	 * Set the category permalink.
	 * @param  {string} $permalink The new permalink.
	 * @return {string}            The actually used permalink.
	 */
	public function setPermalink( $permalink ) {
		$this->permalink = self::generatePermalink( $permalink );

		return $this->permalink;
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