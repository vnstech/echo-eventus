# Echo Eventus

### Dependencies

- Docker
- Docker Compose

### To run

#### Clone Repository

```
git clone https://github.com/vnstech/echo-eventus.git
or
git@github.com:vnstech/echo-eventus.git
cd echo-eventus
```

#### Define the env variables

```
cp .env.example .env
```

#### Install the dependencies

```
./run composer install
```

#### Up the containers

```
docker compose up -d
```

ou

```
./run up -d
```

#### Create database and tables

```
./run db:reset
```

#### Populate database

```
./run db:populate
```

### Fixed uploads folder permission

```
sudo chown www-data:www-data public/assets/uploads
```

#### Run the tests

```
docker compose run --rm php ./vendor/bin/phpunit tests --color
```

or

```
./run test
```

#### Run the linters

[PHPCS](https://github.com/PHPCSStandards/PHP_CodeSniffer/)

```
./run phpcs
```

[PHPStan](https://phpstan.org/)

```
./run phpstan
```

Access [localhost](http://localhost)

### API tests

#### Unauthenticated route

```shell
curl -H "Accept: application/json" localhost/problems
```

#### Authenticated route

In this case you need to change the PHPSESSID value according to your session ID.

```shell
curl -H "Accept: application/json" -b "PHPSESSID=5f55f364a48d87fb7ef9f18425a8ae88" localhost/problems
```

#### Remove all container images volumes from your computer and also vendor

```
./run remove-all
```

### Other Tests:

#### Browser tests:

```
./run test:browser
```

