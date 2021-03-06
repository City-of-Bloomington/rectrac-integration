#!/bin/bash
RELEASE_NAME=RecTrac
DIR=`pwd`
BUILD=$DIR/build
DIST=$DIR/dist

if [ ! -d $BUILD ]
	then mkdir $BUILD
fi

if [ ! -d $DIST ]
	then mkdir $DIST
fi

cd $DIR/public/css
./build_css
cd $DIR

# The PHP code does not need to actually build anything.
# Just copy all the files into the build
rsync -rlv --exclude-from=$DIR/buildignore --delete $DIR/ $BUILD/

# Create a distribution tarball of the build
tar czvf $DIST/$RELEASE_NAME.tar.gz --transform=s/build/$RELEASE_NAME/ build
