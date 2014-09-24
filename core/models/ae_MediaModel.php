<?php

class ae_MediaModel extends ae_Model {


	const STATUS_AVAILABLE = 'available';
	const STATUS_TRASH = 'trash';

	const TABLE = AE_TABLE_MEDIA;
	const TABLE_ID_FIELD = 'm_id';

	protected $status = self::STATUS_AVAILABLE;


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
		throw new Exception( '[' . get_class() . '] Deleting from file system not yet implemented!' );

		return TRUE;
	}


	/**
	 * Get media status.
	 * @return {string} Media status.
	 */
	public function getStatus() {
		return $this->status;
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
		if( isset( $data['m_status'] ) ) {
			$this->setStatus( $data['m_status'] );
		}
	}


	/**
	 * Save media to DB. If an ID is set, it will update
	 * it, otherwise it will create a new one.
	 * @param  {boolean}   $forceInsert If set to TRUE and an ID has been set, the model will be saved
	 *                                  as new entry instead of updating. (Optional, default is FALSE.)
	 * @return {boolean}                TRUE, if saving is successful, FALSE otherwise.
	 * @throws {Exception}              If title is not valid.
	 * @throws {Exception}              If $forceInsert is TRUE, but no valid ID is set.
	 */
	public function save( $forceInsert = FALSE ) {
		$params = array(
			':status' => $this->status
		);

		// Create new media
		if( $this->id === FALSE && !$forceInsert ) {
			$stmt = '
				INSERT INTO `' . self::TABLE . '` (
					m_status
				)
				VALUES (
					:status
				)
			';
		}
		// Create new media with set ID
		else if( $this->id !== FALSE && $forceInsert ) {
			$stmt = '
				INSERT INTO `' . self::TABLE . '` (
					m_id,
					m_status
				)
				VALUES (
					:id,
					:status
				)
			';
			$params[':id'] = $this->id;
		}
		// Update existing one
		else if( $this->id !== FALSE ) {
			$stmt = '
				UPDATE `' . self::TABLE . '` SET
					m_status = :status
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


}