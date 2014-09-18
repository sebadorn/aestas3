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
			throw new Exception( '[' . get_class() . '] Cannot delete model. No valid ID.' );
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
	 * Generate a permalink from the title.
	 * @param  {string} $title Title to convert into a permalink.
	 * @return {string}        The permalink.
	 */
	static public function generatePermalink( $title ) {
		$search = array( 'ä', 'ö', 'ü', 'ß' );
		$replace = array( 'ae', 'oe', 'ue', 'ss' );

		$permalink = strtolower( $title );
		$permalink = str_replace( $search, $replace, $permalink );
		$permalink = preg_replace( '/<[\/]?[a-z0-9]+>/i', '', $permalink );
		$permalink = preg_replace( '/[^a-zA-Z0-9-+]/', '-', $permalink );
		$permalink = preg_replace( '/[-]+/', '-', $permalink );

		return $permalink;
	}


	/**
	 * Get the ID.
	 * @return {int} ID.
	 */
	public function getId() {
		return $this->id;
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
			throw new Exception( '[' . get_class() . '] Not a valid ID: ' . htmlspecialchars( $id ) );
		}

		$this->id = $id;
	}


}
