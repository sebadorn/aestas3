#!/bin/sh

BASEDIR=$(dirname $0)

/opt/lampp/bin/php /usr/local/bin/phpunit --bootstrap $BASEDIR/bootstrap.php --colors $BASEDIR/
