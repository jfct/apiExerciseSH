cd "laradock"
docker-compose up -d mysql redis nginx
:waittofinish
docker exec laradock_workspace_1 bash -c "cd src/ && composer install"
:waittofinish
docker exec laradock_mysql_1 bash -c "mysql -u root -proot < /docker-entrypoint-initdb.d/createdb.sql"
:waittofinish
docker exec laradock_workspace_1 bash -c "cd src/ && php artisan migrate:fresh && php artisan db:seed"
:waittofinish
docker exec laradock_workspace_1 bash -c "cd src/ && nohup php artisan queue:work --daemon &"
:waittofinish
cd ..
pause