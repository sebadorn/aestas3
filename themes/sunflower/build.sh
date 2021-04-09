#!/bin/bash
BASEDIR=$(dirname "$0")

cd "$BASEDIR"

echo ' ----- ----- -----'
echo ' Building standard theme.'

echo -n ' Compiling LESS to CSS ...'
lessc --clean-css less/style.less css/style.css
echo ' Done.'

echo ' All done.'
echo ' ----- ----- -----'
