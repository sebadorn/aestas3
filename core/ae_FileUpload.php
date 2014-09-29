<?php

class ae_FileUpload {


	const DIR_MODE = 0755;
	const FILE_MODE = 0644;

	protected $items = array();
	protected $mediaPath = '../media/';


	/**
	 * Constructor.
	 * @param {array} $files Uploaded file(s).
	 */
	public function __construct( $files ) {
		$numItems = count( $files['tmp_name'] );

		for( $i = 0; $i < $numItems; $i++ ) {
			$error = $files['error'][$i];

			// File input in form for which no file has been selected
			if( $error == UPLOAD_ERR_NO_FILE ) {
				continue;
			}

			// Something actually went wrong with an upload
			if( $error != UPLOAD_ERR_OK ) {
				$msg = sprintf(
					'[%s] Upload error for file <code>%s</code>: %s',
					get_class(),
					htmlspecialchars( $files['name'][$i] ),
					self::getUploadErrorString( $error )
				);
				ae_Log::error( $msg );

				$json = str_replace( '\/', '/', json_encode( $files ) );
				ae_Log::debug( 'File ' . $i . ': ' . $json );

				continue;
			}

			$type = self::getMIMEType( $files['tmp_name'][$i], $files['type'][$i] );

			$m = new ae_MediaModel();
			$m->setName( $files['name'][$i] );
			$m->setTmpName( $files['tmp_name'][$i] );
			$m->setDatetime( date( 'Y-m-d H:i:s' ) );
			$m->setType( $type );
			$m->setUserId( ae_Security::getCurrentUserId() );
			$m->setStatus( ae_MediaModel::STATUS_AVAILABLE );
			$m->setMetaInfo( self::getMetaInfo( $m ) );

			$this->items[] = $m;
		}
	}


	/**
	 * Create and save a preview of an uploaded image.
	 * @param {ae_MediaModel} $m The media model of the image.
	 */
	protected function createImagePreview( ae_MediaModel $m ) {
		$path = $this->mediaPath . $m->getFilePath();
		$previewPath = explode( '/', $path );
		array_pop( $previewPath );
		array_push( $previewPath, 'tiny', $m->getName() );
		$previewPath = implode( '/', $previewPath );

		try {
			$img = new ae_Image( $path, $m->getType() );
		}
		catch( Exception $exc ) {
			return FALSE;
		}

		$img->resize( IMAGE_PREVIEW_MAX_WIDTH );
		$img->saveFile( $previewPath );
	}


	/**
	 * Create sub-directories in the media directory.
	 * @param  {string}  $path Path to create.
	 * @return {boolean}       TRUE, on success, FALSE on failure.
	 */
	protected function createMediaSubDirs( $path ) {
		if( file_exists( $path ) ) {
			return TRUE;
		}

		if( !mkdir( $path, self::DIR_MODE, TRUE ) ) {
			$msg = sprintf( '[%s] Failed to create directory: <code>%s</code>',
					get_class(), htmlspecialchars( $path ) );
			ae_Log::error( $msg );

			return FALSE;
		}

		return TRUE;
	}


	/**
	 * Get the created media model items.
	 * @return {array} The media models.
	 */
	public function getItems() {
		return $this->items;
	}


	/**
	 * Get meta info of an uploaded file.
	 * @param  {ae_MediaModel} $m Media model.
	 * @return {array}            Meta data.
	 */
	static public function getMetaInfo( ae_MediaModel $m ) {
		$meta = array();

		$meta['file_size'] = filesize( $m->getTmpName() );

		if( $m->isImage() ) {
			$size = getimagesize( $m->getTmpName() );

			if( $size ) {
				$meta['image_width'] = $size[0];
				$meta['image_height'] = $size[1];
			}
		}

		return $meta;
	}


	/**
	 * Get the file MIME type.
	 * Tries to use the more reliable "fileinfo", but can fall back
	 * to the MIME type from the upload.
	 * @param  {string} $tmpName    Temporary file name.
	 * @param  {string} $uploadType MIME type from the upload.
	 * @return {string}             MIME type.
	 */
	static public function getMIMEType( $tmpName, $uploadType ) {
		// PHP >= 5.3.0 or PECL fileinfo >= 0.1.0
		if( !function_exists( 'finfo_open' ) ) {
			$msg = sprintf( '[%s] Function <code>finfo_open</code> does not exist.', get_class() );
			ae_Log::warning();

			return $uploadType;
		}

		$finfoHandle = finfo_open( FILEINFO_MIME_TYPE );
		$mime = finfo_file( $finfoHandle, $tmpName );
		finfo_close( $finfoHandle );

		return $mime;
	}


