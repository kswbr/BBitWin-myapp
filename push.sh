if [ $# -ne 1 ]; then
GCP_PROJECT=`gcloud config get-value project`
else
GCP_PROJECT=$1
fi

SHA=`git rev-parse HEAD`

gcloud docker -- push asia.gcr.io/${GCP_PROJECT}/bbitwin_php-fpm:${SHA}
gcloud docker -- push asia.gcr.io/${GCP_PROJECT}/bbitwin_nginx:${SHA}
