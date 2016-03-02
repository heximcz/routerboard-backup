# Change Log

##0.9.0-dev
- NEW: support for override default SSH port from config.yml
- fixed some comments of methods
- fixed some camelCase variables
- IMPORTANT: added new column in database for SSH port ('port' smallint(5) UNSIGNED DEFAULT NULL)

##[0.8.3](https://github.com/heximcz/routerboard-backup/releases/tag/0.8.3)
- fixed better search in the large number of projects. A big thank you to [pcdog](https://github.com/heximcz/routerboard-backup/issues/2) for help.
- fixed check project id without group
- added 'debug' parameter in GitLab section of the config file
 

##[0.8.2](https://github.com/heximcz/routerboard-backup/releases/tag/0.8.2)
- fixed correct identification of recurring project name in a different groups

##[0.8.1](https://github.com/heximcz/routerboard-backup/releases/tag/0.8.1)
- fixed [#1](https://github.com/heximcz/routerboard-backup/issues/1) - Hostname support
- fixed [#2](https://github.com/heximcz/routerboard-backup/issues/2) - GitLab group support
- **!! update your config.yml**
- **!! update MySQL row addr to varchar(255)**


##[0.8](https://github.com/heximcz/routerboard-backup/releases/tag/0.8)
- Add support for backup to GitLab repository
- Any little changes in help texts
- Some classes refactoring and code cleaning
- !! Copy new section 'gitlab:' from config.default.yml to your config.yml

