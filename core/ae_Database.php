<?php

class ae_Database {


	static protected $numQueries = 0;
	static protected $pdo = NULL;


	/**
	 * Close connection (or at least try to).
	 */
	static public function close() {
		self::$pdo = NULL;
	}


	/**
	 * Connect to the MySQL database.
	 * @param  {array}   $dbInfo Database information.
	 * @return {boolean}         TRUE on success, FALSE otherwise.
	 */
	static public function connect( $dbInfo ) {
		$pdoStr = sprintf( 'mysql:host=%s;dbname=%s;charset=utf8', $dbInfo['host'], $dbInfo['name'] );

		try {
			self::$pdo = @new PDO(
				$pdoStr, $dbInfo['username'], $dbInfo['password'],
				array( PDO::ATTR_PERSISTENT => true )
			);
			self::$pdo->exec( 'SET NAMES utf8' );
		}
		catch( PDOException $exc ) {
			$codeMsg = '(error ' . $exc->getCode() . ')';

			switch( $exc->getCode() ) {

				case 1044:
					$codeMsg .= ': No database table found';
					break;

				case 1045:
					$codeMsg .= ': Access denied';
					break;

				case 2002:
					$codeMsg .= ': Host not found';
					break;

			}

			$msg = sprintf( '[%s] Could not connect to database %s.', get_class(), $codeMsg );
			ae_Log::error( $msg );

			return FALSE;
		}

		return TRUE;
	}


	/**
	 * Get the number of executed queries.
	 * @return {int} Number of queries.
	 */
	static public function getNumQueries() {
		return self::$numQueries;
	}


	/**
	 * Get the database server version.
	 * @return {string} Database server version.
	 */
	static public function serverVersion() {
		if( !self::$pdo ) {
			throw new Exception( '[' . get_class() . '] No database connection.' );
		}

		return self::$pdo->getAttribute( PDO::ATTR_SERVER_VERSION );
	}


	/**
	 * Prepare and execute an SQL statement.
	 * @param  {string}        $statement The statement to prepare and execute.
	 * @param  {array}         $params    Parameters for the statement. (Optional.)
	 * @return {array|boolean}            The query result as array or FALSE if an error occured.
	 */
	static public function query( $statement, $params = array() ) {
		$pdoStatement = self::$pdo->prepare( $statement );

		if( !$pdoStatement || $pdoStatement->execute( $params ) === FALSE ) {
			$errorInfo = @$pdoStatement->errorInfo();
			$msg = sprintf(
				'[%s] Statement failed: <code>%s</code>. %s',
				get_class(),
				htmlspecialchars( $statement ),
				@$errorInfo[2]
			);
			ae_Log::error( $msg );

			return FALSE;
		}

		self::$numQueries++;

		return $pdoStatement->fetchAll( PDO::FETCH_ASSOC );
	}


}