# 🎉 Echo Eventus

A fullstack project for event management.

---

## 🧰 Technologies Used & Tested Versions

- [Docker](https://www.docker.com/) (v26.1.3)
- [Docker Compose](https://docs.docker.com/compose/) (v2.35.1)
- [Linux: Ubuntu](https://ubuntu.com/download) (v24.04.2 LTS)

---

## 🛠️ How to Run

### 1. Clone the repository

```bash
git clone https://github.com/vnstech/echo-eventus.git
```
or

```bash
git clone git@github.com:vnstech/echo-eventus.git
```
```bash
cd echo-eventus
```

### 2. Set environment variables

```bash
cp .env.example .env
```

### 3. Install dependencies

```bash
./run composer install
```

### 4. Start the containers

```bash
docker compose up -d
```
or

```bash
./run up -d
```

### 5. Create database and tables

```bash
./run db:reset
```

### 6. Populate the database

```bash
./run db:populate
```

### 7. Fix uploads folder permission

```bash
sudo chown www-data:www-data public/assets/uploads
```

---

## 🧪 Running Tests

### Unit tests

```bash
docker compose run --rm php ./vendor/bin/phpunit tests --color
```
or

```bash
./run test
```

### Browser tests

```bash
./run test:browser
```

### Run all tests

```bash
./run all-tests
```

---

## 🧹 Linters

- [PHPCS](https://github.com/PHPCSStandards/PHP_CodeSniffer/)

```bash
./run phpcs
```

- [PHPStan](https://phpstan.org/)

```bash
./run phpstan
```

---

## 🌐 Access the application

[http://localhost](http://localhost)

---

## 📡 API Tests

### Unauthenticated route

```bash
curl -H "Accept: application/json" localhost
```

### Authenticated route

> Change the `PHPSESSID` value to your session ID.

```bash
curl -H "Accept: application/json" -b "PHPSESSID=YOUR_SESSION_ID" localhost/events
```

---

## 🗑️ Remove all containers, images, volumes, and vendor

```bash
./run remove-all
```

## 🗂️ Access Database container

```bash
docker exec -it echo-eventus-db-1 /bin/sh
```

```bash
mysql -u root -p
```

```bash
empty password
```

```bash
USE echo-eventus_development;
```
---
