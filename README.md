# Mikrotik Routerboard Backup

[![Latest Stable Version](https://poser.pugx.org/routerboard-backup/routerboard-backup/v/stable)](https://github.com/heximcz/routerboard-backup/releases)
[![Build Status](https://travis-ci.org/heximcz/routerboard-backup.svg?branch=master)](https://travis-ci.org/heximcz/routerboard-backup)
[![Latest Unstable Version](https://poser.pugx.org/routerboard-backup/routerboard-backup/v/unstable)](https://github.com/heximcz/routerboard-backup)
[![License](https://poser.pugx.org/routerboard-backup/routerboard-backup/license)](https://github.com/heximcz/routerboard-backup/blob/master/LICENSE.md)
[![codecov.io](https://codecov.io/github/heximcz/routerboard-backup/coverage.svg?branch=master)](https://codecov.io/github/heximcz/routerboard-backup?branch=master)

## Overview

Ultimate backup of your mikrotik routerboards.

## Prerequisites

PHP > 5.6.x

## Features

* Auto generate RSA key if it does not exist.
* Create a new account for backup on a routerboard with a public key
* Get routerboard identity and save IP address along with this one to database
* When backing up the routerboard, delete the old backup  to create new ones . Only the current backup will remain on the routerboard; it will not fill the disk with the old backup.
* Create standard .backup and .rsc backup files in the form of a script
* Your backups are stored on a local disk to the directories; each have in their name an identity and IP address of the backed-up device.
* Your backups on the local disk are automatically replaced with the current backup. Only the last five backups remain. You will never have a full disk, even after many years to come.
* If an error occurs while backing up, an email will be sent automatically 

## Recommended
* For this backup system we recommend version **RouterOS 6.32.3**, because new versions have some problems with file transfer via SCP.
* **Fixed in RouterOS 6.35rc5**

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
 -  **do not forget to configure the config.yml**

## Create database

```sh

CREATE TABLE IF NOT EXISTS `routers` (
  `id` int(11) NOT NULL,
  `addr` char(15) COLLATE utf8_bin NOT NULL COMMENT 'IP address',
  `identity` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT 'System identity',
  `created` datetime NOT NULL,
  `modify` datetime DEFAULT NULL,
  `lastbackup` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE `routers`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `routers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

```

## How to update Routerboard Backup

```sh
$ cd /opt/routerboard-backup/
$ git pull
$ git tag -l
$ git checkout tags/<last tag name of stable version>
 ```
 - How simply find out how to check the tag

```sh
$ git describe --tags
```

## Example Usage

print help:

```php ./routerboard-backup.php```

```php ./routerboard-backup rb:mod -h```

```php ./routerboard-backup rb:backup -h```

```php ./routerboard-backup rb:list -h```

## Using via crontab

add this line to your /etc/crontab: (backup of all in database one per week)

```0 0  * * 6   root /usr/bin/php /opt/routerboard-backup/routerboard-backup.php rb:backup >> /var/log/routerboard-backup/routerboard-backup.log```

## Logrotate script

```nano /etc/logrotate.d/routerboard-backup```


```sh
/var/log/routerboard-backup/*.log
{
	rotate 5
	monthly
	compress
	missingok
	notifempty
}
```

##Product microsite

[https://routerboard-backup.best-hosting.cz/](https://routerboard-backup.best-hosting.cz/)

## License

MIT
