#!/bin/bash
BASEDIR=$(dirname "$0")
CLOSURE="/home/$USER/.closure-compiler-v20141215/compiler.jar"

cd "$BASEDIR"

echo ' ----- ----- -----'
echo ' Building standard theme.'

echo -n ' Compiling LESS to CSS ...'
lessc --clean-css less/style.less css/style.css
echo ' Done.'

echo -n ' Combining and minifying JavaScript files ...'
cat 'js/comment-preview.js' 'js/comment-validate.js' > 'js/tmp.js'
sed -i 's/"use strict";//' 'js/tmp.js'
sed -i '1s/^/"use strict";/' 'js/tmp.js'

java -jar "$CLOSURE" --js 'js/tmp.js' --js_output_file 'js/combined.js' --language_in=ECMASCRIPT5_STRICT
rm 'js/tmp.js'
echo ' Done.'

echo ' All done.'
echo ' ----- ----- -----'
