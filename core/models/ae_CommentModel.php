<?php

class ae_CommentModel extends ae_Model {


	const STATUS_APPROVED = 'approved';
	const STATUS_SPAM = 'spam';
	const STATUS_TRASH = 'trash';
	const STATUS_UNAPPROVED = 'unapproved';

	const TABLE = AE_TABLE_COMMENTS;
	const TABLE_ID_FIELD = 'co_id';

	protected $authorEmail = '';
	protected $authorIp = '';
	protected $authorName = COMMENT_DEFAULT_NAME;
	protected $authorUrl = '';
	protected $content = '';
	protected $datetime = '0000-00-00 00:00:00';
	protected $postId = FALSE;
	protected $status = self::STATUS_UNAPPROVED;
	protected $userId = 0;


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
	 * Get comment author IP.
	 * @return {string} Comment author IP.
	 */
	public function getAuthorIp() {
		return $this->authorIp;
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
	 * @param  {string} $format Format.
	 * @return {string}         Comment datetime.
	 */
	public function getDatetime( $format = 'Y-m-d H:i:s' ) {
		$dt = strtotime( $this->datetime );

		return date( $format, $dt );
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
	 * Get comment user ID.
	 * @return {int} User ID, if written by a logged-in user, 0 otherwise.
	 */
	public function getUserId() {
		return $this->userId;
	}


	/**
	 * Check, if given status is a valid comment status.
	 * @param  {string}  $status Comment status.
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
		return array(
			self::STATUS_APPROVED, self::STATUS_UNAPPROVED,
			self::STATUS_SPAM, self::STATUS_TRASH
		);
	}


	/**
	 * Initialize model from the given data.
	 * @param {array} $data The model data.
	 */
	protected function loadFromData( $data ) {
		if( isset( $data['co_id'] ) ) {
			$this->setId( $data['co_id'] );
		}
		if( isset( $data['co_ip'] ) ) {
			$this->setAuthorIp( $data['co_ip'] );
		}
		if( isset( $data['co_post'] ) ) {
			$this->setPostId( $data['co_post'] );
		}
		if( isset( $data['co_user'] ) ) {
			$this->setUserId( $data['co_user'] );
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
	 * @param  {boolean}   $forceInsert If set to TRUE and an ID has been set, the model will be saved
	 *                                  as new entry instead of updating. (Optional, default is FALSE.)
	 * @return {boolean}                TRUE, if saving is successful, FALSE otherwise.
	 * @throws {Exception}              If no post ID is given.
	 * @throws {Exception}              If $forceInsert is TRUE, but no valid ID is set.
	 */
	public function save( $forceInsert = FALSE ) {
		if( $this->postId === FALSE ) {
			throw new Exception( '[' . get_class() . '] Cannot save comment. No post ID.' );
		}

		if( $this->datetime == '0000-00-00 00:00:00' ) {
			$this->setDatetime( date( 'Y-m-d H:i:s' ) );
		}

		$params = array(
			':postId' => $this->postId,
			':authorName' => $this->authorName,
			':authorEmail' => $this->authorEmail,
			':authorIp' => $this->authorIp,
			':authorUrl' => $this->authorUrl,
			':datetime' => $this->datetime,
			':content' => $this->content,
			':status' => $this->status,
			':userId' => $this->userId
		);

		// Create new comment
		if( $this->id === FALSE && !$forceInsert ) {
			$stmt = '
				INSERT INTO `' . AE_TABLE_COMMENTS . '` (
					co_ip,
					co_post,
					co_user,
					co_name,
					co_email,
					co_url,
					co_datetime,
					co_content,
					co_status
				)
				VALUES (
					:authorIp,
					:postId,
					:userId,
					:authorName,
					:authorEmail,
					:authorUrl,
					:datetime,
					:content,
					:status
				)
			';
		}
		// Create new comment with set ID
		else if( $this->id !== FALSE && $forceInsert ) {
			$stmt = '
				INSERT INTO `' . AE_TABLE_COMMENTS . '` (
					co_id,
					co_ip,
					co_post,
					co_user,
					co_name,
					co_email,
					co_url,
					co_datetime,
					co_content,
					co_status
				)
				VALUES (
					:id,
					:authorIp,
					:postId,
					:userId,
					:authorName,
					:authorEmail,
					:authorUrl,
					:datetime,
					:content,
					:status
				)
			';
			$params[':id'] = $this->id;
		}
		// Update existing one
		else if( $this->id !== FALSE ) {
			$stmt = '
				UPDATE `' . AE_TABLE_COMMENTS . '` SET
					co_ip = :authorIp,
					co_post = :postId,
					co_user = :userId,
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
		else {
			$msg = sprintf( '[%s] Supposed to insert new comment with set ID, but no ID has been set.', get_class() );
			throw new Exception( $msg );
		}

		if( ae_Database::query( $stmt, $params ) === FALSE ) {
			return FALSE;
		}

		// If a new comment was created, get the new ID
		if( $this->id === FALSE ) {
			$this->setId( $this->getLastInsertedId() );
		}

		return TRUE;
	}


	/**
	 * Set the author email.
	 * @param  {string}    $email The author email. Can either be empty or a valid email.
	 * @throws {Exception}        If neither empty nor valid.
	 */
	public function setAuthorEmail( $email ) {
		$email = trim( $email );

		if( $email !== '' && !ae_Validate::emailSloppy( $email ) ) {
			$msg = sprintf( '[%s] Not a valid eMail: %s', get_class(), htmlspecialchars( $email ) );
			throw new Exception( $msg );
		}

		$this->authorEmail = $email;
	}


	/**
	 * Set the author IP.
	 * @param  {string}    $ip The author IP.
	 * @throws {Exception}     If $ip is not a valid IP.
	 */
	public function setAuthorIp( $ip ) {
		if( !ae_Validate::ip( $ip ) ) {
			$msg = sprintf( '[%s] Not a valid IP: %s', get_class(), htmlspecialchars( $ip ) );
			throw new Exception( $msg );
		}

		$this->authorIp = $ip;
	}


	/**
	 * Set comment author name.
	 * @param {string} $name The author name.
	 */
	public function setAuthorName( $name ) {
		$this->authorName = trim( $name );
	}


	/**
	 * Set comment author URL.
	 * @param  {string}    $url The URL. Either a valid URL or an empty string.
	 * @throws {Exception}      If $url is neither empty nor valid.
	 */
	public function setAuthorUrl( $url ) {
		$url = trim( $url );

		if( $url !== '' && !ae_Validate::urlSloppy( $url ) ) {
			$msg = sprintf( '[%s] Not a valid URL: %s', get_class(), htmlspecialchars( $url ) );
			throw new Exception( $msg );
		}

		$this->authorUrl = $url;
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
			$msg = sprintf( '[%s] Not a valid datetime: %s', get_class(), htmlspecialchars( $datetime ) );
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
			$msg = sprintf( '[%s] Not a valid post ID: %s', get_class(), htmlspecialchars( $postId ) );
			throw new Exception( $msg );
		}

		$this->postId = (int) $postId;
	}


	/**
	 * Set comment status.
	 * @param  {string}    $status Comment status.
	 * @throws {Exception}         If $status is not a valid comment status.
	 */
	public function setStatus( $status ) {
		if( !self::isValidStatus( $status ) ) {
			$msg = sprintf( '[%s] Not a valid status: %s', get_class(), htmlspecialchars( $status ) );
			throw new Exception( $msg );
		}

		$this->status = $status;
	}


	/**
	 * Set comment user ID.
	 * @param  {int}       $userId ID of the user or 0 if not of a registered user.
	 * @throws {Exception}         If $userId is not a number of < 0.
	 */
	public function setUserId( $userId ) {
		if( !ae_Validate::integer( $userId ) || $userId < 0 ) {
			$msg = sprintf( '[%s] User ID must be >= 0.', get_class() );
			throw new Exception( $msg );
		}

		$this->userId = (int) $userId;
	}


}