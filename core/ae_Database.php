<?php

class ae_Database {


	static protected $pdo = NULL;


	/**
	 * Close connection (or at least try to).
	 */
	static public function close() {
		self::$pdo = NULL;
	}


	/**
	 * Connect to the MySQL database.
	 * @param {array} $dbInfo Database information.
	 */
	static public function connect( $dbInfo ) {
		$pdoStr = sprintf( 'mysql:host=%s;dbname=%s',
			$dbInfo['host'], $dbInfo['name'] );

		try {
			self::$pdo = new PDO(
				$pdoStr, $dbInfo['username'], $dbInfo['password'],
				array( PDO::ATTR_PERSISTENT => true )
			);
		}
		catch( PDOException $exc ) {
			$msg = '[' . get_class() . '] Could not connect to database: ' . $exc->getMessage();
			ae_Log::error( $msg );
		}
	}


	/**
	 * Prepare and execute an SQL statement.
	 * @param  {string}        $statement The statement to prepare and execute.
	 * @param  {array}         $params    Parameters for the statement. (Optional.)
	 * @return {array|boolean}            The query result as array or FALSE if an error occured.
	 */
	static public function query( $statement, $params = array() ) {
		$pdoStatement = self::$pdo->prepare( $statement );

		if( !$pdoStatement || !$pdoStatement->execute( $params ) ) {
			$msg = '[' . get_class() . '] Statement <code>' . $statement . '</code> failed.';
			ae_Log::error( $msg );

			return FALSE;
		}

		return $pdoStatement->fetchAll( PDO::FETCH_ASSOC );
	}


}