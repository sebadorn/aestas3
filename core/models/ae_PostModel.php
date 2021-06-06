<?php

class ae_PostModel extends ae_PageModel {


	const CONTENT_DIVIDER = '<!--more-->';
	const TABLE = AE_TABLE_POSTS;
	const TABLE_ID_FIELD = 'po_id';
	const TAG_DELIMITER = ';';

	protected $categories = array();
	protected $categoryIds = array();
	protected $numComments = FALSE;
	protected $tags = '';


	/**
	 * Constructor.
	 * @param {array} $data Post data to initialize the model with. (Optional.)
	 */
	public function __construct( $data = array() ) {
		$this->loadFromData( $data );
	}


	/**
	 * Add a category model to the list of categories.
	 * @param {ae_CategoryModel} $category Category model.
	 */
	public function addCategory( ae_CategoryModel $category ) {
		$this->categories[] = $category;
	}


	/**
	 * Delete the loaded post and its relations with categories and social.
	 * @return {boolean} FALSE, if deletion failed,
	 *                   TRUE otherwise (including the case that the model doesn't exist).
	 */
	public function delete() {
		if( !parent::delete() ) {
			return FALSE;
		}

		$successCategories = TRUE;
		$successSocial = TRUE;

		$stmt = '
			DELETE FROM `' . AE_TABLE_POSTS2CATEGORIES . '`
			WHERE pc_post = :id
		';
		$params = array(
			':id' => $this->getId()
		);
		$successCategories = ae_Database::query( $stmt, $params ) !== FALSE;

		if( $this->getSocialId() !== NULL ) {
			$stmt = '
				DELETE FROM `' . AE_TABLE_SOCIAL . '`
				WHERE soc_id = :id
			';
			$params = array(
				':id' => $this->getSocialId()
			);
			$successSocial = ae_Database::query( $stmt, $params ) !== FALSE;
		}

		return $successCategories && $successSocial;
	}


	/**
	 * Get post categories.
	 * @return {array} Post categories (models).
	 */
	public function getCategories() {
		return $this->categories;
	}


	/**
	 * Get post category IDs.
	 * @return {array} Post category IDs.
	 */
	public function getCategoryIds() {
		return $this->categoryIds;
	}


	/**
	 * Get only the first part of the content until the "<!--more-->" divider.
	 * @return {string} First part of the content.
	 */
	public function getContentSnippet() {
		$content = explode( self::CONTENT_DIVIDER, $this->getContent(), 2 );

		return $content[0];
	}


	/**
	 * Get complete permalink for the post (not including the domain and directory).
	 * @param  {string} $urlBase URL base of the link. (Optional, defaults to constant "URL".)
	 * @return {string}          Complete permalink.
	 */
	public function getLink( $urlBase = URL ) {
		if( ae_Settings::isModRewriteEnabled() ) {
			$link= $urlBase . PERMALINK_BASE_POST . $this->getDatetime( 'Y/m/d/' ) . $this->getPermalink();
		}
		else {
			$urlBase .= ( $urlBase[mb_strlen( $urlBase ) - 1] == '?' ) ? '&amp;' : '?';
			$link = $urlBase . PERMALINK_GET_POST . '=' . $this->getId();
		}

		return $link;
	}


