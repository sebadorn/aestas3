<?php

class ae_PageModel extends ae_Model {


	const COMMENTS_CLOSED = 'closed';
	const COMMENTS_DISABLED = 'disabled';
	const COMMENTS_OPEN = 'open';

	const STATUS_DRAFT = 'draft';
	const STATUS_PUBLISHED = 'published';
	const STATUS_TRASH = 'trash';

	const TABLE = AE_TABLE_PAGES;
	const TABLE_ID_FIELD = 'pa_id';

	protected $commentsStatus = self::COMMENTS_OPEN;
	protected $content = '';
	protected $datetime = '0000-00-00 00:00:00';
	protected $desc = '';
	protected $editDatetime = NULL;
	protected $permalink = '';
	protected $status = self::STATUS_DRAFT;
	protected $title = '';
	protected $userId = 0;

	protected $socialId = NULL;
	protected $socialData = array();


	/**
	 * Constructor.
	 * @param {array} $data Page data to initialize the model with. (Optional.)
	 */
	public function __construct( $data = array() ) {
		$this->loadFromData( $data );
	}


	/**
	 *
	 */
	protected function createSocial() {
		if( empty( $this->socialData ) ) {
			return;
		}

		$params = array(
			':tw_title' => $this->socialData['tw_title'],
			':tw_desc' => $this->socialData['tw_desc'],
			':tw_image' => $this->socialData['tw_image']
		);

		$stmt = '
			INSERT INTO `' . AE_TABLE_SOCIAL . '` (
				soc_tw_title,
				soc_tw_desc,
				soc_tw_image
			) VALUES (
				:tw_title,
				:tw_desc,
				:tw_image
			)
		';

		if( ae_Database::query( $stmt, $params ) === FALSE ) {
			return;
		}

		$this->socialId = $this->getLastInsertedSocialId();

		$params = array(
			':id' => $this->getId(),
			':social' => $this->socialId
		);

		$class = get_class( $this );
		$table = constant( $class . '::TABLE' );
		$prefix = 'pa';

		if( $table == AE_TABLE_POSTS ) {
			$prefix = 'po';
		}

		$stmt = '
			UPDATE `' . $table . '`
			SET ' . $prefix . '_social = :social
			WHERE ' . $prefix . '_id = :id
		';

		ae_Database::query( $stmt, $params );
	}


