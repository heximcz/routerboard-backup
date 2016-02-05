# Mikrotik Routerboard Backup

## Overview

Ultimate backup of yours mikrotik routerboards.

## Prerequisites

php > 5.6.x

## How to install

 - Connet via SSH to your web server

```sh
$ cd /opt/
$ git clone https://github.com/heximcz/routerboard-backup.git
$ cd /opt/routerboard-backup/
$ git tag -l
$ git checkout tags/<last tag name of stable version>
$ cp ./config.default.yml ./config.yml
$ mkdir -p /var/log/routerboard-backup/
```
 -  do not forget to configure the config.yml

## How to update Bind Manager

```sh
$ cd /opt/routerboard-backup/
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

```php ./routerboard-backup.php```

```php ./routerboard-backup rb:mod -h```

```php ./routerboard-backup rb:backup -h```

## Using via crontab

add this lines to your /etc/crontab: (create backup one per week)

```0 0  * * 6   root /usr/bin/php /opt/routerboard-backup/routerboard-backup.php rb:backup >> /var/log/routerboard-backup/routerboard-backup.log```


License
----

MIT
