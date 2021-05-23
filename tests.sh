#!/bin/bash
cd laradock
docker exec laradock_workspace_1 bash -c "cd src/ && vendor/bin/phpunit"
cd ..