# Change Log

## [1.0.1](https://github.com/heximcz/routerboard-backup/releases/tag/1.0.1)
- update vendor - Symfony framework
- add support for gitlab base64 file decode (rb:decode -f filepath) [issue #9](https://github.com/heximcz/routerboard-backup/issues/9)

## [1.0.0](https://github.com/heximcz/routerboard-backup/releases/tag/1.0.0)
- update vendor - Symfony framework, Gitlab API
- full support Gitlab API V4

## [0.9.5](https://github.com/heximcz/routerboard-backup/releases/tag/0.9.5)
- update vendor - new PHPMailer and Symfony framework

## [0.9.4](https://github.com/heximcz/routerboard-backup/releases/tag/0.9.4)
- security update PHPMailer (CVE-2016-10033, CVE-2016-10045) and other vendor


## [0.9.3](https://github.com/heximcz/routerboard-backup/releases/tag/0.9.3)
- fixed [#3](https://github.com/heximcz/routerboard-backup/issues/3) Email in gitlab/username is not allowed
- **NEW:** the number of backups rotation has been moved into the config file (update your config.yml if you need change this value, default is still 5)


## [0.9.2](https://github.com/heximcz/routerboard-backup/releases/tag/0.9.2)
- fixed when SSH port is NULL in database, use default port from config file

## 0.9.0
- **NEW:** support for override default SSH port from config.yml (-i 192.168.1.1:2345)
- update Symfony to 2.8.3
- update DIBI to 3.0.3
- fixed some comments of methods
- fixed some camelCase variables
- **IMPORTANT:** added new column in database for SSH port ('port' smallint(5) UNSIGNED DEFAULT NULL) -> [readme](https://github.com/heximcz/routerboard-backup#create-database)

## [0.8.3](https://github.com/heximcz/routerboard-backup/releases/tag/0.8.3)
- fixed better search in the large number of projects. A big thank you to [pcdog](https://github.com/heximcz/routerboard-backup/issues/2) for help.
- fixed check project id without group
- added 'debug' parameter in GitLab section of the config file
 

## [0.8.2](https://github.com/heximcz/routerboard-backup/releases/tag/0.8.2)
- fixed correct identification of recurring project name in a different groups

## [0.8.1](https://github.com/heximcz/routerboard-backup/releases/tag/0.8.1)
- fixed [#1](https://github.com/heximcz/routerboard-backup/issues/1) - Hostname support
- fixed [#2](https://github.com/heximcz/routerboard-backup/issues/2) - GitLab group support
- **!! update your config.yml**
- **!! update MySQL row addr to varchar(255)**


## [0.8](https://github.com/heximcz/routerboard-backup/releases/tag/0.8)
- Add support for backup to GitLab repository
- Any little changes in help texts
- Some classes refactoring and code cleaning
- !! Copy new section 'gitlab:' from config.default.yml to your config.yml

