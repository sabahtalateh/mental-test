# Mental Stack Test

### First configure environment

### Then create a database
`php bin/console doctrine:database:create`

### Then run migrations
`bin/console doctrine:migrations:migrate --no-interaction`

### Create OAuth2 client
`bin/console fos:oauth-server:create-client --grant-type=password --grant-type=refresh_token`
Note `client_id` and `client_secret`

### Run dev server
`php bin/console server:start`

### Then register new user with
```bash
curl -X POST \
  {{url}}/api/v1/register \
  -H 'Content-Type: application/json' \
  -d '{
	"username": "Ivan",
	"email": "ivan@mail.com",
	"password": "123123"
}'
```

### Get OAuth2 Token
```bash
curl -X POST \
  {{url}}/oauth/v2/token \
  -H 'Content-Type: application/json' \
  -d '{
  "grant_type": "password",
  "client_id": "{client_id}",
  "client_secret": "{client_secret}",
  "username": "ivan@mail.com",
  "password": "123123"
}'
```
Not a `token`

### Create task
```bash
curl -X POST \
  {{url}}/api/v1/todo \
  -H 'Authorization: Bearer {token}' \
  -H 'Content-Type: application/json' \
  -d '{
	"title": "lol",
	"description": "kek"
}'
```

### List tasks
```bash
curl -X GET \
  {{url}}/api/v1/todo/list \
  -H 'Authorization: Bearer {token}'
```

### Mark task as done
```bash
curl -X POST \
  {{url}}/api/v1/todo/{task_id} \
  -H 'Authorization: Bearer {token}'
```

### API Endpoints Postman Collection
https://web.postman.co/collections/1576355-bdd50073-497c-4e79-bbff-98a501090c30?workspace=1b4b23a9-967f-4327-8017-46678d2cffb7
