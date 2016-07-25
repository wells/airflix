# Airflix

[![Build Status](https://travis-ci.org/wells/airflix.svg)](https://travis-ci.org/wells/airflix)
[![Total Downloads](https://poser.pugx.org/airflix/airflix/d/total.svg)](https://packagist.org/packages/airflix/airflix)
[![Latest Stable Version](https://poser.pugx.org/airflix/airflix/v/stable.svg)](https://packagist.org/packages/airflix/airflix)
[![License](https://poser.pugx.org/airflix/airflix/license.svg)](https://packagist.org/packages/airflix/airflix)

Airflix is a web application for browsing and playing movies and TV shows from a local home server. The overall goal of the project is to provide a beautiful HTTP interface to AirPlay or Chromecast media onto your TV screen from a phone or tablet.

![Airflix Demo](https://raw.githubusercontent.com/wells/airflix/master/public/airflix-demo.gif)

## Installation

You can download Airflix either through GitHub or composer.

### Via GitHub

```
$ git clone --depth=1 git@github.com:wells/airflix.git airflix.local
```

### Via Composer Create-Project

You may also install Airflix by issuing the Composer create-project command in your terminal:

```
$ composer create-project --prefer-dist airflix/airflix airflix.local
```

### Recommended Servers

We recommend that you use the following servers to run Airflix.

- [nginx web server](https://www.nginx.com/)
- [MariaDB database server](https://mariadb.com/)
- [Redis server](http://redis.io/)

### Create a Database

We recommend you use a database with `utf8mb4` encoding and `utf8mb4_unicode_520_ci` collation. Make sure you also update your `.env` file with your database credentials and other configuration options.

### Apply for an API Token

Airflix requires an API token from [themoviedb.org](https://www.themoviedb.org/) to gather information and images. You will need to create an account and apply there to acquire a key for access to this API.

### Configuration

Once you have an API key ready to use, you can simply run `php artisan airflix:install` to run migrationes, configure your folders, and enter API keys. This command will also perform an initial scan of your movies and TV shows folders for content, which will take time on the first run.

## Documentation

Airflix has [api documentation](http://docs.airflix.apiary.io/).

### Folders

The folders that contain movies and TV shows follow certain naming conventions. 

Each movie is contained inside a folder named with either the movie title (i.e. `/Films/Serenity/Serenity.m4v`) or the movie title with the release year in parenthesis (i.e. `/Films/Avatar (2009)/Avatar (2009).m4v`).

TV Shows are contained inside a folder with a similar naming convention to a movie, except each episode file has a `S##E##.m4v` naming format (i.e. `/TV Shows/Stargate Atlantis/S01E01.m4v`).

If you use a different file extension from `m4v` (i.e. `mp4`) for your video files, you can edit the `AIRFLIX_EXTENSIONS_VIDEO` in your `.env` file.

### Jobs

We recommend you run `redis-server` on your home server so that Airflix can queue up jobs. This is required for the Settings page to function properly.

Please see the [Laravel documentation](https://laravel.com/docs/5.2/queues#running-the-queue-listener) to learn how to setup a queue daemon. We recommend limiting to one `queue:work` process since most of the jobs are synchronous in nature. 

We have provided a sample `/etc/supervisor/conf.d/airflix-worker.conf` file below:

```
[program:airflix-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /srv/www/airflix.local/current/artisan queue:work redis --sleep=3 --tries=3 --daemon
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/srv/www/airflix.local/storage/logs/airflix-worker.log
```

### Schedule

Airflix has a number of scheduled tasks to refresh your folder list on a weekly basis. Just add something like the following into your server's cron:

```
* * * * * php /srv/www/airflix.local/current/artisan schedule:run >> /dev/null 2>&1
```

### Commands

We have provided a number of Laravel Artisan commands including `airflix:history`, `airflix:install`, `airflix:folders`, `airflix:genres`, `airflix:movies`, `airflix:shows`, and `airflix:keys`. For more information about each command, you can run `php artisan help <command>` to find out what options are available for each command.

## Testing

``` bash
$ phpunit
```

## License

Airflix is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
