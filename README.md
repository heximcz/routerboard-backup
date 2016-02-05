# Routerboard Backup

## Overview

Ultimate backup of yours mikrotik routerboards.

## Prerequisites

php > 5.6.x

## How to install

 - Connet via SSH to your web server

```sh
$ cd /opt/
$ git clone https://github.com/heximcz/routerboar-backup.git
$ cd /opt/routerboar-backup/
$ git tag -l
$ git checkout tags/<last tag name of stable version>
$ cp ./config.default.yml ./config.yml
$ mkdir -p /var/log/routerboar-backup/
```
 -  do not forget to configure the config.yml

## How to update Bind Manager

```sh
$ cd /opt/routerboar-backup/
$ git pull
$ git tag -l
$ git checkout tags/<last tag name of stable version>
 ```
 - How to finding the tag is that checked out? Simply

```sh
$ git describe --tags
```

## Example Usage

print help:

```php ./routerboar-backup.php```

```php ./routerboar-backup rb:mod -h```

```php ./routerboar-backup rb:backup -h```

## Using via crontab

add this lines to your /etc/crontab: (create backup one per week)

```0 0  * * 6   root /usr/bin/php /opt/routerboar-backup/routerboar-backup.php rb:backup >> /var/log/routerboar-backup/routerboar-backup.log```


License
----

MIT
