<?php

class ae_CommentfilterModel extends ae_Model {

	const ACTION_APPROVE = 'approve';
	const ACTION_DROP = 'drop';
	const ACTION_SPAM = 'spam';
	const ACTION_TRASH = 'trash';
	const ACTION_UNAPPROVE = 'unapprove';

	const STATUS_ACTIVE = 'active';
	const STATUS_INACTIVE = 'inactive';

	const TABLE = AE_TABLE_COMMENTFILTERS;
	const TABLE_ID_FIELD = 'cf_id';

	const TARGET_CONTENT = 'content';
	const TARGET_EMAIL = 'email';
	const TARGET_IP = 'ip';
	const TARGET_NAME = 'name';
	const TARGET_URL = 'url';
	const TARGET_USERID = 'userId';

	protected $action = self::ACTION_SPAM;
	protected $match = '';
	protected $name = '';
	protected $status = self::STATUS_ACTIVE;
	protected $target = self::TARGET_IP;


	/**
	 * Constructor.
	 * @param {array} $data Filter data to initialize the model with. (Optional.)
	 */
	public function __construct( $data = array() ) {
		$this->loadFromData( $data );
	}


	/**
	 * Get the filter action.
	 * @return {action} Action.
	 */
	public function getAction() {
		return $this->action;
	}


	/**
	 * Get the filter rule.
	 * @return {string} Regex rule.
	 */
	public function getMatchRule() {
		return $this->match;
	}


	/**
	 * Get the filter target.
	 * @return {string} Target.
	 */
	public function getMatchTarget() {
		return $this->target;
	}


	/**
	 * Get the filter name.
	 * @return {string} Name.
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * Get the filter status.
	 * @return {string} Status.
	 */
	public function getStatus() {
		return $this->status;
	}


	/**
	 * Check, if given status is a valid filter status.
	 * @param  {string}  $status Filter status.
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
		return array( self::STATUS_ACTIVE, self::STATUS_INACTIVE );
	}


	/**
	 * Initialize model from the given data array.
	 * @param {array} $data The model data.
	 */
	protected function loadFromData( $data ) {
		if( isset( $data['cf_id'] ) ) {
			$this->setId( $data['cf_id'] );
		}
		if( isset( $data['cf_name'] ) ) {
			$this->setName( $data['cf_name'] );
		}
		if( isset( $data['cf_target'] ) ) {
			$this->setMatchTarget( $data['cf_target'] );
		}
		if( isset( $data['cf_match'] ) ) {
			$this->setMatchRule( $data['cf_match'] );
		}
		if( isset( $data['cf_action'] ) ) {
			$this->setAction( $data['cf_action'] );
		}
		if( isset( $data['cf_status'] ) ) {
			$this->setStatus( $data['cf_status'] );
		}
	}


	/**
	 * Save the filter to DB. If an ID is set, it will update
	 * the filter, otherwise it will create a new one.
	 * @param  {boolean}   $forceInsert If set to TRUE and an ID has been set, the model will be saved
	 *                                  as new entry instead of updating. (Optional, default is FALSE.)
	 * @return {boolean}                TRUE, if saving is successful, FALSE otherwise.
	 * @throws {Exception}              If $forceInsert is TRUE, but no valid ID is set.
	 */
	public function save( $forceInsert = FALSE ) {
		if( $this->name == '' ) {
			$this->name = 'filter ' . date( 'Y-m-d H:i:s' );
		}

		$params = array(
			':name' => $this->name,
			':target' => $this->target,
			':match' => $this->match,
			':action' => $this->action,
			':status' => $this->status
		);

		// Create new category
		if( $this->id === FALSE && !$forceInsert ) {
			$stmt = '
				INSERT INTO `' . self::TABLE . '` (
					cf_name,
					cf_target,
					cf_match,
					cf_action,
					cf_status
				) VALUES (
					:name,
					:target,
					:match,
					:action,
					:status
				)
			';
		}
		// Create new category with set ID
		else if( $this->id !== FALSE && $forceInsert ) {
			$stmt = '
				INSERT INTO `' . self::TABLE . '` (
					cf_id,
					cf_name,
					cf_target,
					cf_match,
					cf_action,
					cf_status
				) VALUES (
					:id,
					:name,
					:target,
					:match,
					:action,
					:status
				)
			';
			$params[':id'] = $this->id;
		}
		// Update existing one
		else if( $this->id !== FALSE ) {
			$stmt = '
				UPDATE `' . self::TABLE . '` SET
					cf_name = :name,
					cf_target = :target,
					cf_match = :match,
					cf_action = :action,
					cf_status = :status
				WHERE
					cf_id = :id
			';
			$params[':id'] = $this->id;
		}
		else {
			$msg = sprintf( '[%s] Supposed to insert new filter with set ID, but no ID has been set.', get_class() );
			throw new Exception( $msg );
		}

		if( ae_Database::query( $stmt, $params ) === FALSE ) {
			return FALSE;
		}

		// If a new filter was created, get the new ID
		if( $this->id === FALSE ) {
			$this->setId( $this->getLastInsertedId() );
		}

		return TRUE;
	}


	/**
	 * Set the filter action.
	 * @param {string} $action The action to perform if the rule matches.
	 */
	public function setAction( $action ) {
		$actions = array(
			self::ACTION_APPROVE, self::ACTION_DROP, self::ACTION_SPAM,
			self::ACTION_TRASH, self::ACTION_UNAPPROVE
		);

		if( !in_array( $action, $actions ) ) {
			$msg = sprintf( '[%s] Invalid action: %s', get_class(), htmlspecialchars( $action ) );
			throw new Exception( $msg );
		}

		$this->action = $action;
	}


	/**
	 * Set the filter regex rule.
	 * @param  {string}    $rule Regex rule.
	 * @throws {Exception}       If $rule is not a valid regex.
	 */
	public function setMatchRule( $rule ) {
		if( @preg_match( $rule, NULL ) === FALSE ) {
			$msg = sprintf( '[%s] Invalid regex: %s', get_class(), htmlspecialchars( $rule ) );
			throw new Exception( $msg );
		}

		$this->match = $rule;
	}


	/**
	 * Set the filter target.
	 * @param  {string}    $target Target of the filter rule.
	 * @throws {Exception}         If $target is not a valid target.
	 */
	public function setMatchTarget( $target ) {
		$targets = array(
			self::TARGET_CONTENT, self::TARGET_EMAIL, self::TARGET_IP,
			self::TARGET_NAME, self::TARGET_URL, self::TARGET_USERID
		);

		if( !in_array( $target, $targets ) ) {
			$msg = sprintf( '[%s] Invalid target: %s', get_class(), htmlspecialchars( $target ) );
			throw new Exception( $msg );
		}

		$this->target = $target;
	}


	/**
	 * Set filter name.
	 * @param {string} $name Name.
	 */
	public function setName( $name ) {
		$this->name = $name;
	}


	/**
	 * Set filter status.
	 * @param  {string}    $status Filter status.
	 * @throws {Exception}         If $status is not a valid filter status.
	 */
	public function setStatus( $status ) {
		if( !self::isValidStatus( $status ) ) {
			$msg = sprintf( '[%s] Not a valid status: %s', get_class(), htmlspecialchars( $status ) );
			throw new Exception( $msg );
		}

		$this->status = $status;
	}


}
