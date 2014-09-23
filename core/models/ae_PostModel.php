<?php

class ae_PostModel extends ae_PageModel {


	const TABLE = AE_TABLE_POSTS;
	const TABLE_ID_FIELD = 'po_id';

	const TAG_DELIMITER = ';';

	protected $categories = array();
	protected $tags = '';


	/**
	 * Constructor.
	 * @param {array} $data Post data to initialize the model with. (Optional.)
	 */
	public function __construct( $data = array() ) {
		$this->loadFromData( $data );
	}


	/**
	 * Delete the loaded post and its relations with categories.
	 * @return {boolean} FALSE, if deletion failed,
	 *                   TRUE otherwise (including the case that the model doesn't exist).
	 */
	public function delete() {
		if( !parent::delete() ) {
			return FALSE;
		}

		$stmt = '
			DELETE FROM `' . AE_TABLE_POSTS2CATEGORIES . '`
			WHERE pc_post = :id
		';
		$params = array(
			':id' => $model->getId()
		);

		return ( ae_Database::query( $stmt, $params ) !== FALSE );
	}


	/**
	 * Get post categories.
	 * @return {array} Post categories.
	 */
	public function getCategories() {
		return $this->categories;
	}


	/**
	 * Get post tags.
	 * @return {array} Post tags.
	 */
	public function getTags() {
		return explode( self::TAG_DELIMITER, $this->tags );
	}


	/**
	 * Get post tags.
	 * @return {string} Post tags.
	 */
	public function getTagsString() {
		return $this->tags;
	}


	/**
	 * Load a post with the given ID.
	 * @param  {int}     $id ID of the post to load.
	 * @return {boolean}     TRUE, if loading succeeded, FALSE otherwise.
	 */
	public function load( $id ) {
		$modelData = $this->loadModelData( $id );

		if( $modelData === FALSE ) {
			return FALSE;
		}

		$this->loadFromData( $modelData );

		if( !$this->loadCategories() ) {
			return FALSE;
		}

		return TRUE;
	}


	/**
	 * Load post categories.
	 * @return {boolean} TRUE, if loading succeeded, FALSE otherwise.
	 */
	protected function loadCategories() {
		if( !ae_Validate::id( $this->id ) ) {
			throw new Exception( '[' . get_class() . '] Cannot load post categories. No valid post ID.' );
		}

		$stmt = '
			SELECT pc_category
			FROM `' . AE_TABLE_POSTS2CATEGORIES . '`
			WHERE pc_post = :id
		';
		$params = array(
			':id' => $this->id
		);
		$result = ae_Database::query( $stmt, $params );

		if( $result === FALSE ) {
			return FALSE;
		}

		$categories = array();

		foreach( $result as $row ) {
			$categories[] = $row['pc_category'];
		}

		$this->setCategories( $categories );

		return TRUE;
	}


	/**
	 * Initialize model from the given data array.
	 * @param {array} $data The model data.
	 */
	protected function loadFromData( $data ) {
		if( isset( $data['po_id'] ) ) {
			$this->setId( $data['po_id'] );
		}
		if( isset( $data['categories'] ) ) {
			$this->setCategories( $data['categories'] );
		}
		if( isset( $data['po_comments'] ) ) {
			$this->setCommentsStatus( $data['po_comments'] );
		}
		if( isset( $data['po_content'] ) ) {
			$this->setContent( $data['po_content'] );
		}
		if( isset( $data['po_datetime'] ) ) {
			$this->setDatetime( $data['po_datetime'] );
		}
		if( isset( $data['po_permalink'] ) ) {
			$this->setPermalink( $data['po_permalink'] );
		}
		if( isset( $data['po_status'] ) ) {
			$this->setStatus( $data['po_status'] );
		}
		if( isset( $data['po_tags'] ) ) {
			$this->setTags( $data['po_tags'] );
		}
		if( isset( $data['po_title'] ) ) {
			$this->setTitle( $data['po_title'] );
		}
		if( isset( $data['po_user'] ) ) {
			$this->setUserId( $data['po_user'] );
		}
	}


