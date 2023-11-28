Start containers:
```
cd docker && docker-compose up -d
```
Enter to PHP container: ``docker exec -ti php bash`` and run:

```
composer install
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load -n
```

Login (its return JWT token):
```
POST http://localhost/login_check

{
    "username": "user1",
    "password": "123"
}
```

API methods (attach auth header `Authorization: Bearer {token}`):

```
GET  http://localhost/task
POST http://localhost/task/new
POST http://localhost/task/1/done
```
