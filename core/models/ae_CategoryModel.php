<?php

class ae_CategoryModel {


	protected $id = FALSE;
	protected $parent = 0;
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
	 * Generate a permalink from the title.
	 * @param  {string} $title Title to convert into a permalink.
	 * @return {string}        The permalink.
	 */
	static public function generatePermalink( $title ) {
		$permalink = strtolower( $title );
		$permalink = str_replace( ' ', '-', $permalink );
		$permalink = str_replace( '/', '-', $permalink );
		$permalink = preg_replace( '/[-]+/', '-', $permalink );
		$permalink = preg_replace( '/[?&#\\\\]/', '', $permalink );

		return $permalink;
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