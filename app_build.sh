cd src && npm run prod && cd -
rsync -av --delete src dist --exclude="test_site" --exclude="node_modules"

