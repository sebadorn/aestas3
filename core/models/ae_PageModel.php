<?php

class ae_PageModel extends ae_Model {


	const COMMENTS_CLOSED = 'closed';
	const COMMENTS_DISABLED = 'disabled';
	const COMMENTS_OPEN = 'open';

	const STATUS_DRAFT = 'draft';
	const STATUS_PUBLISHED = 'published';
	const STATUS_TRASH = 'trash';

	protected $id = FALSE;
	protected $commentsStatus = self::COMMENTS_OPEN;
	protected $content = '';
	protected $datetime = '0000-00-00 00:00:00';
	protected $permalink = '';
	protected $status = self::STATUS_DRAFT;
	protected $title = '';
	protected $userId = 0;


	/**
	 * Constructor.
	 * @param {array} $data Page data to initialize the model with. (Optional.)
	 */
	public function __construct( $data = array() ) {
		$this->loadFromData( $data );
	}


	/**
	 * Get page ID.
	 * @return {int} Page ID.
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * Get page comments status.
	 * @return {string} Page comments status.
	 */
	public function getCommentsStatus() {
		return $this->commentsStatus;
	}


	/**
	 * Get page content.
	 * @return {string} Page content.
	 */
	public function getContent() {
		return $this->content;
	}


	/**
	 * Get page datetime (time it was published).
	 * @return {string} Page datetime.
	 */
	public function getDatetime() {
		return $this->datetime;
	}


	/**
	 * Get page permalink.
	 * @return {string} Page permalink.
	 */
	public function getPermalink() {
		return $this->permalink;
	}


	/**
	 * Get page status.
	 * @return {string} Page status.
	 */
	public function getStatus() {
		return $this->status;
	}


	/**
	 * Get page title.
	 * @return {string} Page title.
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * Get page user ID.
	 * @return {int} Page user ID.
	 */
	public function getUserId() {
		return $this->userId;
	}


	/**
	 * Load a page with the given ID.
	 * @param  {int}     $id ID of the page to load.
	 * @return {boolean}     TRUE, if loading succeeded, FALSE otherwise.
	 */
	public function load( $id ) {
		$this->setId( $id );

		$stmt = '
			SELECT *
			FROM `' . AE_TABLE_PAGES . '`
			WHERE pa_id = :id
		';
		$params = array(
			':id' => $id
		);
		$result = ae_Database::query( $stmt, $params );

		if( $result === FALSE ) {
			return FALSE;
		}

		$this->loadFromData( $result[0] );

		return TRUE;
	}


	/**
	 * Initialize model from the given data array.
	 * @param {array} $data The model data.
	 */
	protected function loadFromData( $data ) {
		if( isset( $data['pa_id'] ) ) {
			$this->setId( $data['pa_id'] );
		}
		if( isset( $data['pa_comments'] ) ) {
			$this->setCommentsStatus( $data['pa_comments'] );
		}
		if( isset( $data['pa_content'] ) ) {
			$this->setContent( $data['pa_content'] );
		}
		if( isset( $data['pa_datetime'] ) ) {
			$this->setDatetime( $data['pa_datetime'] );
		}
		if( isset( $data['pa_title'] ) ) {
			$this->setTitle( $data['pa_title'] );
		}
		if( isset( $data['pa_permalink'] ) ) {
			$this->setPermalink( $data['pa_permalink'] );
		}
		if( isset( $data['pa_status'] ) ) {
			$this->setStatus( $data['pa_status'] );
		}
		if( isset( $data['pa_user'] ) ) {
			$this->setUserId( $data['pa_user'] );
		}
	}


	/**
	 * Save the page to DB. If an ID is set, it will update
	 * the page, otherwise it will create a new one.
	 * @return {boolean} TRUE, if saving is successful, FALSE otherwise.
	 */
	public function save() {
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
			':user' => $this->userId,
			':comments' => $this->commentsStatus,
			':status' => $this->status
		);

		// Create new page
		if( $this->id === FALSE ) {
			$stmt = '
				INSERT INTO `' . AE_TABLE_PAGES . '` (
					pa_title,
					pa_permalink,
					pa_content,
					pa_datetime,
					pa_user,
					pa_comments,
					pa_status
				) VALUES (
					:title,
					:permalink,
					:content,
					:datetime,
					:user,
					:comments,
					:status
				)
			';
		}
		// Update existing one
		else {
			$stmt = '
				UPDATE `' . AE_TABLE_PAGES . '` SET
					pa_title = :title,
					pa_permalink = :permalink,
					pa_content = :content,
					pa_datetime = :datetime,
					pa_user = :user,
					pa_comments = :comments,
					pa_status = :status
				WHERE
					pa_id = :id
			';
			$params[':id'] = $this->id;
		}

		if( ae_Database::query( $stmt, $params ) === FALSE ) {
			return FALSE;
		}

		// If a new page was created, get the new ID
		if( $this->id === FALSE ) {
			$stmt = 'SELECT DISTINCT LAST_INSERT_ID() as pa_id FROM `' . AE_TABLE_PAGES . '`';
			$result = ae_Database::query( $stmt );

			if( $result === FALSE ) {
				return FALSE;
			}

			$this->setId( $result[0]['pa_id'] );
		}

		return TRUE;
	}


	/**
	 * Set the page ID.
	 * @param  {int}       $id Page ID.
	 * @throws {Exception}     If $id is not valid.
	 */
	public function setId( $id ) {
		if( !ae_Validate::id( $id ) ) {
			throw new Exception( '[' . get_class() . '] Not a valid ID: ' . htmlspecialchars( $id ) );
		}

		$this->id = $id;
	}


	/**
	 * Set post comments status.
	 * @param  {string}    $commentsStatus Page comments status.
	 * @throws {Exception}                 If $commentsStatus is not a valid page comments status.
	 */
	public function setCommentsStatus( $commentsStatus ) {
		$validStatuses = array( self::COMMENTS_CLOSED, self::COMMENTS_DISABLED, self::COMMENTS_OPEN );

		if( !in_array( $commentsStatus, $validStatuses ) ) {
			$msg = sprintf( '[%s] Not a valid comments status: %d', get_class(), $commentsStatus );
			throw new Exception( $msg );
		}

		$this->commentsStatus = $commentsStatus;
	}


	/**
	 * Set page content.
	 * @param {string} $content Post content.
	 */
	public function setContent( $content ) {
		$this->content = $content;
	}


	/**
	 * Set page datetime (time it was published).
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
	 * Set page status.
	 * @param  {string}    $status Page status.
	 * @throws {Exception}         If $status is not a valid page status.
	 */
	public function setStatus( $status ) {
		$validStatuses = array( self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_TRASH );

		if( !in_array( $status, $validStatuses ) ) {
			$msg = sprintf( '[%s] Not a valid status: %s', get_class(), $status );
			throw new Exception( $msg );
		}

		$this->status = $status;
	}


	/**
	 * Set the page title.
	 * @param {string} $title Page title.
	 */
	public function setTitle( $title ) {
		$this->title = $title;
	}


	/**
	 * Set the page user ID. Validates if the user ID is a valid format,
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