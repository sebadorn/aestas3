<?php

class ae_UserModel extends ae_Model {


	const STATUS_ACTIVE = 'active';
	const STATUS_SUSPENDED = 'suspended';

	protected $id = FALSE;
	protected $nameExternal = '';
	protected $nameInternal = '';
	protected $permalink = '';
	protected $pwdHash = '';
	protected $status = self::STATUS_SUSPENDED;


	/**
	 * Constructor.
	 * @param {array} $data User data to initialize the model with. (Optional.)
	 */
	public function __construct( $data = array() ) {
		$this->loadFromData( $data );
	}


	/**
	 * Get user ID.
	 * @return {int} User ID.
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * Get external user name.
	 * @return {string} User name (extern).
	 */
	public function getNameExternal() {
		return $this->nameExternal;
	}


	/**
	 * Get internal user name.
	 * @return {string} User name (internal).
	 */
	public function getNameInternal() {
		return $this->nameInternal;
	}


	/**
	 * Get user password hash.
	 * @return {string} User password hash.
	 */
	public function getPasswordHash() {
		return $this->pwdHash;
	}


	/**
	 * Get user permalink.
	 * @return {string} User permalink.
	 */
	public function getPermalink() {
		return $this->permalink;
	}


	/**
	 * Get user status.
	 * @return {string} User status.
	 */
	public function getStatus() {
		return $this->status;
	}


	/**
	 * Load a user with the given ID.
	 * @param  {int}     $id ID of the user to load.
	 * @return {boolean}     TRUE, if loading succeeded, FALSE otherwise.
	 */
	public function load( $id ) {
		$this->setId( $id );

		$stmt = '
			SELECT *
			FROM `' . AE_TABLE_USERS . '`
			WHERE u_id = :id
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
		if( isset( $data['u_id'] ) ) {
			$this->setId( $data['u_id'] );
		}
		if( isset( $data['u_name_external'] ) ) {
			$this->setNameExternal( $data['u_name_external'] );
		}
		if( isset( $data['u_name_internal'] ) ) {
			$this->setNameInternal( $data['u_name_internal'] );
		}
		if( isset( $data['u_permalink'] ) ) {
			$this->setPermalink( $data['u_permalink'] );
		}
		if( isset( $data['u_pwd'] ) ) {
			$this->setPasswordHash( $data['u_pwd'] );
		}
		if( isset( $data['u_status'] ) ) {
			$this->setStatus( $data['u_status'] );
		}
	}


	/**
	 * Save the user to DB. If an ID is set, it will update
	 * the user, otherwise it will create a new one.
	 * @return {boolean} TRUE, if saving is successful, FALSE otherwise.
	 */
	public function save() {
		if( $this->permalink == '' ) {
			$this->setPermalink( $this->nameExternal );
		}

		$params = array(
			':nameInternal' => $this->nameInternal,
			':nameExternal' => $this->nameExternal,
			':permalink' => $this->permalink,
			':password' => $this->pwdHash,
			':status' => $this->status
		);

		// Create new page
		if( $this->id === FALSE ) {
			$stmt = '
				INSERT INTO `' . AE_TABLE_USERS . '` (
					u_pwd,
					u_name_intern,
					u_name_extern,
					u_permalink,
					u_status
				) VALUES (
					:password,
					:nameInternal,
					:nameExternal,
					:permalink,
					:status
				)
			';
		}
		// Update existing one
		else {
			$stmt = '
				UPDATE `' . AE_TABLE_USERS . '` SET
					u_pwd = :password,
					u_name_intern = :nameInternal,
					u_name_extern = :nameExternal,
					u_permalink = :permalink,
					u_status = :status
				WHERE
					u_id = :id
			';
			$params[':id'] = $this->id;
		}

		if( ae_Database::query( $stmt, $params ) === FALSE ) {
			return FALSE;
		}

		// If a new user was created, get the new ID
		if( $this->id === FALSE ) {
			$stmt = 'SELECT DISTINCT LAST_INSERT_ID() as u_id FROM `' . AE_TABLE_USERS . '`';
			$result = ae_Database::query( $stmt );

			if( $result === FALSE ) {
				return FALSE;
			}

			$this->setId( $result[0]['u_id'] );
		}

		return TRUE;
	}


	/**
	 * Set the user ID.
	 * @param  {int}       $id New user ID.
	 * @throws {Exception}     If $id is not valid.
	 */
	public function setId( $id ) {
		if( !ae_Validate::id( $id ) ) {
			throw new Exception( '[' . get_class() . '] Not a valid ID: ' . htmlspecialchars( $id ) );
		}

		$this->id = $id;
	}


	/**
	 * Set the external user name.
	 * @param {string} $name New user name (external).
	 */
	public function setNameExternal( $name ) {
		$this->nameExternal = $name;
	}


	/**
	 * Set the internal user name.
	 * @param  {string}    $name New user name (internal).
	 * @throws {Exception}       If $name is empty.
	 */
	public function setNameInternal( $name ) {
		if( strlen( $name ) == 0 ) {
			throw new Exception( '[' . get_class() . '] Internal name cannot be empty.' );
		}

		$this->nameInternal = $name;
	}


	/**
	 * Set the user password hash.
	 * @param  {string}    $hash New user password hash.
	 * @throws {Exception}       If $hash is empty.
	 */
	public function setPasswordHash( $hash ) {
		if( strlen( $hash ) == 0 ) {
			throw new Exception( '[' . get_class() . '] Password hash cannot be empty.' );
		}

		$this->pwdHash = $hash;
	}


	/**
	 * Set user permalink.
	 * @param  {string} $permalink User permalink
	 * @return {string}            The actually used permalink.
	 */
	public function setPermalink( $permalink ) {
		$this->permalink = self::generatePermalink( $permalink );

		return $this->permalink;
	}


	/**
	 * Set user status.
	 * @param  {string}    $status User status.
	 * @throws {Exception}         If $status is not a valid user status.
	 */
	public function setStatus( $status ) {
		$validStatuses = array( self::STATUS_ACTIVE, self::STATUS_SUSPENDED );

		if( !in_array( $status, $validStatuses ) ) {
			$msg = sprintf( '[%s] Not a valid status: %s', get_class(), $status );
			throw new Exception( $msg );
		}

		$this->status = $status;
	}


}