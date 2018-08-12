GCP_PROJECT=`gcloud config get-value project`
SHA=`git rev-parse HEAD`

docker build -f php-fpm/Dockerfile -t asia.gcr.io/${GCP_PROJECT}/bbitwin_php-fpm:${SHA} . --no-cache=true
docker build -f nginx/Dockerfile -t asia.gcr.io/${GCP_PROJECT}/bbitwin_nginx:${SHA} .
cd mysql && docker build -f Dockerfile -t asia.gcr.io/${GCP_PROJECT}/bbitwin_mysql:${SHA} .
