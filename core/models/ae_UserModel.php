<?php

class ae_UserModel extends ae_Model {


	const STATUS_ACTIVE = 'active';
	const STATUS_SUSPENDED = 'suspended';

	const TABLE = AE_TABLE_USERS;
	const TABLE_ID_FIELD = 'u_id';

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
	 * Get complete permalink for the user (not including the domain and directory).
	 * @param  {string} $urlBase URL base of the link. (Optional, defaults to constant "URL".)
	 * @return {string}          Complete permalink.
	 */
	public function getLink( $urlBase = URL ) {
		if( ae_Settings::isModRewriteEnabled() ) {
			$link= $urlBase . PERMALINK_BASE_USER . $this->getPermalink();
		}
		else {
			$urlBase .= ( $urlBase[mb_strlen( $urlBase ) - 1] == '?' ) ? '&amp;' : '?';
			$link = $urlBase . PERMALINK_GET_USER . '=' . $this->getId();
		}

		return $link;
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
	 * Check, if given status is a valid user status.
	 * @param  {string}  $status User status.
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
		return array( self::STATUS_ACTIVE, self::STATUS_SUSPENDED );
	}


	/**
	 * Initialize model from the given data array.
	 * @param {array} $data The model data.
	 */
	protected function loadFromData( $data ) {
		if( isset( $data['u_id'] ) ) {
			$this->setId( $data['u_id'] );
		}
		if( isset( $data['u_name_extern'] ) ) {
			$this->setNameExternal( $data['u_name_extern'] );
		}
		if( isset( $data['u_name_intern'] ) ) {
			$this->setNameInternal( $data['u_name_intern'] );
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
	 * Load model data from DB identified by the given permalink.
	 * @param {string} $permalink Permalink to identify the user by.
	 */
	public function loadFromPermalink( $permalink ) {
		$stmt = '
			SELECT * FROM `' . self::TABLE . '`
			WHERE u_permalink = :permalink
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
	 * Save the user to DB. If an ID is set, it will update
	 * the user, otherwise it will create a new one.
	 * @param  {boolean}   $forceInsert If set to TRUE and an ID has been set, the model will be saved
	 *                                  as new entry instead of updating. (Optional, default is FALSE.)
	 * @return {boolean}                TRUE, if saving is successful, FALSE otherwise.
	 * @throws {Exception}              If $forceInsert is TRUE, but no valid ID is set.
	 */
	public function save( $forceInsert = FALSE ) {
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

		// Create new user
		if( $this->id === FALSE && !$forceInsert ) {
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
		// Create new user with set ID
		else if( $this->id !== FALSE && $forceInsert ) {
			$stmt = '
				INSERT INTO `' . AE_TABLE_USERS . '` (
					u_id,
					u_pwd,
					u_name_intern,
					u_name_extern,
					u_permalink,
					u_status
				) VALUES (
					:id,
					:password,
					:nameInternal,
					:nameExternal,
					:permalink,
					:status
				)
			';
			$params[':id'] = $this->id;
		}
		// Update existing one
		else if( $this->id !== FALSE ) {
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
		else {
			$msg = sprintf( '[%s] Supposed to insert new user with set ID, but no ID has been set.', get_class() );
			throw new Exception( $msg );
		}

		if( ae_Database::query( $stmt, $params ) === FALSE ) {
			return FALSE;
		}

		// If a new user was created, get the new ID
		if( $this->id === FALSE ) {
			$this->setId( $this->getLastInsertedId() );
		}

		return TRUE;
	}


	/**
	 * Set the external user name.
	 * @param {string} $name New user name (external).
	 */
	public function setNameExternal( $name ) {
		$this->nameExternal = (string) $name;
	}


	/**
	 * Set the internal user name.
	 * @param  {string}    $name New user name (internal).
	 * @throws {Exception}       If $name is empty.
	 */
	public function setNameInternal( $name ) {
		if( mb_strlen( $name ) == 0 ) {
			$msg = sprintf( '[%s] Internal name cannot be empty.', get_class() );
			throw new Exception( $msg );
		}

		$this->nameInternal = (string) $name;
	}


	/**
	 * Set the user password hash.
	 * @param  {string}    $hash New user password hash.
	 * @throws {Exception}       If $hash is empty.
	 */
	public function setPasswordHash( $hash ) {
		if( mb_strlen( $hash ) == 0 ) {
			$msg = sprintf( '[%s] Password hash cannot be empty.', get_class() );
			throw new Exception( $msg );
		}

		$this->pwdHash = (string) $hash;
	}


	/**
	 * Set user permalink.
	 * @param  {string} $permalink User permalink
	 * @return {string}            The actually used permalink.
	 */
	public function setPermalink( $permalink ) {
		$this->permalink = ae_Permalink::generatePermalink( $permalink );

		return $this->permalink;
	}


	/**
	 * Set user status.
	 * @param  {string}    $status User status.
	 * @throws {Exception}         If $status is not a valid user status.
	 */
	public function setStatus( $status ) {
		if( !self::isValidStatus( $status ) ) {
			$msg = sprintf( '[%s] Not a valid status: %s', get_class(), $status );
			throw new Exception( $msg );
		}

		$this->status = $status;
	}


}