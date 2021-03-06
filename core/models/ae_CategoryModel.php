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
	 * Delete the loaded category, remove all its parent-child and post relations.
	 * @return {boolean} FALSE, if deletion failed,
	 *                   TRUE otherwise (including the case that the model doesn't exist).
	 */
	public function delete() {
		if( !parent::delete() ) {
			return FALSE;
		}

		$stmt = '
			UPDATE `' . self::TABLE . '`
			SET ca_parent = 0
			WHERE ca_parent = :id
		';
		$params = array(
			':id' => $this->id
		);

		if( ae_Database::query( $stmt, $params ) === FALSE ) {
			return FALSE;
		}

		$stmt = '
			DELETE FROM `' . AE_TABLE_POSTS2CATEGORIES . '`
			WHERE pc_category = :id
		';

		return ( ae_Database::query( $stmt, $params ) !== FALSE );
	}


	/**
	 * Get children (sub-categories).
	 * @return {array} Category children.
	 */
	public function getChildren() {
		return $this->children;
	}


	/**
	 * Get complete permalink for the category (not including the domain and directory).
	 * @param  {string} $urlBase URL base of the link. (Optional, defaults to constant "URL".)
	 * @return {string}          Complete permalink.
	 */
	public function getLink( $urlBase = URL ) {
		if( ae_Settings::isModRewriteEnabled() ) {
			$link= $urlBase . PERMALINK_BASE_CATEGORY . $this->getPermalink();
		}
		else {
			$urlBase .= ( $urlBase[mb_strlen( $urlBase ) - 1] == '?' ) ? '&amp;' : '?';
			$link = $urlBase . PERMALINK_GET_CATEGORY . '=' . $this->getId();
		}

		return $link;
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
	 * Check, if given status is a valid category status.
	 * @param  {string}  $status Category status.
	 * @return {boolean}         TRUE, if $status is valid, FALSE otherwise.
	 */
	static public function isValidStatus( $status ) {
		return in_array( $status, self::listStatuses(), TRUE );
	}


	/**
	 * Get a list of valid statuses.
	 * @return {array} List of valid statuses.
	 */
	static public function listStatuses() {
		return array( self::STATUS_AVAILABLE, self::STATUS_TRASH );
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
			FROM `' . self::TABLE . '`
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
	 * Load model data from DB identified by the given permalink.
	 * @param {string} $permalink Permalink to identify the category by.
	 */
	public function loadFromPermalink( $permalink, $loadChildren = FALSE ) {
		$stmt = '
			SELECT * FROM `' . self::TABLE . '`
			WHERE ca_permalink = :permalink
		';
		$params = array(
			':permalink' => $permalink
		);

		$result = ae_Database::query( $stmt, $params );

		if( $result === FALSE || count( $result ) < 1 ) {
			return FALSE;
		}

		$this->loadFromData( $result[0] );

		if( $loadChildren ) {
			return $this->loadChildren( $this->getId() );
		}

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
			$this->permalink = ae_Permalink::generatePermalink( $this->title );
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
				INSERT INTO `' . self::TABLE . '` (
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
				INSERT INTO `' . self::TABLE . '` (
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
				UPDATE `' . self::TABLE . '` SET
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
			$this->setId( $this->getLastInsertedId() );
		}

		return TRUE;
	}


	/**
	 * Set children of category.
	 * @param {array} $children IDs of child categories.
	 */
	public function setChildren( $children ) {
		if( !is_array( $children ) ) {
			$msg = sprintf( '[%s] Child categories must be passed as array.', get_class() );
			throw new Exception( $msg );
		}

		foreach( $children as $child ) {
			if( !ae_Validate::id( $child ) ) {
				$msg = sprintf( '[%s] Category child ID invalid: %s', get_class(), htmlspecialchars( $child ) );
				throw new Exception( $msg );
			}
		}

		$this->children = $children;
	}


	/**
	 * Set the category parent ID.
	 * @param  {int}       $parent New category parent ID.
	 * @throws {Exception}         If $parent is not valid.
	 */
	public function setParent( $parent ) {
		if( $parent != 0 && !ae_Validate::id( $parent ) ) {
			$msg = sprintf( '[%s] Not a valid ID: %s', get_class(), htmlspecialchars( $parent ) );
			throw new Exception( $msg );
		}

		$this->parent = $parent;
	}


	/**
	 * Set the category permalink.
	 * @param  {string} $permalink The new permalink.
	 * @return {string}            The actually used permalink.
	 */
	public function setPermalink( $permalink ) {
		$this->permalink = ae_Permalink::generatePermalink( $permalink );

		return $this->permalink;
	}


	/**
	 * Set category status.
	 * @param  {string}    $status Category status.
	 * @throws {Exception}         If $status is not a valid category status.
	 */
	public function setStatus( $status ) {
		if( !self::isValidStatus( $status ) ) {
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
		if( mb_strlen( $title ) == 0 ) {
			$msg = sprintf( '[%s] Category title cannot be empty.', get_class() );
			throw new Exception( $msg );
		}

		$this->title = (string) $title;
	}


}