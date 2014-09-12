<?php

class ae_PostModel extends ae_Model {


	const COMMENTS_CLOSED = 'closed';
	const COMMENTS_DISABLED = 'disabled';
	const COMMENTS_OPEN = 'open';

	const STATUS_DRAFT = 'draft';
	const STATUS_PUBLISHED = 'published';
	const STATUS_TRASH = 'trash';

	const TAG_DELIMITER = ';';

	protected $id = FALSE;
	protected $categories = array();
	protected $commentsStatus = self::COMMENTS_OPEN;
	protected $content = '';
	protected $datetime = '0000-00-00 00:00:00';
	protected $permalink = '';
	protected $status = self::STATUS_DRAFT;
	protected $tags = '';
	protected $title = '';
	protected $userId = 0;


	/**
	 * Constructor.
	 * @param {array} $data Post data to initialize the model with. (Optional.)
	 */
	public function __construct( $data = array() ) {
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
	 * Get post ID.
	 * @return {int} Post ID.
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * Get post categories.
	 * @return {array} Post categories.
	 */
	public function getCategories() {
		return $this->categories;
	}


	/**
	 * Get post comments status.
	 * @return {string} Post comments status.
	 */
	public function getCommentsStatus() {
		return $this->commentsStatus;
	}


	/**
	 * Get post content.
	 * @return {string} Post content.
	 */
	public function getContent() {
		return $this->content;
	}


	/**
	 * Get post datetime (time it was published).
	 * @return {string} Post datetime.
	 */
	public function getDatetime() {
		return $this->datetime;
	}


	/**
	 * Get post permalink.
	 * @return {string} Post permalink.
	 */
	public function getPermalink() {
		return $this->permalink;
	}


	/**
	 * Get post status.
	 * @return {string} Post status.
	 */
	public function getStatus() {
		return $this->status;
	}


	/**
	 * Get post tags.
	 * @return {array} Post tags.
	 */
	public function getTags() {
		return explode( self::TAG_DELIMITER, $this->tags );
	}


	/**
	 * Get post title.
	 * @return {string} Post title.
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * Get post user ID.
	 * @return {int} Post user ID.
	 */
	public function getUserId() {
		return $this->user;
	}


	/**
	 * Set the post ID.
	 * @param  {int}       $id Post ID.
	 * @throws {Exception}     If $id is not valid.
	 */
	public function setId( $id ) {
		if( !ae_Validate::id( $id ) ) {
			throw new Exception( '[' . get_class() . '] Not a valid ID: ' . htmlspecialchars( $id ) );
		}

		$this->id = $id;
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
	 * Set post comments status.
	 * @param  {string}    $commentsStatus Post comments status.
	 * @throws {Exception}                 If $commentsStatus is not a valid post comments status.
	 */
	public function setCommentsStatus( $commentsStatus ) {
		$validStatuses = array( self::COMMENTS_CLOSED, self::COMMENTS_DISABLED, self::COMMENTS_OPEN );

		if( !in_array( $commentStatus, $validStatuses ) ) {
			$msg = sprintf( '[%s] Not a valid comments status: %d', get_class(), $commentsStatus );
			throw new Exception( $msg );
		}

		$this->commentsStatus = $commentsStatus;
	}


	/**
	 * Set post content.
	 * @param {string} $content Post content.
	 */
	public function setContent( $content ) {
		$this->content = $content;
	}


	/**
	 * Set post datetime (time it was published).
	 * @param  {string}    $datetime The datetime.
	 * @throws {Exception}           If $datetime is not a valid format.
	 */
	public function setDatetime( $datetime ) {
		if( !ae_Validate::datetime( $datetime ) ) {
			$msg = sprintf( '[%s] Not a valid datetime: %s', get_class(), $datetime );
			throw new Exception( $msg );
		}

		$this->datetime = $datetime;
	}


	/**
	 * Set post permalink.
	 * @param  {string} $permalink Post permalink
	 * @return {string}            The actually used permalink.
	 */
	public function setPermalink( $permalink ) {
		$this->permalink = self::generatePermalink( $permalink );

		return $this->permalink;
	}


	/**
	 * Set post status.
	 * @param  {string}    $status Post status.
	 * @throws {Exception}         If $status is not a valid post status.
	 */
	public function setStatus( $status ) {
		$validStatuses = array( self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_TRASH );

		if( !in_array( $status, $validStatuses ) ) {
			$msg = sprintf( '[%s] Not a valid post status: %s', get_class(), $status );
			throw new Exception( $msg );
		}

		$this->status = $status;
	}


	/**
	 * Set the post tags.
	 * @param {string|array} $tags Tags of this post.
	 */
	public function setTags( $tags ) {
		if( is_array( $tags ) ) {
			$tags = implode( self::TAG_DELIMITER, $tags );
		}

		$this->tags = $tags;
	}


	/**
	 * Set the post title.
	 * @param {string} $title Post title.
	 */
	public function setTitle( $title ) {
		$this->title = $title;
	}


	/**
	 * Set the post user ID. Validates if the user ID is a valid format,
	 * but not if the user exists.
	 * @param  {int}       $userId User ID.
	 * @throws {Exception}         If $userId is not a valid format.
	 */
	public function setUserId( $userId ) {
		if( !ae_Validate::id( $userId ) ) {
			$msg = sprintf( '[%s] Not a valid user ID: %s', get_class(), $userId );
			throw new Exception( $msg );
		}

		$this->userId = $userId;
	}


}
