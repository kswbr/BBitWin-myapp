GCP_PROJECT=`gcloud config get-value project`
SHA=`git rev-parse HEAD`

docker build -f php-fpm/Dockerfile -t asia.gcr.io/${GCP_PROJECT}/bbitwin_php-fpm:${SHA} . --no-cache=true
cd ./nginx && docker build -f Dockerfile -t asia.gcr.io/${GCP_PROJECT}/bbitwin_nginx:${SHA} .

gcloud docker -- push asia.gcr.io/${GCP_PROJECT}/bbitwin_php-fpm:${SHA}
gcloud docker -- push asia.gcr.io/${GCP_PROJECT}/bbitwin_nginx:${SHA}
