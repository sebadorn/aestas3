<?php

class ae_Forms {


	const INPUT_CHECKBOX = 0;
	const INPUT_RADIO = 1;


	/**
	 * TODO: Nested, to visualize parent-child relationships.
	 * Get a list of all categories, selectable as input.
	 * @param  {string} $name      HTML name attribute for the inputs.
	 * @param  {int}    $inputType Input type: INPUT_CHECKBOX, INPUT_RADIO.
	 * @param  {array}  $preselect Pre-select certain category IDs. (Optional.)
	 * @param  {array}  $omit      IDs to omit. (Optional.)
	 * @return {string}            HTML list with inputs.
	 */
	static public function categories( $name, $inputType, $preselect = array(), $omit = array() ) {
		$filter = array( 'WHERE' => 'ca_status = "' . ae_CategoryModel::STATUS_AVAILABLE . '"' );
		$caList = new ae_CategoryList( $filter );
		$templateInput = "\t\t";
		$templateLabel = "\t\t" . '<label for="ca-%s">%s</label>' . PHP_EOL;

		if( $inputType == self::INPUT_CHECKBOX ) {
			$templateInput .= '<input type="checkbox" name="%s[]" value="%s" id="ca-%s" />' . PHP_EOL;
		}
		else if( $inputType == self::INPUT_RADIO ) {
			$templateInput .= '<input type="radio" name="%s" value="%s" id="ca-%s" />' . PHP_EOL;
		}

		$out = '<ol class="choose-categories">' . PHP_EOL;

		// Only for "radio": default "none" option
		if( $inputType == self::INPUT_RADIO ) {
			$out .= "\t" . '<li>' . PHP_EOL;
			$out .= sprintf( $templateInput, $name, 0, 0 );

			if( count( $preselect ) == 0 || $preselect[0] < 1 ) {
				$out = str_replace( ' />', ' checked />', $out );
			}

			$out .= sprintf( $templateLabel, 0, '<em>none</em>' );
			$out .= "\t" . '</li>' . PHP_EOL;
		}

		// All categories
		while( $ca = $caList->next() ) {
			if( in_array(  $ca->getId(), $omit ) ) {
				continue;
			}

			$out .= "\t" . '<li>' . PHP_EOL;
			$input = sprintf( $templateInput, $name, $ca->getId(), $ca->getId() );

			if( in_array( $ca->getId(), $preselect ) ) {
				$input = str_replace( ' />', ' checked />', $input );
			}

			$out .= $input;
			$out .= sprintf( $templateLabel, $ca->getId(), htmlspecialchars( $ca->getTitle() ) );
			$out .= '</li>' . PHP_EOL;

		}

		$out .= '</ol>' . PHP_EOL;

		return $out;
	}


	/**
	 * Format a size value given in bytes.
	 * Returns a size < 1024 with the correct unit.
	 * @param  {int}    $size Size in bytes.
	 * @return {string}       Formatted size inclusive unit.
	 */
	static public function formatSize( $size ) {
		$names = array( 'Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );
		$numNames = count( $names );
		$i = 0;

		while( $size > 1024 && $i < $numNames ) {
			$size /= 1024;
			$i++;
		}

		return sprintf( '%.2f %s', $size, $names[$i] );
	}


	/**
	 * Get the CSS class for the appropiate icon according to the MIME type.
	 * @param  {string} $mime MIME type.
	 * @return {string}       CSS class.
	 */
	static public function getIcon( $mime ) {
		$mime = explode( '/', $mime );

		switch( $mime[0] ) {

			case 'audio':
				$icon = 'icon-before-audio';
				break;

			case 'image':
				$icon = 'icon-before-image';
				break;

			case 'text':
				$icon = 'icon-before-document';
				break;

			case 'video':
				$icon = 'icon-before-video';
				break;

			default:
				$icon = 'icon-before-file';

		}

		return $icon;
	}


	/**
	 * Get an HTML select for the months.
	 * @param  {string}  $name       Value for the HTML name attribute.
	 * @param  {int}     $preselect  Month to preselect. Defaults to the current month. (Optional.)
	 * @return {string}              HTML select.
	 */
	static public function monthSelect( $name, $preselect = FALSE ) {
		$preselect = ( $preselect === FALSE ) ? date( 'm' ) : $preselect;
		$months = array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' );

		$out = '<select name="' . htmlspecialchars( $name ) . '">' . PHP_EOL;

		for( $i = 1; $i <= 12; $i++ ) {
			$sel = ( $i == $preselect ) ? ' selected' : '';
			$out .= sprintf( "\t" . '<option value="%d"%s>%s</option>' . PHP_EOL,
				$i, $sel, $months[$i - 1] );
		}

		$out .= '</select>' . PHP_EOL;

		return $out;
	}


	/**
	 * Get an HTML select for the page/post comments status.
	 * @param  {string} $name      Value for the HTML name attribute.
	 * @param  {string} $preselect Status to preselect. (Optional.)
	 * @return {string}            HTML select.
	 */
	static public function postCommentsStatus( $name, $preselect = '' ) {
		$out = '<select name="' . $name . '">' . PHP_EOL;

		$statuses = array(
			ae_PageModel::COMMENTS_CLOSED,
			ae_PageModel::COMMENTS_DISABLED,
			ae_PageModel::COMMENTS_OPEN
		);

		foreach( $statuses as $status ) {
			$select = ( $status == $preselect ) ? ' selected' : '';
			$out .= "\t";
			$out .= sprintf(
				'<option value="%s"%s>%s</option>',
				htmlspecialchars( $status ),
				$select,
				htmlspecialchars( $status )
			);
			$out .= PHP_EOL;
		}

		$out .= '</select>' . PHP_EOL;

		return $out;
	}


	/**
	 * Get an HTML select for statuses.
	 * @param  {string} $name     Value for the HTML name attribute.
	 * @param  {array}  $statuses List of statuses.
	 * @return {string}           HTML select.
	 */
	static public function selectStatus( $name, $statuses ) {
		$out = '<select name="' . $name . '" class="select-status">' . PHP_EOL;

		foreach( $statuses as $status ) {
			$out .= '<option value="' . $status . '">' . $status . '</option>' . PHP_EOL;
		}

		$out .= '</select>' . PHP_EOL;

		return $out;
	}


}
