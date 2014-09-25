<?php

class ae_MediaModel extends ae_Model {


	const STATUS_AVAILABLE = 'available';
	const STATUS_TRASH = 'trash';

	const TABLE = AE_TABLE_MEDIA;
	const TABLE_ID_FIELD = 'm_id';

	protected $datetime = '0000-00-00 00:00:00';
	protected $name = '';
	protected $status = self::STATUS_AVAILABLE;
	protected $tmpName = '';
	protected $type = '';
	protected $userId = 0;


	/**
	 * Constructor.
	 * @param {array} $data Data to initialize the model with. (Optional.)
	 */
	public function __construct( $data = array() ) {
		$this->loadFromData( $data );
	}


	/**
	 * Delete the loaded media - from the DB and file system.
	 * @return {boolean} FALSE, if deletion failed,
	 *                   TRUE otherwise (including the case that the model doesn't exist).
	 */
	public function delete() {
		if( !parent::delete() ) {
			return FALSE;
		}

		// TODO: delete from file system
		$msg = sprintf( '[%s] Deleting from file system not yet implemented!', get_class() );
		throw new Exception( $msg );

		return TRUE;
	}


	/**
	 * Get datetime of upload.
	 * @return {string} Datetime of upload.
	 */
	public function getDatetime() {
		return $this->datetime;
	}


	/**
	 * Get media (file) name.
	 * @return {string} Get the name of the file, including extension.
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * Get media status.
	 * @return {string} Media status.
	 */
	public function getStatus() {
		return $this->status;
	}


	/**
	 * Get the temporary name after uploading.
	 * Will not be saved to DB.
	 * @return {string} The temporary name after uploading or an empty string.
	 */
	public function getTmpName() {
		return $this->tmpName;
	}


	/**
	 * Get the MIME type.
	 * @return {string} MIME type as determined at upload.
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * Check, if given status is a valid media status.
	 * @param  {string}  $status Media status.
	 * @return {boolean}         TRUE, if $status is valid, FALSE otherwise.
	 */
	static public function isValidStatus( $status ) {
		return in_array( $status, self::listStatuses() );
	}


	/**
	 * Get a list of valid statuses.
	 * @return {array} List of valid statuses.
	 */
	static public function listStatuses() {
		return array( self::STATUS_AVAILABLE, self::STATUS_TRASH );
	}


	/**
	 * Initialize model from the given data.
	 * @param {array} $data The model data.
	 */
	protected function loadFromData( $data ) {
		if( isset( $data['m_id'] ) ) {
			$this->setId( $data['m_id'] );
		}
		if( isset( $data['m_datetime'] ) ) {
			$this->setDatetime( $data['m_datetime'] );
		}
		if( isset( $data['m_name'] ) ) {
			$this->setName( $data['m_name'] );
		}
		if( isset( $data['m_status'] ) ) {
			$this->setStatus( $data['m_status'] );
		}
		if( isset( $data['m_type'] ) ) {
			$this->setType( $data['m_type'] );
		}
		if( isset( $data['m_user'] ) ) {
			$this->setUserId( $data['m_user'] );
		}
	}


	/**
	 * Save media to DB. If an ID is set, it will update
	 * it, otherwise it will create a new one.
	 * @param  {boolean}   $forceInsert If set to TRUE and an ID has been set, the model will be saved
	 *                                  as new entry instead of updating. (Optional, default is FALSE.)
	 * @return {boolean}                TRUE, if saving is successful, FALSE otherwise.
	 * @throws {Exception}              If name is not valid.
	 * @throws {Exception}              If $forceInsert is TRUE, but no valid ID is set.
	 */
	public function save( $forceInsert = FALSE ) {
		if( mb_strlen( $this->name ) == 0 ) {
			$msg = sprintf( '[%s] Cannot save. Name is empty.', get_class() );
			throw new Exception( $msg );
		}

		if( $this->datetime == '0000-00-00 00:00:00' ) {
			$this->setDatetime( date( 'Y-m-d H:i:s' ) );
		}

		$params = array(
			':datetime' => $this->datetime,
			':name' => $this->name,
			':status' => $this->status,
			':type' => $this->type,
			':userId' => $this->userId
		);

		// Create new media
		if( $this->id === FALSE && !$forceInsert ) {
			$stmt = '
				INSERT INTO `' . self::TABLE . '` (
					m_datetime,
					m_name,
					m_status,
					m_type,
					m_user
				)
				VALUES (
					:datetime,
					:name,
					:status,
					:type,
					:userId
				)
			';
		}
		// Create new media with set ID
		else if( $this->id !== FALSE && $forceInsert ) {
			$stmt = '
				INSERT INTO `' . self::TABLE . '` (
					m_id,
					m_datetime,
					m_name,
					m_status,
					m_type,
					m_user
				)
				VALUES (
					:id,
					:datetime,
					:name,
					:status,
					:type,
					:userId
				)
			';
			$params[':id'] = $this->id;
		}
		// Update existing one
		else if( $this->id !== FALSE ) {
			$stmt = '
				UPDATE `' . self::TABLE . '` SET
					m_datetime = :datetime,
					m_name = :name,
					m_status = :status,
					m_type = :type,
					m_user = :userId
				WHERE
					m_id = :id
			';
			$params[':id'] = $this->id;
		}
		else {
			$msg = sprintf( '[%s] Supposed to insert new media with set ID, but no ID has been set.', get_class() );
			throw new Exception( $msg );
		}

		if( ae_Database::query( $stmt, $params ) === FALSE ) {
			return FALSE;
		}

		// If new media was created, get the new ID
		if( $this->id === FALSE ) {
			$this->setId( $this->getLastInsertedId() );
		}

		return TRUE;
	}


	/**
	 * Set media datetime (time it was uploaded).
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
	 * Set media name.
	 * @param  {string}    $name Name of the file.
	 * @throws {Exception}       If $name is empty.
	 */
	public function setName( $name ) {
		if( mb_strlen( $name ) == 0 ) {
			$msg = sprintf( '[%s] Empty name.', get_class() );
			throw new Exception( $msg );
		}

		$this->name = str_replace( array( '/', '\\' ), '-', $name );
	}


	/**
	 * Set media status.
	 * @param  {string}    $status Media status.
	 * @throws {Exception}         If $status is not a valid media status.
	 */
	public function setStatus( $status ) {
		if( !self::isValidStatus( $status ) ) {
			$msg = sprintf( '[%s] Not a valid status: %s', get_class(), htmlspecialchars( $status ) );
			throw new Exception( $msg );
		}

		$this->status = $status;
	}


	/**
	 * Set the temporary file name.
	 * @param {string} $tmpName Temporary file name.
	 */
	public function setTmpName( $tmpName ) {
		$this->tmpName = $tmpName;
	}


	/**
	 * Set MIME type.
	 * @param {string} $type MIME type.
	 */
	public function setType( $type ) {
		$this->type = $type;
	}


	/**
	 * Set user ID.
	 * @param  {int}       $user User ID.
	 * @throws {Exception}       If $user is not a valid ID.
	 */
	public function setUserId( $user ) {
		if( !ae_Validate::id( $user ) ) {
			$msg = sprintf( '[%s] Not a valid ID: %s', get_class(), htmlspecialchars( $user ) );
			throw new Exception( $msg );
		}

		$this->userId = $user;
	}


}