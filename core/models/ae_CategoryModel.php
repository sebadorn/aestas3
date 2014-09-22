<?php

class ae_CategoryModel extends ae_Model {


	const STATUS_AVAILABLE = 'available';
	const STATUS_TRASH = 'trash';

	const TABLE = AE_TABLE_CATEGORIES;
	const TABLE_ID_FIELD = 'ca_id';

	protected $children = array();
	protected $parent = 0;
	protected $permalink = '';
	protected $status = self::STATUS_AVAILABLE;
	protected $title = '';


	/**
	 * Constructor.
	 * @param {array} $data Category data to initialize the model with. (Optional.)
	 */
	public function __construct( $data = array() ) {
		$this->loadFromData( $data );
	}


	/**
	 * Get children (sub-categories).
	 * @return {array} Category children.
	 */
	public function getChildren() {
		return $this->children;
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
	 * Get category status.
	 * @return {string} Category status.
	 */
	public function getStatus() {
		return $this->status;
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
		$modelData = $this->loadModelData( $id );

		if( $modelData === FALSE ) {
			return FALSE;
		}

		$this->loadFromData( $modelData );
		$this->loadChildren( $id );

		return TRUE;
	}


	/**
	 * Load children (sub-categories) of a category.
	 * @param  {int}     $id ID of the parent category.
	 * @return {boolean}     TRUE, if loading succeeded, FALSE otherwise.
	 */
	protected function loadChildren( $id ) {
		$stmt = '
			SELECT ca_id
			FROM `' . AE_TABLE_CATEGORIES . '`
			WHERE
				ca_parent = :id AND
				ca_status = :status
		';
		$params = array(
			':id' => $id,
			':status' => self::STATUS_AVAILABLE
		);
		$result = ae_Database::query( $stmt, $params );

		if( $result === FALSE ) {
			return FALSE;
		}

		$children = array();

		foreach( $result as $row ) {
			$children[] = $row['ca_id'];
		}

		$this->setChildren( $children );

		return TRUE;
	}


	/**
	 * Initialize model from the given data.
	 * @param {array} $data The model data.
	 */
	protected function loadFromData( $data ) {
		if( isset( $data['ca_id'] ) ) {
			$this->setId( $data['ca_id'] );
		}
		if( isset( $data['ca_parent'] ) ) {
			$this->setParent( $data['ca_parent'] );
		}
		if( isset( $data['ca_permalink'] ) ) {
			$this->setPermalink( $data['ca_permalink'] );
		}
		if( isset( $data['ca_status'] ) ) {
			$this->setStatus( $data['ca_status'] );
		}
		if( isset( $data['ca_title'] ) ) {
			$this->setTitle( $data['ca_title'] );
		}
	}


	/**
	 * Save the category to DB. If an ID is set, it will update
	 * the category, otherwise it will create a new one.
	 * Changes on the children attribute will not be saved!
	 * To change parent-child relations, edit the child.
	 * @param  {boolean}   $forceInsert If set to TRUE and an ID has been set, the model will be saved
	 *                                  as new entry instead of updating. (Optional, default is FALSE.)
	 * @return {boolean}                TRUE, if saving is successful, FALSE otherwise.
	 * @throws {Exception}              If title is not valid.
	 * @throws {Exception}              If $forceInsert is TRUE, but no valid ID is set.
	 */
	public function save( $forceInsert = FALSE ) {
		if( $this->title == '' ) {
			throw new Exception( '[' . get_class() . '] Cannot save category. Invalid title.' );
		}

		if( $this->permalink == '' ) {
			$this->permalink = self::generatePermalink( $this->title );
		}

		$params = array(
			':title' => $this->title,
			':permalink' => $this->permalink,
			':parent' => $this->parent,
			':status' => $this->status
		);

		// Create new category
		if( $this->id === FALSE && !$forceInsert ) {
			$stmt = '
				INSERT INTO `' . AE_TABLE_CATEGORIES . '` (
					ca_title,
					ca_permalink,
					ca_parent,
					ca_status
				)
				VALUES (
					:title,
					:permalink,
					:parent,
					:status
				)
			';
		}
		// Create new category with set ID
		else if( $this->id !== FALSE && $forceInsert ) {
			$stmt = '
				INSERT INTO `' . AE_TABLE_CATEGORIES . '` (
					ca_id,
					ca_title,
					ca_permalink,
					ca_parent,
					ca_status
				)
				VALUES (
					:id,
					:title,
					:permalink,
					:parent,
					:status
				)
			';
			$params[':id'] = $this->id;
		}
		// Update existing one
		else if( $this->id !== FALSE ) {
			$stmt = '
				UPDATE `' . AE_TABLE_CATEGORIES . '` SET
					ca_title = :title,
					ca_permalink = :permalink,
					ca_parent = :parent,
					ca_status = :status
				WHERE
					ca_id = :id
			';
			$params[':id'] = $this->id;
		}
		else {
			$msg = sprintf( '[%s] Supposed to insert new category with set ID, but no ID has been set.', get_class() );
			throw new Exception( $msg );
		}

		if( ae_Database::query( $stmt, $params ) === FALSE ) {
			return FALSE;
		}

		// If a new category was created, get the new ID
		if( $this->id === FALSE ) {
			$stmt = 'SELECT DISTINCT LAST_INSERT_ID() as id FROM `' . AE_TABLE_CATEGORIES . '`';
			$result = ae_Database::query( $stmt );

			if( $result === FALSE ) {
				return FALSE;
			}

			$this->setId( $result[0]['id'] );
		}

		return TRUE;
	}


	/**
	 * Set children of category.
	 * @param {array} $children IDs of child categories.
	 */
	public function setChildren( $children ) {
		if( !is_array( $children ) ) {
			throw new Exception( '[' . get_class() . '] Child categories must be passed as array.' );
		}

		$this->children = $children;
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
	 * Set category status.
	 * @param  {string}    $status Category status.
	 * @throws {Exception}         If $status is not a valid category status.
	 */
	public function setStatus( $status ) {
		$validStatuses = array( self::STATUS_AVAILABLE, self::STATUS_TRASH );

		if( !in_array( $status, $validStatuses ) ) {
			$msg = sprintf( '[%s] Not a valid status: %s', get_class(), htmlspecialchars( $status ) );
			throw new Exception( $msg );
		}

		$this->status = $status;
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