<?php

class ae_MediaModel extends ae_Model {


	const STATUS_AVAILABLE = 'available';
	const STATUS_TRASH = 'trash';

	const TABLE = AE_TABLE_MEDIA;
	const TABLE_ID_FIELD = 'm_id';

	protected $datetime = '0000-00-00 00:00:00';
	protected $mediaPath = '../../media/';
	protected $meta = array();
	protected $name = '';
	protected $oldName = '';
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

		if( !$this->deleteFile() ) {
			return FALSE;
		}

		return TRUE;
	}


	/**
	 * Delete the associated file from the file system.
	 * @return {boolean} TRUE, if file could be deleted, FALSE otherwise.
	 */
	public function deleteFile() {
		$file = $this->mediaPath . $this->getFilePath();

		if( !unlink( $file ) ) {
			$msg = sprintf( '[%s] Failed to delete file: %s',
					get_class(), htmlspecialchars( $file ) );
			ae_Log::error( $msg );

			return FALSE;
		}

		if( $this->isImage() ) {
			$file = $this->mediaPath . $this->getFilePathNoName() . 'tiny/' . $this->getName();

			if( !unlink( $file ) ) {
				$msg = sprintf( '[%s] Failed to delete preview image: %s',
						get_class(), htmlspecialchars( $file ) );
				ae_Log::error( $msg );

				return FALSE;
			}
		}

		return TRUE;
	}


	/**
	 * Get datetime of upload.
	 * @param  {string} $format Format.
	 * @return {string}         Datetime of upload.
	 */
	public function getDatetime( $format = 'Y-m-d H:i:s' ) {
		$dt = strtotime( $this->datetime );

		return date( $format, $dt );
	}


	/**
	 * Get file path relative inside the media directory.
	 * @return {string} Path to the file inside the media directory.
	 */
	public function getFilePath() {
		return $this->getFilePathNoName() . $this->getName();
	}


	/**
	 * Get directory path relative inside the media directory.
	 * @return {string} Path to the file directory inside the media directory.
	 */
	public function getFilePathNoName() {
		return $this->getDatetime( 'Y/m/' );
	}


	/**
	 * Get the set path to the media directory.
	 * @return {string} Absolute or relative path to the media directory.
	 */
	public function getMediaPath() {
		return $this->mediaPath;
	}


	/**
	 * Get the encoded meta info.
	 * @return {array} Meta info.
	 */
	public function getMetaInfo() {
		return $this->meta;
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
	 * Get the upload user ID.
	 * @return {int} User ID of the uploader.
	 */
	public function getUserId() {
		return $this->userId;
	}


	/**
	 * Check, if media is an image according to MIME type.
	 * @return {boolean} TRUE, if is an image, FALSE otherwise.
	 */
	public function isImage() {
		$type = explode( '/', $this->type, 2 );

		return ( $type[0] == 'image' );
	}


	/**
	 * Check, if media is a text according to MIME type.
	 * @return {boolean} TRUE, if is a text, FALSE otherwise.
	 */
	public function isText() {
		$type = explode( '/', $this->type, 2 );

		return ( $type[0] == 'text' );
	}


	/**
	 * Check, if media is a video according to MIME type.
	 * @return {boolean} TRUE, if is a video, FALSE otherwise.
	 */
	public function isVideo() {
		$type = explode( '/', $this->type, 2 );

		return ( $type[0] == 'video' );
	}


	/**
	 * Check, if given status is a valid media status.
	 * @param  {string}  $status Media status.
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
		if( isset( $data['m_meta'] ) ) {
			$this->setMetaInfo( $data['m_meta'] );
		}
		if( isset( $data['m_name'] ) ) {
			$this->setName( $data['m_name'] );
			$this->oldName = $this->getName();
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
	 * Rename the file to the currently set name.
	 */
	protected function renameFile() {
		$pathOld = $this->mediaPath . $this->getFilePathNoName() . $this->oldName;
		$pathNew = $this->mediaPath . $this->getFilePath();

		if( !rename( $pathOld, $pathNew ) ) {
			$msg = sprintf( '[%s] Failed to rename "%s" to "%s".',
					get_class(), htmlspecialchars( $pathOld ), htmlspecialchars( $pathNew ) );
			throw new Exception( $msg );
		}

		$this->renamePreviewFile();
	}


	/**
	 * Rename the preview file to the currently set name.
	 */
	protected function renamePreviewFile() {
		$previewImage = $this->mediaPath . $this->getFilePathNoName() . 'tiny/' . $this->oldName;

		if( $this->isImage() && file_exists( $previewImage ) ) {
			$pathNew = $this->mediaPath . $this->getFilePathNoName() . 'tiny/' . $this->getName();

			if( !rename( $previewImage, $pathNew ) ) {
				$msg = sprintf( '[%s] Failed to rename "%s" to "%s".',
						get_class(), htmlspecialchars( $previewImage ), htmlspecialchars( $pathNew ) );
				throw new Exception( $msg );
			}
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

		if( $this->meta != '' ) {
			$meta = json_encode( $this->meta );

			if( $meta === FALSE ) {
				$msg = sprintf( '[%s] Failed to JSON encode meta data.', get_class() );
				throw new Exception( $msg );
			}
		}

		$params = array(
			':datetime' => $this->datetime,
			':meta' => $meta,
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
					m_meta,
					m_name,
					m_status,
					m_type,
					m_user
				)
				VALUES (
					:datetime,
					:meta,
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
					m_meta,
					m_name,
					m_status,
					m_type,
					m_user
				)
				VALUES (
					:id,
					:datetime,
					:meta,
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
					m_meta = :meta,
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
		// Name changed with update, rename the file
		else if( $this->name != $this->oldName ) {
			$this->renameFile();
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
	 * Set the path to the media directory.
	 * @param {string} $mediaPath Path to the media directory.
	 */
	public function setMediaPath( $mediaPath ) {
		$this->mediaPath = $mediaPath;
	}


	/**
	 * Set meta data.
	 * @param {array|string} $meta Meta data.
	 */
	public function setMetaInfo( $meta ) {
		if( is_string( $meta ) ) {
			$meta = json_decode( $meta, TRUE );

			if( $meta === NULL ) {
				$msg = sprintf( '[%s] Failed to JSON decode meta data.', get_class() );
				throw new Exception( $msg );
			}
		}
		else if( !is_array( $meta ) ) {
			$msg = sprintf( '[%s] Meta info is required to be an array.', get_class() );
			throw new Exception( $msg );
		}

		$this->meta = $meta;
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
		$this->tmpName = (string) $tmpName;
	}


	/**
	 * Set MIME type.
	 * @param {string} $type MIME type.
	 */
	public function setType( $type ) {
		$this->type = (string) $type;
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

		$this->userId = (int) $user;
	}


}