	/**
	 * Get the number of comments.
	 * @return {int|boolean} Number of comments or FALSE if not loaded.
	 */
	public function getNumComments() {
		return $this->numComments;
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
	 * Check, if there is a snippet of the content.
	 * @return {boolean} TRUE, if snippet exists, FALSE otherwise.
	 */
	public function hasSnippet() {
		return ( mb_strstr( $this->getContent(), self::CONTENT_DIVIDER ) !== FALSE );
	}


	/**
	 * Load a post with the given ID.
	 * @param  {int}     $id             ID of the post to load.
	 * @param  {boolean} $loadCategories Also load the associated categories from the DB.
	 * @return {boolean}                 TRUE, if loading succeeded, FALSE otherwise.
	 */
	public function load( $id, $loadCategories = FALSE ) {
		$modelData = $this->loadModelData( $id );

		if( $modelData === FALSE ) {
			return FALSE;
		}

		$this->loadFromData( $modelData );

		if( $loadCategories ) {
			return $this->loadCategories();
		}

		return TRUE;
	}


	/**
	 * Load post category models.
	 * @return {boolean} TRUE, if loading succeeded, FALSE otherwise.
	 */
	public function loadCategories() {
		if( !ae_Validate::id( $this->id ) ) {
			throw new Exception( '[' . get_class() . '] Cannot load post categories. No valid post ID.' );
		}

		$stmt = '
			SELECT ca_id, ca_title, ca_parent, ca_permalink
			FROM (
				SELECT * FROM `' . AE_TABLE_POSTS2CATEGORIES . '`
				WHERE pc_post = :id
			) AS `' . AE_TABLE_POSTS2CATEGORIES. '`
			LEFT JOIN `' . AE_TABLE_CATEGORIES . '`
			ON pc_category = ca_id
			WHERE ca_status = :caStatus
		';
		$params = array(
			':id' => $this->id,
			':caStatus' => ae_CategoryModel::STATUS_AVAILABLE
		);

		$result = ae_Database::query( $stmt, $params );

		if( $result === FALSE ) {
			return FALSE;
		}

		foreach( $result as $row ) {
			$ca = new ae_CategoryModel( $row );
			$ca->setStatus( ae_CategoryModel::STATUS_AVAILABLE );

			$this->addCategory( $ca );
		}

		return TRUE;
	}


	/**
	 * Load post category IDs.
	 * @return {boolean} TRUE, if loading succeeded, FALSE otherwise.
	 */
	public function loadCategoryIds() {
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

		$this->setCategoryIds( $categories );

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
			$this->setCategoryIds( $data['categories'] );
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
		if( isset( $data['po_desc'] ) ) {
			$this->setDescription( $data['po_desc'] );
		}
		if( isset( $data['po_edit'] ) && $data['po_edit'] != NULL ) {
			$this->setEditDatetime( $data['po_edit'] );
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
		if( isset( $data['po_social'] ) ) {
			$this->setSocialId( $data['po_social'] );
		}
	}


	/**
	 * Load model data from DB identified by the given permalink.
	 * @param {string} $permalink Permalink to identify the post by.
	 */
	public function loadFromPermalink( $permalink ) {
		$parts = explode( '/', $permalink );
		$pl = $parts[3];
		array_pop( $parts );
		$date = implode( '-', $parts ) . ' %';

		$stmt = '
			SELECT * FROM `' . self::TABLE . '`
			WHERE
				po_permalink = :permalink AND
				po_datetime LIKE :date
		';
		$params = array(
			':permalink' => $pl,
			':date' => $date
		);

		$result = ae_Database::query( $stmt, $params );

		if( $result === FALSE || count( $result ) < 1 ) {
			return FALSE;
		}

		$this->loadFromData( $result[0] );

		return TRUE;
	}



	/**
	 * Save the post to DB. If an ID is set, it will update
	 * the post, otherwise it will create a new one.
	 * Will not save/update post-category relations!
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
			':desc' => $this->desc,
			':datetime' => $this->datetime,
			':tags' => $this->tags,
			':user' => $this->userId,
			':social' => $this->socialId,
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
					po_desc,
					po_datetime,
					po_tags,
					po_user,
					po_social,
					po_comments,
					po_status
				) VALUES (
					:title,
					:permalink,
					:content,
					:desc,
					:datetime,
					:tags,
					:user,
					:social,
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
					po_desc,
					po_datetime,
					po_tags,
					po_user,
					po_social,
					po_comments,
					po_status
				) VALUES (
					:id,
					:title,
					:permalink,
					:content,
					:desc,
					:datetime,
					:tags,
					:user,
					:social,
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
					po_desc = :desc,
					po_datetime = :datetime,
					po_edit = :editDatetime,
					po_tags = :tags,
					po_user = :user,
					po_social = :social,
					po_comments = :comments,
					po_status = :status
				WHERE
					po_id = :id
			';
			$params[':id'] = $this->id;
			$params[':editDatetime'] = date( 'Y-m-d H:i:s' );
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
			$this->setId( $this->getLastInsertedId() );
			$this->createSocial();
		}
		else {
			$this->updateSocial();
		}

		return TRUE;
	}


	/**
	 * Set post categories. Validates if category IDs are a valid format,
	 * but not if the category with the ID exists.
	 * @param  {array}     $categories Post categories.
	 * @throws {Exception}             If $categories is not an array or contains a non-valid ID.
	 */
	public function setCategoryIds( $categories ) {
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

		$this->categoryIds = $categories;
	}


	/**
	 * Set the number of comments.
	 * @param {int} $numComments Number of comments.
	 */
	public function setNumComments( $numComments ) {
		if( !ae_Validate::integer( $numComments ) || $numComments < 0 ) {
			$msg = sprintf( '[%s] Not a number: %s', get_class(), htmlspecialchars( $numComments ) );
			throw new Exception( $msg );
		}

		$this->numComments = (int) $numComments;
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