	/**
	 * Get the currently set path to the media directory.
	 * This value is NOT determined automatically, but has to be set.
	 * @return {string} Currently set path to the media directory.
	 */
	public function getPathToMediaDir() {
		return $this->mediaPath;
	}


	/**
	 * Turn an upload error code into a text message.
	 * @param  {int}    $error Error code.
	 * @return {string}        Error message.
	 */
	static public function getUploadErrorString( $error ) {
		$text = 'Unknown error.';

		switch( $error ) {

			case UPLOAD_ERR_OK:
				$text = 'No error. (UPLOAD_ERR_OK)';
				break;

			case UPLOAD_ERR_INI_SIZE:
				$text = 'File size exceeds limit defined in php.ini. (UPLOAD_ERR_INI_SIZE)';
				break;

			case UPLOAD_ERR_FORM_SIZE:
				$text = 'File size exceeds limit defined in HTML form. (UPLOAD_ERR_FORM_SIZE)';
				break;

			case UPLOAD_ERR_PARTIAL:
				$text = 'File has only been partially uploaded. (UPLOAD_ERR_PARTIAL)';
				break;

			case UPLOAD_ERR_NO_FILE:
				$text = 'No file was uploaded. (UPLOAD_ERR_NO_FILE)';
				break;

			case UPLOAD_ERR_NO_TMP_DIR:
				$text = 'Missing temporary folder for uploads. (UPLOAD_ERR_NO_TMP_DIR)';
				break;

			case UPLOAD_ERR_CANT_WRITE:
				$text = 'Failed to write file to disk. (UPLOAD_ERR_CANT_WRITE)';
				break;

			case UPLOAD_ERR_EXTENSION:
				$text = 'An unknown PHP extension stopped the file upload. (UPLOAD_ERR_EXTENSION)';
				break;

		}

		return $text;
	}


	/**
	 * Save all uploaded files to the file system.
	 * @return {boolean} TRUE, if all files could be saved/moved, FALSE otherwise.
	 */
	public function saveToFileSystem() {
		foreach( $this->items as $m ) {
			$from = $m->getTmpName();
			$to = $this->mediaPath . $m->getFilePath();
			$path = explode( '/', $to );
			array_pop( $path );
			$path = implode( '/', $path ) . '/tiny';

			$this->createMediaSubDirs( $path );

			// Move file from temporary directory to media directory
			if( !move_uploaded_file( $from, $to ) ) {
				$msg = sprintf( '[%s] Failed to move <code>%s</code> to <code>%s</code>.',
						get_class(), htmlspecialchars( $from ), htmlspecialchars( $to ) );
				ae_Log::error( $msg );

				return FALSE;
			}

			// Set file permissions of moved file
			if( !chmod( $to, self::FILE_MODE ) ) {
				$msg = sprintf( '[%s] Failed to change file mode of <code>%s</code>.',
						get_class(), htmlspecialchars( $to ) );
				ae_Log::error( $msg );

				return FALSE;
			}

			if( $m->isImage() ) {
				$this->createImagePreview( $m );
			}
		}

		return TRUE;
	}


	/**
	 * Save all uploaded file data to the DB.
	 * @return {boolean} TRUE, if all files could be saved, FALSE otherwise.
	 */
	public function saveToDB() {
		foreach( $this->items as $m ) {
			if( !$m->save() ) {
				$msg = sprintf( '[%s] Failed to save <code>%s</code> to the DB.',
						get_class(), htmlspecialchars( $m->getName() ) );
				ae_Log::error( $msg );

				return FALSE;
			}
		}

		return TRUE;
	}


	/**
	 * Set the path to the media directory.
	 * @param  {string}    $path Path to the media directory.
	 * @throws {Exception}       If the directory does not exist.
	 */
	public function setPathToMediaDir( $path ) {
		if( !file_exists( $path ) ) {
			$msg = sprintf( '[%s] Directory does not exist: %s',
					get_class(), htmlspecialchars( $path ) );
			throw new Exception( $msg );
		}

		$this->mediaPath = $path;
	}


}
