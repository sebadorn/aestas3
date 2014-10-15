<?php

class ae_CommentfilterList extends ae_List {


	const ITEM_CLASS = 'ae_CommentfilterModel';

	static protected $defaultFilter = array(
		'LIMIT' => '0, 50',
		'ORDER BY' => 'cf_id ASC'
	);


	/**
	 * Constructor.
	 * Fetches all comment filters from the DB.
	 * @param {array}   $filter     Array of filters to apply to the MySQL statement. (Optional.)
	 * @param {boolean} $countItems If TRUE, extends the DB query to count the number of items. (Optional, defaults to TRUE.)
	 */
	public function __construct( $filter = array(), $params = array(), $countItems = TRUE ) {
		parent::__construct( self::ITEM_CLASS, $filter, $params, self::$defaultFilter, $countItems );
	}


	/**
	 * Apply a filter action on a comment.
	 * @param  {string}          $action The action to perform.
	 * @param  {ae_CommentModel} $co     The comment to apply the action on.
	 * @return {boolean}                 TRUE, if the comment shall be saved, FALSE otherwise.
	 */
	static protected function applyAction( $action, ae_CommentModel $co ) {
		switch( $action ) {

			case ae_CommentfilterModel::ACTION_APPROVE:
				$co->setStatus( ae_CommentModel::STATUS_APPROVED );
				$keep = TRUE;
				break;

			case ae_CommentfilterModel::ACTION_UNAPPROVE:
				$co->setStatus( ae_CommentModel::STATUS_UNAPPROVED );
				$keep = TRUE;
				break;

			case ae_CommentfilterModel::ACTION_SPAM:
				$co->setStatus( ae_CommentModel::STATUS_SPAM );
				$keep = TRUE;
				break;

			case ae_CommentfilterModel::ACTION_TRASH:
				$co->setStatus( ae_CommentModel::STATUS_TRASH );
				$keep = TRUE;
				break;

			case ae_CommentfilterModel::ACTION_DROP:
				$co->setStatus( ae_CommentModel::STATUS_TRASH );
				$keep = FALSE;
				break;

			default:
				$keep = TRUE;

		}

		return $keep;
	}


	/**
	 * Apply all loaded filters on a given comment.
	 * @param  {ae_CommentModel} $co Comment to apply the filters on.
	 * @return {boolean}             TRUE, if the comment shall be saved, FALSE otherwise.
	 */
	public function applyFilters( ae_CommentModel $co ) {
		foreach( $this->items as $cf ) {
			$target = self::getTarget( $cf->getMatchTarget(), $co );

			if( preg_match( $cf->getMatchRule(), $target ) >= 1 ) {
				if( !self::applyAction( $cf->getAction(), $co ) ) {
					reset( $this->items );

					return FALSE;
				}
			}
		}

		reset( $this->items );

		return TRUE;
	}


	/**
	 * Get the content of the filter target.
	 * @param  {string}          $cfTarget Target identifier.
	 * @param  {ae_CommentModel} $co       The comment.
	 * @return {string}                    Target content.
	 */
	static protected function getTarget( $cfTarget, ae_CommentModel $co ) {
		switch( $cfTarget ) {

			case ae_CommentfilterModel::TARGET_CONTENT:
				$target = $co->getContent();
				break;

			case ae_CommentfilterModel::TARGET_EMAIL:
				$target = $co->getAuthorEmail();
				break;

			case ae_CommentfilterModel::TARGET_IP:
				$target = $co->getAuthorIp();
				break;

			case ae_CommentfilterModel::TARGET_NAME:
				$target = $co->getAuthorName();
				break;

			case ae_CommentfilterModel::TARGET_URL:
				$target = $co->getAuthorUrl();
				break;

			case ae_CommentfilterModel::TARGET_USERID:
				$target = $co->getUserId();
				break;

			default:
				$target = NULL;

		}

		return $target;
	}


}