	/**
	 * Save the post to DB. If an ID is set, it will update
	 * the post, otherwise it will create a new one.
	 * @param  {boolean}   $forceInsert If set to TRUE and an ID has been set, the model will be saved
	 *                                  as new entry instead of updating. (Optional, default is FALSE.)
	 * @return {boolean}                TRUE, if saving is successful, FALSE otherwise.
	 * @throws {Exception}              If $forceInsert is TRUE, but no valid ID is set.
	 */
	public function save( $forceInsert = FALSE ) {
		if( $this->datetime == '0000-00-00 00:00:00' ) {
			$this->setDatetime( date( 'Y-m-d H:i:s' ) );
		}
		if( !ae_Validate::id( $this->userId ) ) {
			$this->setUserId( ae_Security::getCurrentUserId() );
		}
		if( $this->permalink == '' ) {
			$this->setPermalink( $this->title );
		}

		$params = array(
			':title' => $this->title,
			':permalink' => $this->permalink,
			':content' => $this->content,
			':datetime' => $this->datetime,
			':tags' => $this->tags,
			':user' => $this->userId,
			':comments' => $this->commentsStatus,
			':status' => $this->status
		);

		// Create new post
		if( $this->id === FALSE && !$forceInsert ) {
			$stmt = '
				INSERT INTO `' . AE_TABLE_POSTS . '` (
					po_title,
					po_permalink,
					po_content,
					po_datetime,
					po_tags,
					po_user,
					po_comments,
					po_status
				) VALUES (
					:title,
					:permalink,
					:content,
					:datetime,
					:tags,
					:user,
					:comments,
					:status
				)
			';
		}
		// Create new post with set ID
		else if( $this->id !== FALSE && $forceInsert ) {
			$stmt = '
				INSERT INTO `' . AE_TABLE_POSTS . '` (
					po_id,
					po_title,
					po_permalink,
					po_content,
					po_datetime,
					po_tags,
					po_user,
					po_comments,
					po_status
				) VALUES (
					:id,
					:title,
					:permalink,
					:content,
					:datetime,
					:tags,
					:user,
					:comments,
					:status
				)
			';
			$params[':id'] = $this->id;
		}
		// Update existing one
		else if( $this->id !== FALSE ) {
			$stmt = '
				UPDATE `' . AE_TABLE_POSTS . '` SET
					po_title = :title,
					po_permalink = :permalink,
					po_content = :content,
					po_datetime = :datetime,
					po_tags = :tags,
					po_user = :user,
					po_comments = :comments,
					po_status = :status
				WHERE
					po_id = :id
			';
			$params[':id'] = $this->id;
		}
		else {
			$msg = sprintf( '[%s] Supposed to insert new post with set ID, but no ID has been set.', get_class() );
			throw new Exception( $msg );
		}

		if( ae_Database::query( $stmt, $params ) === FALSE ) {
			return FALSE;
		}

		// If a new post was created, get the new ID
		if( $this->id === FALSE ) {
			$stmt = 'SELECT DISTINCT LAST_INSERT_ID() as id FROM `' . AE_TABLE_POSTS . '`';
			$result = ae_Database::query( $stmt );

			if( $result === FALSE ) {
				return FALSE;
			}

			$this->setId( $result[0]['id'] );
		}

		return TRUE;
	}


	/**
	 * Set post categories. Validates if category IDs are a valid format,
	 * but not if the category with the ID exists.
	 * @param  {array}     $categories Post categories.
	 * @throws {Exception}             If $categories is not an array or contains a non-valid ID.
	 */
	public function setCategories( $categories ) {
		if( !is_array( $categories ) ) {
			$msg = sprintf( '[%s] Requires categories to be passed as array.', get_class() );
			throw new Exception( $msg );
		}

		foreach( $categories as $caId ) {
			if( !ae_Validate::id( $caId ) ) {
				$msg = sprintf( '[%s] Not a valid category ID: %d', get_class(), $caId );
				throw new Exception( $msg );
			}
		}

		$this->categories = $categories;
	}


	/**
	 * Set the post tags.
	 * @param {string|array} $tags Tags of this post.
	 */
	public function setTags( $tags ) {
		if( !is_array( $tags ) ) {
			$tags = explode( self::TAG_DELIMITER, $tags );
		}

		$tagsCleaned = array();

		foreach( $tags as $tag ) {
			$tag = trim( $tag );

			if( $tag !== '' ) {
				$tagsCleaned[] = $tag;
			}
		}

		$tagsCleaned = implode( self::TAG_DELIMITER, $tagsCleaned );
		$this->tags = $tagsCleaned;
	}


}
