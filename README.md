## Запуск

```
docker-compose up -d
```
При первом запуске выполнить:

```
docker-compose exec backend bin/console doctrine:database:create

docker-compose exec backend doctrine:migrations:migrate
```

Если требуется заполнить данными из Faker выполнить:
```
docker-compose exec backend bin/console doctrine:fixtures:load
```

API доступно по [ссылке](http://localhost:8080/todoitems)

Конфиг коллекции для postman с тестами лежит [тут](./iSpring_todo.postman_collection.json)