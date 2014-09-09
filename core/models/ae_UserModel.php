<?php

class ae_UserModel {


	protected $id = -1;
	protected $nameExternal = '';
	protected $nameInternal = '';
	protected $pwdHash = '';


	/**
	 * Constructor.
	 * @param {array} $data User data to initialize the model with. (Optional.)
	 */
	public function __construct( $data = array() ) {
		if( isset( $data['id'] ) ) {
			$this->setId( $data['id'] );
		}
		if( isset( $data['name_external'] ) ) {
			$this->setNameExternal( $data['name_external'] );
		}
		if( isset( $data['name_internal'] ) ) {
			$this->setNameInternal( $data['name_internal'] );
		}
		if( isset( $data['password_hash'] ) ) {
			$this->setPasswordHash( $data['password_hash'] );
		}
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


}