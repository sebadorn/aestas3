<?php

class ae_PostList extends ae_List {


	const ITEM_CLASS = 'ae_PostModel';

	static protected $defaultFilter = array(
		'LIMIT' => '0, 20',
		'ORDER BY' => 'po_datetime DESC'
	);


	/**
	 * Constructor.
	 * Fetches all posts from the DB.
	 * @param {array}   $filter     Array of filters to apply to the MySQL statement. (Optional.)
	 * @param {boolean} $countItems If TRUE, extends the DB query to count the number of items. (Optional, defaults to TRUE.)
	 */
	public function __construct( $filter = array(), $params = array(), $countItems = TRUE ) {
		parent::__construct( self::ITEM_CLASS, $filter, $params, self::$defaultFilter, $countItems );
	}


	/**
	 * Assign the loaded category models to their post models.
	 * @param {array} $categories Category models.
	 */
	protected function assignCategoriesToPosts( $categories ) {
		$id2Post = array();

		foreach( $this->items as $item ) {
			$id2Post[$item->getId()] = $item;
		}

		foreach( $categories as $row ) {
			$ca = new ae_CategoryModel();
			$ca->setId( $row['ca_id'] );
			$ca->setTitle( $row['ca_title'] );
			$ca->setPermalink( $row['ca_permalink'] );
			$ca->setParent( $row['ca_parent'] );
			$ca->setStatus( ae_CategoryModel::STATUS_AVAILABLE );

			$id2Post[$row['pc_post']]->addCategory( $ca );
		}
	}


	/**
	 * Assign the loaded number of comments to their post models.
	 * @param {array} $numComments Number of comments of each loaded post.
	 */
	protected function assignNumCommentsToPosts( $numComments ) {
		$id2Num = array();

		foreach( $numComments as $row ) {
			$id2Num[$row['co_post']] = $row['numComments'];
		}

		foreach( $this->items as $key => $item ) {
			$num = isset( $id2Num[$item->getId()] ) ? $id2Num[$item->getId()] : 0;
			$this->items[$key]->setNumComments( $num );
		}
	}


	/**
	 * Get the post IDs as comma separated string.
	 * @return {string|boolean} Post IDs or FALSE if there are no post IDs.
	 */
	protected function getPostIdsString() {
		$postIds = '';

		foreach( $this->items as $item ) {
			$postIds .= $item->getId() . ',';
		}

		if( $postIds == '' ) {
			return FALSE;
		}

		return mb_substr( $postIds, 0, -1 );
	}


	/**
	 * Load category models for the loaded post models.
	 */
	public function loadCategories() {
		$postIds = $this->getPostIdsString();

		if( !$postIds ) {
			return FALSE;
		}

		$stmt = '
			SELECT pc_post, ca_id, ca_title, ca_parent, ca_permalink
			FROM (
				SELECT * FROM `' . AE_TABLE_POSTS2CATEGORIES . '`
				WHERE pc_post IN ( ' . $postIds . ' )
			) AS `' . AE_TABLE_POSTS2CATEGORIES. '`
			LEFT JOIN `' . AE_TABLE_CATEGORIES . '`
			ON pc_category = ca_id
			WHERE ca_status = :caStatus
		';
		$params = array(
			':caStatus' => ae_CategoryModel::STATUS_AVAILABLE
		);

		$result = ae_Database::query( $stmt, $params );

		if( $result === FALSE ) {
			return FALSE;
		}

		$this->assignCategoriesToPosts( $result );
		$this->reset();

		return TRUE;
	}


	/**
	 * Load number of comments for loaded post models.
	 * @param {string} $status Status of comments to select.
	 */
	public function loadNumComments( $status = ae_CommentModel::STATUS_APPROVED ) {
		$postIds = $this->getPostIdsString();

		if( !$postIds ) {
			return FALSE;
		}

		$stmt = '
			SELECT co_post, COUNT( co_id ) AS numComments
			FROM `' . AE_TABLE_COMMENTS . '`
			WHERE co_post IN ( ' . $postIds . ' )
			AND co_status = :status
			GROUP BY co_post
		';
		$params = array(
			':status' => $status
		);

		$result = ae_Database::query( $stmt, $params );

		if( $result === FALSE ) {
			return FALSE;
		}

		$this->assignNumCommentsToPosts( $result );
		$this->reset();

		return TRUE;
	}


}
