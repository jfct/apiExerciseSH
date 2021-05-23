#!/bin/bash
cd laradock
docker-compose up -d mysql redis nginx
docker exec laradock_workspace_1 bash -c "cd src/ && composer install"