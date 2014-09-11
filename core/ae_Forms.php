<?php

class ae_Forms {


	/**
	 * Get an HTML select for the months.
	 * @param  {string}  $selectName Value for the HTML name attribute.
	 * @param  {int}     $preselect  Month to preselect. Defaults to the current month.
	 * @return {string}              HTML select.
	 */
	static public function monthSelect( $selectName, $preselect = FALSE ) {
		$preselect = ( $preselect === FALSE ) ? date( 'm' ) : $preselect;
		$months = array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' );

		$out = '<select name="' . htmlspecialchars( $selectName ) . '">' . PHP_EOL;

		for( $i = 1; $i <= 12; $i++ ) {
			$sel = ( $i == $preselect ) ? ' selected' : '';
			$out .= sprintf( "\t" . '<option value="%d"%s>%s</option>' . PHP_EOL,
				$i, $sel, $months[$i - 1] );
		}

		$out .= '</select>' . PHP_EOL;

		return $out;
	}


}
