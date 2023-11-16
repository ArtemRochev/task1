Start containers:
```
docker-compose up -d
```
Enter to PHP container: ``docker exec -ti php bash`` and run:

```
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load -n
```

Login (its return JWT token):
```
GET http://localhostc

{
    "username": "user1",
    "password": "123"
}
```

API methods (attach auth header `Authorization: Bearer {token}`):

```
http://localhost/task/new
http://localhost/task/1/done
http://localhost/task
```
