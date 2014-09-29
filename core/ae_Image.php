<?php

class ae_Image {


	protected $source = null;
	protected $target = null;
	protected $type = '';


	/**
	 * Constructor.
	 * Set path and MIME type of the source image.
	 * @param {string} $path File path.
	 * @param {string} $type MIME type.
	 */
	public function __construct( $path, $type ) {
		if( !file_exists( $path ) ) {
			$msg = sprintf( '[%s] File "%s" does not exist.',
					get_class(), htmlspecialchars( $path ) );
			throw new Exception( $msg );
		}

		$type = explode( '/', $type, 2 );
		$this->type = $type[1];

		$this->source = self::loadImage( $path, $this->type );
	}


	/**
	 * Destructor.
	 */
	public function __destruct() {
		if( $this->source != NULL ) {
			imagedestroy( $this->source );
		}
		if( $this->target != NULL ) {
			imagedestroy( $this->target );
		}
	}


	/**
	 * Calculate the new width and height according to the given max width.
	 * @param  {int}   $maxWidth Max width of the new image.
	 * @return {array}           New width and height, and old width and height.
	 */
	protected function calcNewSize( $maxWidth ) {
		$width = imagesx( $this->source );
		$height = imagesy( $this->source );

		$newWidth = min( $width, $maxWidth );
		$ratio = $newWidth / $width;
		$newHeight = $height * $ratio;

		return array( $newWidth, $newHeight, $width, $height );
	}


	/**
	 * Save the created image to the file system.
	 * @param  {string}    $path File path.
	 * @throws {Exception}       If saving the image failed.
	 */
	public function saveFile( $path ) {
		switch( $this->type ) {

			case 'gif':
				$success = imagegif( $this->target, $path );
				break;

			case 'jpeg':
				$success = imagejpeg( $this->target, $path, IMAGE_QUALITY_JPEG );
				break;

			case 'png':
				imagealphablending( $this->target, TRUE );
				imagesavealpha( $this->target, TRUE );
				$success = imagepng( $this->target, $path, IMAGE_COMPRESSION_PNG );
				break;

			default:
				$success = FALSE;

		}

		if( !$success ) {
			$msg = sprintf( '[%s] Failed to save image %s.', get_class(), htmlspecialchars( $path ) );
			throw new Exception( $msg );
		}
	}


	/**
	 * Load an image.
	 * @param  {string}    $path File path of the image to load.
	 * @param  {string}    $type Image type.
	 * @return {resource}        Loaded image.
	 * @throws {Exception}       If loading failed.
	 */
	static protected function loadImage( $path, $type ) {
		switch( $type ) {

			case 'gif':
				$imageSource = imagecreatefromgif( $path );
				break;

			case 'jpeg':
				$imageSource = imagecreatefromjpeg( $path );
				break;

			case 'png':
				$imageSource = imagecreatefrompng( $path );
				break;

			default:
				$imageSource = FALSE;

		}

		if( !$imageSource ) {
			$msg = sprintf(
				'[%s] Could not load image "%s" of type "%s".',
				get_class(), htmlspecialchars( $path ), htmlspecialchars( $type )
			);
			throw new Exception( $msg );
		}

		return $imageSource;
	}


	/**
	 * Resize the image to a given max width.
	 * @param  {int}       $maxWidth Max width after resizing.
	 * @throws {Exception}           If resizing failed.
	 */
	public function resize( $maxWidth ) {
		list( $width, $height, $oldWidth, $oldHeight ) = $this->calcNewSize( $maxWidth );

		$this->target = imagecreatetruecolor( $width, $height );
		imageantialias( $this->target, TRUE );

		$success = imagecopyresized(
			$this->target, $this->source,
			0, 0, 0, 0,
			$width, $height,
			$oldWidth, $oldHeight
		);

		if( !$success ) {
			$msg = sprintf(
				'[%s] Could not resize image "%s" from %dx%d to %dx%d.',
				get_class(), htmlspecialchars( $path ),
				$oldWidth, $oldHeight, $width, $height
			);
			throw new Exception( $msg );
		}
	}


}
