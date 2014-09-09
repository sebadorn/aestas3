<?php

require_once( '../../core/autoload.php' );
require_once( '../../config.php' );


ae_Security::logout();

header( 'Location: ../index.php?success=logout' );
