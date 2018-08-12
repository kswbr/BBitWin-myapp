cd ../laradock && docker exec -it laradock_mysql_1 mysqldump -u root -p localdb oauth_clients > ../myapp/mysql/oauth_clients.sql
