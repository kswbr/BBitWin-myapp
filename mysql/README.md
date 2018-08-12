
# OAUTH_CLIENTのダンプをGKEへ転送
kubectl cp oauth_clients.sql  ${PODS}:. -c mysql

# mysqlコンテナへログイン
kubectl exec -it oauth_clients.sql ${PODS}:. -c mysql bash

# あとはmysqlへダンプをコピー