	/**
	 * Delete the loaded post and its social.
	 * @return {boolean} FALSE, if deletion failed,
	 *                   TRUE otherwise (including the case that the model doesn't exist).
	 */
	public function delete() {
		if( !parent::delete() ) {
			return FALSE;
		}

		$successSocial = TRUE;

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

		return $successSocial;
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
	 * @param  {string} $format Format.
	 * @return {string}         Page datetime.
	 */
	public function getDatetime( $format = 'Y-m-d H:i:s' ) {
		$dt = strtotime( $this->datetime );

		return date( $format, $dt );
	}


	/**
	 * Get page description.
	 * @return {string} Page description.
	 */
	public function getDescription() {
		return $this->desc;
	}


	/**
	 * Get page edit datetime (time it was edited).
	 * @param  {string} $format Format.
	 * @return {string} Page edit datetime.
	 */
	public function getEditDatetime( $format = 'Y-m-d H:i:s' ) {
		$dt = strtotime( $this->editDatetime );

		return date( $format, $dt );
	}


	/**
	 * Get the last inserted social ID.
	 * @return {int|boolean} The ID on success, FALSE on failure.
	 */
	public function getLastInsertedSocialId() {
		$stmt = '
			SELECT DISTINCT LAST_INSERT_ID() as id
			FROM `' . AE_TABLE_SOCIAL . '`
		';
		$result = ae_Database::query( $stmt );

		if( $result === FALSE ) {
			return FALSE;
		}

		return (int) $result[0]['id'];
	}


	/**
	 * Get complete permalink for the page (not including the domain and directory).
	 * @param  {string} $urlBase URL base of the link. (Optional, defaults to constant "URL".)
	 * @return {string}          Complete permalink.
	 */
	public function getLink( $urlBase = URL ) {
		if( ae_Settings::isModRewriteEnabled() ) {
			$link= $urlBase . PERMALINK_BASE_PAGE . $this->getPermalink();
		}
		else {
			$urlBase .= ( $urlBase[mb_strlen( $urlBase ) - 1] == '?' ) ? '&amp;' : '?';
			$link = $urlBase . PERMALINK_GET_PAGE . '=' . $this->getId();
		}

		return $link;
	}


	/**
	 * Get page permalink.
	 * @return {string} Page permalink.
	 */
	public function getPermalink() {
		return $this->permalink;
	}


	/**
	 * Get social data.
	 * @return {array}
	 */
	public function getSocialData() {
		return $this->socialData;
	}


	/**
	 * Get the social data Id.
	 * @return {?int}
	 */
	public function getSocialId() {
		return $this->socialId;
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
	 *
	 * @return {boolean}
	 */
	public function hasDescription() {
		// '0' is interpreted as empty. So
		// also check with is_numeric().
		if( empty( $this->desc ) ) {
			return is_numeric( $this->desc );
		}

		return true;
	}


	/**
	 * Check, if given status is a valid page/post status.
	 * @param  {string}  $status Page/post status.
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
		return array( self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_TRASH );
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
		if( isset( $data['pa_desc'] ) ) {
			$this->setDescription( $data['pa_desc'] );
		}
		if( isset( $data['pa_edit'] ) && $data['pa_edit'] != NULL ) {
			$this->setEditDatetime( $data['pa_edit'] );
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
		if( isset( $data['pa_social'] ) ) {
			$this->setSocialId( $data['pa_social'] );
		}
	}


	/**
	 * Load model data from DB identified by the given permalink.
	 * @param {string} $permalink Permalink to identify the page by.
	 */
	public function loadFromPermalink( $permalink ) {
		$stmt = '
			SELECT * FROM `' . self::TABLE . '`
			WHERE pa_permalink = :permalink
		';
		$params = array(
			':permalink' => $permalink
		);

		$result = ae_Database::query( $stmt, $params );

		if( $result === FALSE || count( $result ) < 1 ) {
			return FALSE;
		}

		$this->loadFromData( $result[0] );

		return TRUE;
	}


	/**
	 * Load social data.
	 * @return {boolean} TRUE, if loading succeeded, FALSE otherwise.
	 */
	public function loadSocial() {
		if( $this->socialId === NULL ) {
			return FALSE;
		}

		if( !ae_Validate::id( $this->socialId ) ) {
			throw new Exception( '[' . get_class() . '] Cannot load social data. No valid id.' );
		}

		$stmt = '
			SELECT * FROM `' . AE_TABLE_SOCIAL . '`
			WHERE soc_id = :id
		';
		$params = array(
			':id' => $this->socialId
		);

		$result = ae_Database::query( $stmt, $params );

		if( $result === FALSE ) {
			return FALSE;
		}

		$this->socialData = $result[0];

		return TRUE;
	}


	/**
	 *
	 * @param {string} $type - "twitter"
	 */
	public function renderSocial( $type ) {
		if( $type == 'twitter' ) {
			$title = isset( $this->socialData['soc_tw_title'] ) ? $this->socialData['soc_tw_title'] : NULL;

			// Required attributes.
			if( !$title ) {
				return;
			}

			$desc = isset( $this->socialData['soc_tw_desc'] ) ? $this->socialData['soc_tw_desc'] : NULL;
			$image = isset( $this->socialData['soc_tw_image'] ) ? $this->socialData['soc_tw_image'] : NULL;

			echo '<meta name="twitter:card" content="summary" />' . PHP_EOL;
			echo '<meta name="twitter:title" content="' . addslashes( $title ) . '" />' . PHP_EOL;

			if( $desc ) {
				echo '<meta name="twitter:description" content="' . addslashes( $desc ) . '" />' . PHP_EOL;
			}

			if( $image ) {
				echo '<meta name="twitter:image" content="' . addslashes( $image ) . '" />' . PHP_EOL;
			}
		}
	}


	/**
	 * Save the page to DB. If an ID is set, it will update
	 * the page, otherwise it will create a new one.
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
			':user' => $this->userId,
			':social' => $this->socialId,
			':comments' => $this->commentsStatus,
			':status' => $this->status
		);

		// Create new page
		if( $this->id === FALSE && !$forceInsert ) {
			$stmt = '
				INSERT INTO `' . AE_TABLE_PAGES . '` (
					pa_title,
					pa_permalink,
					pa_content,
					pa_desc,
					pa_datetime,
					pa_user,
					pa_social,
					pa_comments,
					pa_status
				) VALUES (
					:title,
					:permalink,
					:content,
					:desc,
					:datetime,
					:user,
					:social,
					:comments,
					:status
				)
			';
		}
		// Create new page with set ID
		else if( $this->id !== FALSE && $forceInsert ) {
			$stmt = '
				INSERT INTO `' . AE_TABLE_PAGES . '` (
					pa_id,
					pa_title,
					pa_permalink,
					pa_content,
					pa_desc,
					pa_datetime,
					pa_user,
					pa_social,
					pa_comments,
					pa_status
				) VALUES (
					:id,
					:title,
					:permalink,
					:content,
					:desc,
					:datetime,
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
				UPDATE `' . AE_TABLE_PAGES . '` SET
					pa_title = :title,
					pa_permalink = :permalink,
					pa_content = :content,
					pa_desc = :desc,
					pa_datetime = :datetime,
					pa_edit = :editDatetime,
					pa_user = :user,
					pa_social = :social,
					pa_comments = :comments,
					pa_status = :status
				WHERE
					pa_id = :id
			';
			$params[':id'] = $this->id;
			$params[':editDatetime'] = date( 'Y-m-d H:i:s' );
		}
		else {
			$msg = sprintf( '[%s] Supposed to insert new page with set ID, but no ID has been set.', get_class() );
			throw new Exception( $msg );
		}

		if( ae_Database::query( $stmt, $params ) === FALSE ) {
			return FALSE;
		}

		// If a new page was created, get the new ID
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
	 * Set post comments status.
	 * @param  {string}    $commentsStatus Page comments status.
	 * @throws {Exception}                 If $commentsStatus is not a valid page comments status.
	 */
	public function setCommentsStatus( $commentsStatus ) {
		$validStatuses = array( self::COMMENTS_CLOSED, self::COMMENTS_DISABLED, self::COMMENTS_OPEN );

		if( !in_array( $commentsStatus, $validStatuses, TRUE ) ) {
			$msg = sprintf( '[%s] Not a valid comments status: %d', get_class(), htmlspecialchars( $commentsStatus ) );
			throw new Exception( $msg );
		}

		$this->commentsStatus = $commentsStatus;
	}


	/**
	 * Set page content.
	 * @param {string} $content Page content.
	 */
	public function setContent( $content ) {
		$this->content = (string) $content;
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
	 * Set page description.
	 * @param {string} $desc Page description.
	 */
	public function setDescription( $desc ) {
		$this->desc = (string) $desc;
	}


	/**
	 * Set page edit datetime (time it was edited).
	 * @param  {string}    $datetime The datetime.
	 * @throws {Exception}           If $datetime is not a valid format.
	 */
	public function setEditDatetime( $datetime ) {
		if( !ae_Validate::datetime( $datetime ) ) {
			$msg = sprintf( '[%s] Not a valid datetime: %s', get_class(), $datetime );
			throw new Exception( $msg );
		}

		$this->editDatetime = $datetime;
	}


	/**
	 * Set post permalink.
	 * @param  {string} $permalink Post permalink
	 * @return {string}            The actually used permalink.
	 */
	public function setPermalink( $permalink ) {
		$this->permalink = ae_Permalink::generatePermalink( $permalink );

		return $this->permalink;
	}


	/**
	 *
	 * @param {?array} $socialData
	 */
	public function setSocialData( $socialData ) {
		if( $socialData === NULL ) {
			$socialData = array();
		}

		if( !is_array( $socialData ) ) {
			$msg = sprintf( '[%s] Expected social data to be an array or NULL.', get_class() );
			throw new Exception( $msg );
		}

		$this->socialData = $socialData;
	}


	/**
	 * Set the page social Id.
	 * @param  {?int} $socialId
	 * @throws {Exception}
	 */
	public function setSocialId( $socialId ) {
		if( $socialId === NULL ) {
			$this->socialId = NULL;
			return;
		}

		if( !ae_Validate::id( $socialId ) ) {
			$msg = sprintf( '[%s] Not a valid social ID: %s', get_class(), $socialId );
			throw new Exception( $msg );
		}

		$this->socialId = (int) $socialId;
	}


	/**
	 * Set page status.
	 * @param  {string}    $status Page status.
	 * @throws {Exception}         If $status is not a valid page status.
	 */
	public function setStatus( $status ) {
		if( !self::isValidStatus( $status ) ) {
			$msg = sprintf( '[%s] Not a valid status: %s', get_class(), htmlspecialchars( $status ) );
			throw new Exception( $msg );
		}

		$this->status = $status;
	}


	/**
	 * Set the page title.
	 * @param {string} $title Page title.
	 */
	public function setTitle( $title ) {
		$this->title = (string) $title;
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

		$this->userId = (int) $userId;
	}


	/**
	 *
	 */
	protected function updateSocial() {
		if( !$this->socialId ) {
			$this->createSocial();
		}
		else {
			$params = array(
				':soc_id' => $this->socialId,
				':tw_title' => $this->socialData['tw_title'],
				':tw_desc' => $this->socialData['tw_desc'],
				':tw_image' => $this->socialData['tw_image']
			);

			$stmt = '
				UPDATE `' . AE_TABLE_SOCIAL . '` SET
					soc_tw_title = :tw_title,
					soc_tw_desc = :tw_desc,
					soc_tw_image = :tw_image
				WHERE
					soc_id = :soc_id
			';

			ae_Database::query( $stmt, $params );
		}
	}


}
