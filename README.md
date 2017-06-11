## What does it do?

This package provides additional deployer tasks to support TYPO3 CMS. It depends on 
sourcebroker/deployer-extended package which provides some system independent tasks
as database and media synchronistation and request bufferring during time of deploy
to allow 100% zero time deployment.

This package provides a working deploy task so you can use it to deploy TYPO3 projects.

## Installation

### What verions to choose ?

Mind that there is no full semantic versioning because major version is for the TYPO3 version. 
- For TYPO3 7.6 the tags starts with 2.\*.\*
- For TYPO3 8.7 the tags starts with 3.\*.\*
- For TYPO3 9.0 the tags starts with 4.\*.\*

The features and bugfixes will increment the patch version and the change in minor version
will mean breaking change (major.[breaking].[features/bugfixes])

Therefore you should use tilde-range constraints for choosing sourcebroker/deployer-extended-typo3

**For TYPO3 7.6**

``composer require sourcebroker/deployer-extended-typo3 ~2.1.0``

**For TYPO3 8.7**

``composer require sourcebroker/deployer-extended-typo3 ~3.1.0``


### Dot env

This package requires that you use .env to manage your TYPO3 settings which 
are instance dependent.

For database synchronisation functionality you must set in .env file the INSTANCE which
must be the same as server name in deploy.php file: 

For TYPO3 7.6

    INSTANCE="local"
    TYPO3__DB__database="dbname"
    TYPO3__DB__host="localhost"
    TYPO3__DB__password="password"
    TYPO3__DB__port="3306"
    TYPO3__DB__username="user"
    
For TYPO3 8.7

    INSTANCE="local"
    TYPO3__DB__Connections__Default__dbname="dbname"
    TYPO3__DB__Connections__Default__host="localhost"
    TYPO3__DB__Connections__Default__password="password"
    TYPO3__DB__Connections__Default__port="3306"
    TYPO3__DB__Connections__Default__user="user"


To implement the ".env" support for your project look for:
 https://github.com/helhum/dotenv-connector
 https://github.com/helhum/TYPO3-Distribution


## Known problems
None.

## To-Do list
None.