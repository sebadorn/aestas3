<?php

class ae_SiteBuilder {


	protected $basePath = '';


	/**
	 * Constructor.
	 */
	public function __construct() {
		//
	}


	/**
	 * Build the links for a page navigation.
	 * @param  {int}    $numPages      Total number of pages.
	 * @param  {int}    $currentPage   The currently displayed page. Index starting at 0.
	 * @param  {string} $linkBase      Base for the links. The page index will be added at the end.
	 * @param  {int}    $numLinkAtOnce How many numbered links to display at once. (Optional, default is 7.)
	 * @return {string}                HTML. Links to the pages.
	 */
	static public function pagination( $numPages, $currentPage, $linkBase, $numLinksAtOnce = 7, $startAt = 0 ) {
		$out = '<a class="page-offset jump-first-page" href="' . $linkBase . $startAt . '" title="first page">«</a>';

		$offset = floor( $numLinksAtOnce / 2 );
		$start = max( $currentPage - $offset, $startAt );
		$end = min( $start + $numLinksAtOnce, $numPages );
		$start -= $numLinksAtOnce - ( $end - $start );
		$start = max( $start, $startAt );

		for( $i = $start; $i < $end; $i++ ) {
			$status = ( $i == $currentPage ) ? ' current-offset' : '';
			$out .= '<a class="page-offset' . $status . '" href="' . $linkBase . $i . '">';
			$out .= ( $i - 1 + $startAt ) . '</a>';
		}

		$out .= '<a class="page-offset jump-last-page" href="' . $linkBase . ( $numPages - 1 + $startAt ) . '" title="last page">»</a>';

		return $out;
	}


	/**
	 * Render a template.
	 * @param {string} $template Path to file.
	 * @param {mixed}  $data     Data to make available.
	 */
	public function render( $template, $data = NULL ) {
		if( !include( $this->basePath . $template ) ) {
			$msg = sprintf(
				'[%s] Failed to include file <code>"%s"</code>.',
				get_class(), htmlspecialchars( $this->basePath . $template )
			);
			ae_Log::error( $msg );
		}
	}


	/**
	 * Set the base path for includes.
	 * @param {string} $path Base path.
	 */
	public function setBasePath( $path ) {
		if( mb_strlen( $path ) > 0 && mb_substr( $path, -1 ) != '/' ) {
			$path .= '/';
		}

		$this->basePath = $path;
	}


}