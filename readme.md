## Songslib 0.0.1

This is a test Symfony 5 project for a song library.

It consists of one index to view the song list, and one form to edit the song.
A song has a title, one or more artists, one or more albums. New artists and albums can be added withing the song form.
It can be checked as a "single", in that case an Album with the same title as the song name is created.

### Prerequisites

* php 7.4+
* composer
* git
* mysql

### Dev environment installation

Copy .env file in .env.dev.local:

Change the DATABASE_URL in .env.dev.local:

```ini
DATABASE_URL="mysql://<user>:<password>@localhost:3306/songslib"
```

Change locale (it/en) in .env.dev.local:
```ini
LOCALE=it
```

Install dependencies:

```bash
composer install
yarn install
```

Build database:

```bash
./bin/console doctrine:database:create
./bin/console doctrine:schema:update --force
```

Build assets (dev environment):

```bash
yarn dev
```
