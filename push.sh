GCP_PROJECT=`gcloud config get-value project`
SHA=`git rev-parse HEAD`

gcloud docker -- push asia.gcr.io/${GCP_PROJECT}/bbitwin_php-fpm:${SHA}
gcloud docker -- push asia.gcr.io/${GCP_PROJECT}/bbitwin_nginx:${SHA}
gcloud docker -- push asia.gcr.io/${GCP_PROJECT}/bbitwin_mysql:${SHA}
