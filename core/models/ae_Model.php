<?php

abstract class ae_Model {


	protected $id = FALSE;


	/**
	 * Save the model to DB. If an ID is set, it will update
	 * the model, otherwise it will create a new one.
	 * @return {boolean} TRUE, if saving is successful, FALSE otherwise.
	 */
	abstract public function save();


	/**
	 * Delete the loaded model.
	 * @return {boolean}   FALSE, if deletion failed,
	 *                     TRUE otherwise (including the case that the model doesn't exist).
	 * @throws {Exception} If not a valid ID is set.
	 */
	public function delete() {
		if( !ae_Validate::id( $this->id ) ) {
			$msg = sprintf( '[%s] Cannot delete model. No valid ID.', get_class() );
			throw new Exception( $msg );
		}

		$class = get_class( $this );
		$stmt = '
			DELETE FROM `' . constant( $class . '::TABLE' ) . '`
			WHERE ' . constant( $class . '::TABLE_ID_FIELD' ) . ' = :id
		';
		$params = array(
			':id' => $this->id
		);
		$result = ae_Database::query( $stmt, $params );

		return ( $result !== FALSE );
	}


	/**
	 * Get the ID.
	 * @return {int} ID.
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * Get the last inserted ID of the DB table for this model.
	 * @return {int|boolean} The ID on success, FALSE on failure.
	 */
	public function getLastInsertedId() {
		$class = get_class( $this );
		$stmt = '
			SELECT DISTINCT LAST_INSERT_ID() as id
			FROM `' . constant( $class . '::TABLE' ) . '`
		';
		$result = ae_Database::query( $stmt );

		if( $result === FALSE ) {
			return FALSE;
		}

		return (int) $result[0]['id'];
	}


	/**
	 * Load a model with the given ID.
	 * @param  {int}     $id ID of the user to load.
	 * @return {boolean}     TRUE, if loading succeeded, FALSE otherwise.
	 */
	public function load( $id ) {
		$modelData = $this->loadModelData( $id );

		if( $modelData === FALSE ) {
			return FALSE;
		}

		$this->loadFromData( $modelData );

		return TRUE;
	}


	/**
	 * Load the model data for the given ID.
	 * @param  {int}           $id ID of the model to load.
	 * @return {boolean|array}     FALSE, if loading failed, an array with the DB data otherwise.
	 */
	protected function loadModelData( $id ) {
		$this->setId( $id );

		$class = get_class( $this );
		$stmt = '
			SELECT *
			FROM `' . constant( $class . '::TABLE' ) . '`
			WHERE ' . constant( $class . '::TABLE_ID_FIELD' ) . ' = :id
		';
		$params = array(
			':id' => $id
		);
		$result = ae_Database::query( $stmt, $params );

		if( $result === FALSE || empty( $result ) ) {
			return FALSE;
		}

		return $result[0];
	}


	/**
	 * Set the ID.
	 * @param  {int}       $id New ID.
	 * @throws {Exception}     If $id is not valid.
	 */
	public function setId( $id ) {
		if( !ae_Validate::id( $id ) ) {
			$msg = sprintf( '[%s] Not a valid ID: %s', get_class(), htmlspecialchars( $id ) );
			throw new Exception( $msg );
		}

		$this->id = (int) $id;
	}


}
