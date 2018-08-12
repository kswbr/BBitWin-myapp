SHA=`git rev-parse HEAD`

rsync -av --delete src dist --exclude="test_site"

cd dist/src && npm run prod && rm -rf node_modules
