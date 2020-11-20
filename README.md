# moviedb-php

Movie database using TMDB API and Slim PHP framework

-----

### Install

- Copy example `.env.example` file to a file named `.env`

- Edit the new `.env` file with your API key. Save and close.

- Install PHP dependencies with Composer

```
$ composer install
```

- Point your web server's root path to `/path/to/moviedb-php/public`

- Check owner/permissions of `/path/to/moviedb-php`

```
$ sudo chown -R www-data:web /path/to/moviedb-php/
$
$ sudo chmod -R 775 /path/to/moviedb-php/
```

- Start or restart your webserver!

-----

### Database

- Create table `users`

```
CREATE TABLE "users" ( "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE, "name" TEXT NOT NULL UNIQUE, "email" TEXT NOT NULL UNIQUE, "password" TEXT NOT NULL, "avatar_url" TEXT DEFAULT '/images/default_avatar.jpg', "is_admin" INTEGER NOT NULL DEFAULT 0, "created_at" TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP, "updated_at" TEXT DEFAULT CURRENT_TIMESTAMP )
```

- Indices

```
CREATE UNIQUE INDEX "user_emails" ON "users" ( "email" ASC )
CREATE UNIQUE INDEX "user_id" ON "users" ( "id" DESC )
CREATE UNIQUE INDEX "user_names" ON "users" ( "name" ASC )
```

-----

### Other

By: Turbo

