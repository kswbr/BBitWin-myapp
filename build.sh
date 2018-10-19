#!/bin/bash -eu
if [ $# -ne 1 ]; then
GCP_PROJECT=`gcloud config get-value project`
else
GCP_PROJECT=$1
fi

SHA=`git rev-parse HEAD`

docker build -f php-fpm/Dockerfile -t asia.gcr.io/${GCP_PROJECT}/bbitwin_php-fpm:${SHA} .
docker build -f nginx/Dockerfile -t asia.gcr.io/${GCP_PROJECT}/bbitwin_nginx:${SHA} .
