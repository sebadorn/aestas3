<?php

class ae_CommentModel extends ae_Model {


	const STATUS_APPROVED = 'approved';
	const STATUS_SPAM = 'spam';
	const STATUS_TRASH = 'trash';
	const STATUS_UNAPPROVED = 'unapproved';

	const TABLE = AE_TABLE_COMMENTS;
	const TABLE_ID_FIELD = 'co_id';

	protected $authorEmail = '';
	protected $authorName = 'Anonymous';
	protected $authorUrl = '';
	protected $content = '';
	protected $datetime = '0000-00-00 00:00:00';
	protected $postId = FALSE;
	protected $status = self::STATUS_UNAPPROVED;


	/**
	 * Constructor.
	 * @param {array} $data Data to initialize the model with. (Optional.)
	 */
	public function __construct( $data = array() ) {
		$this->loadFromData( $data );
	}


	/**
	 * Get comment author email.
	 * @return {string} Comment author email.
	 */
	public function getAuthorEmail() {
		return $this->authorEmail;
	}


	/**
	 * Get comment author name.
	 * @return {string} Comment author name.
	 */
	public function getAuthorName() {
		return $this->authorName;
	}


	/**
	 * Get comment author URL.
	 * @return {string} Comment author URL.
	 */
	public function getAuthorUrl() {
		return $this->authorUrl;
	}


	/**
	 * Get comment content.
	 * @return {string} Comment content.
	 */
	public function getContent() {
		return $this->content;
	}


	/**
	 * Get comment datetime.
	 * @return {string} Comment datetime.
	 */
	public function getDatetime() {
		return $this->datetime;
	}


	/**
	 * Get comment post ID.
	 * @return {int} Comment post ID.
	 */
	public function getPostId() {
		return $this->postId;
	}


	/**
	 * Get comment status.
	 * @return {string} Comment status.
	 */
	public function getStatus() {
		return $this->status;
	}


	/**
	 * Initialize model from the given data.
	 * @param {array} $data The model data.
	 */
	protected function loadFromData( $data ) {
		if( isset( $data['co_id'] ) ) {
			$this->setId( $data['co_id'] );
		}
		if( isset( $data['co_post'] ) ) {
			$this->setPostId( $data['co_post'] );
		}
		if( isset( $data['co_name'] ) ) {
			$this->setAuthorName( $data['co_name'] );
		}
		if( isset( $data['co_email'] ) ) {
			$this->setAuthorEmail( $data['co_email'] );
		}
		if( isset( $data['co_url'] ) ) {
			$this->setAuthorUrl( $data['co_url'] );
		}
		if( isset( $data['co_datetime'] ) ) {
			$this->setDatetime( $data['co_datetime'] );
		}
		if( isset( $data['co_content'] ) ) {
			$this->setContent( $data['co_content'] );
		}
		if( isset( $data['co_status'] ) ) {
			$this->setStatus( $data['co_status'] );
		}
	}


	/**
	 * Save the comment to DB. If an ID is set, it will update
	 * the comment, otherwise it will create a new one.
	 * @throws {Exception} If no post ID is given.
	 * @return {boolean}   TRUE, if saving is successful, FALSE otherwise.
	 */
	public function save() {
		if( $this->postId === FALSE ) {
			throw new Exception( '[' . get_class() . '] Cannot save comment. No post ID.' );
		}


		$params = array(
			':postId' => $this->postId,
			':authorName' => $this->authorName,
			':authorEmail' => $this->authorEmail,
			':authorUrl' => $this->authorUrl,
			':datetime' => $this->datetime,
			':content' => $this->content,
			':status' => $this->status
		);

		// Create new comment
		if( $this->id === FALSE ) {
			$stmt = '
				INSERT INTO `' . AE_TABLE_COMMENTS . '` (
					co_post,
					co_name,
					co_email,
					co_url,
					co_datetime,
					co_content,
					co_status
				)
				VALUES (
					:postId,
					:authorName,
					:authorEmail,
					:authorUrl,
					:datetime,
					:content,
					:status
				)
			';
		}
		// Update existing one
		else {
			$stmt = '
				UPDATE `' . AE_TABLE_COMMENTS . '` SET
					co_post = :postId,
					co_name = :authorName,
					co_email = :authorEmail,
					co_url = :authorUrl,
					co_datetime = :datetime,
					co_content = :content,
					co_status = :status
				WHERE
					co_id = :id
			';
			$params[':id'] = $this->id;
		}

		if( ae_Database::query( $stmt, $params ) === FALSE ) {
			return FALSE;
		}

		// If a new comment was created, get the new ID
		if( $this->id === FALSE ) {
			$stmt = 'SELECT DISTINCT LAST_INSERT_ID() as id FROM `' . AE_TABLE_COMMENTS . '`';
			$result = ae_Database::query( $stmt );

			if( $result === FALSE ) {
				return FALSE;
			}

			$this->setId( $result[0]['id'] );
		}

		return TRUE;
	}


	/**
	 * Set the author email.
	 * @param  {string}    $email The author email. Can either be empty or a valid email.
	 * @throws {Exception}        If neither empty nor valid.
	 */
	public function setAuthorEmail( $email ) {
		if( $email !== '' && !ae_Validate::email( $email ) ) {
			throw new Exception( '[' . get_class() . '] Not a valid email.' );
		}

		$this->authorEmail = $email;
	}


	/**
	 * Set comment author name.
	 * @param {string} $name The author name. If empty it will be set to a default name.
	 */
	public function setAuthorName( $name ) {
		$name = trim( $name );

		if( strlen( $name ) == 0 ) {
			$name = 'Anonymous';
		}

		$this->authorName = $name;
	}


	/**
	 * Set comment author URL.
	 * @param  {string}    $url The URL. Either a valid URL or an empty string.
	 * @throws {Exception}      If $url is neither empty nor valid.
	 */
	public function setAuthorUrl( $url ) {
		$url = trim( $url );

		if( !ae_Validate::url( $url ) ) {
			$msg = sprintf( '[%s] Not a valid URL: %s', get_class(), htmlspecialchars( $url ) );
			throw new Exception( $msg );
		}

		$this->url = $url;
	}


	/**
	 * Set comment content.
	 * @param {string} $content The content.
	 */
	public function setContent( $content ) {
		$this->content = trim( $content );
	}


	/**
	 * Set comment datetime (time it was submitted).
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
	 * Set the comment post ID.
	 * @param  {int}       $postId New comment post ID.
	 * @throws {Exception}         If $postId is not valid.
	 */
	public function setPostId( $postId ) {
		if( !ae_Validate::id( $postId ) ) {
			throw new Exception( '[' . get_class() . '] Not a valid post ID: ' . htmlspecialchars( $postId ) );
		}

		$this->postId = $postId;
	}


	/**
	 * Set comment status.
	 * @param  {string}    $status Comment status.
	 * @throws {Exception}         If $status is not a valid comment status.
	 */
	public function setStatus( $status ) {
		$validStatuses = array(
			self::STATUS_APPROVED, self::STATUS_SPAM,
			self::STATUS_TRASH, self::STATUS_UNAPPROVED
		);

		if( !in_array( $status, $validStatuses ) ) {
			$msg = sprintf( '[%s] Not a valid status: %s', get_class(), htmlspecialchars( $status ) );
			throw new Exception( $msg );
		}

		$this->status = $status;
	}


}