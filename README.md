# Api Exercise

Tools used:
- Laradock (For docker packages)
- Lumen/PHP (API framework)
- Redis (Message broker/queue system)
- nginx
- mySQL
- JWT (authentication)


## Notes
 - From the proposed features/bonus I am missing Kubernetes.
 - First install might be a bit slow due to the images from Laradock
 - On creating a new Task using the create endpoint the API fires an event which dispatches a job to the REDIS queue, this job is then run to simulate the notification. In this case i am just printing something to the log located in: 

   ```<rootFolder>/src/storage/logs/lumen-<year>-<month>-<day>.log```
- Premade users for the API for testing if needed:

 ```
API:
user: test_manager
pass: teste1

user: test_technician
pass: teste2

DB:
user: user
user: qi#i4F!#
```


## Installation (scripts easy way)

The easy way is to run the scripts on the root


1. Unpack the projectAPI folder
2. Inside the folder you will have a few scripts you can run


```
#(windows cmd)
setup.bat 

#(linux - didn't test this one)
setup.sh 

```



## If the scripts don't work (hard way)

1. Navigate to the laradock folder
2. Running docker ```docker-compose up -d mysql redis nginx```
3. Installing composer dependencies```docker exec laradock_workspace_1 bash -c "cd src/ && composer install"```
4. Creating DB ```docker exec laradock_mysql_1 bash -c "mysql -u root -proot < /docker-entrypoint-initdb.d/createdb.sql"```
5. Populating DB with base users/attributes ```docker exec laradock_workspace_1 bash -c "cd src/ && php artisan migrate:fresh && php artisan db:seed"```
6. Running deamon to work queue (Can exist after running)```docker exec laradock_workspace_1 bash -c "cd src/ && nohup php artisan queue:work --daemon &"```

# Application

Simple API with several services

### example
```localhost/api/...```

---
### login -> ```localhost/api/login```
  - login - Just a way to authenticate and get the JWT auth token

Params: ```name ``` ```password ``` 


```localhost/api/login?name=test_manager&password=teste1```

---

### register-> ```localhost/api/register```

  - POST ```manager```- Just a way to authenticate and get the JWT auth token

Params: ```name ``` ```password ``` 

  - POST ```technician```- Just a way to authenticate and get the JWT auth token

Params: ```name ``` ```password ``` 

---


### tasks -> ```localhost/api/tasks```

  * GET ```/``` - Returns all tasks (only available for manager type users)


  * GET ```{taskId}``` - Returns a specific taskId

Params: ``` taskId ```

  * GET ```user/{taskId}``` - Returns all tasks for a specific User (only for managers or if the user searches for himself)

Params: ``` taskId ```

  * POST ```create``` - Create a task

Params: ```summary``` ```date``` 

  * PUT ```{taskid}``` - Update a task

Params: ``` taskId ```

  * DELETE ```{taskid}``` - Delete a task

Params: ``` taskId ```

---

### users -> ```localhost/api/users```

  * GET ```technicians``` - Returns all technician users


  * GET ```{userId}``` - Returns a specific User

Params: ```userId```



# Tests

## Running tests (easy way)

In the root of the folder, near the setup.bat there's a tests.bat that can be run directly.
There's also the tests.sh but i have not tested it

## If the easy way doesn't work

1. Navigate to the laradock folder
2. ```docker-compose exec workspace bash```
3. ```cd src/```
4. ```vender/bin/phpunit```


