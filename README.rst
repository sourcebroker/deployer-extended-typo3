deployer-extended-typo3
=======================
|

.. image:: http://img.shields.io/packagist/v/sourcebroker/deployer-extended-typo3.svg?style=flat
   :target: https://packagist.org/packages/sourcebroker/deployer-extended-typo3

.. image:: https://img.shields.io/badge/license-MIT-blue.svg?style=flat
   :target: https://packagist.org/packages/sourcebroker/deployer-extended-typo3

|

.. contents:: :local:

What does it do?
----------------

This package provides deploy task for deploying TYPO3 CMS with deployer (deployer.org).

This "deploy" task depends on:

- `sourcebroker/deployer-extended`_ package which provides some deployer tasks that can be used for any framework or CMS

- `sourcebroker/deployer-extended-typo3-tasks`_ which is wrapper for ext:typo3_console and TYPO3 commands

Additionally this package depends on two more packages that are not used directly for deploy but are useful
to database and media synchronization:

- `sourcebroker/deployer-extended-database`_ package which provides some php framework independent tasks
  to synchronize database

- `sourcebroker/deployer-extended-media`_  package which provides some php framework independent tasks
  to synchronize media


Installation
------------

Mind that there is no full semantic versioning because "major" version is taken for the TYPO3 version.

- For TYPO3 7.6 the tags starts with 2.\*.\*
- For TYPO3 8.7 the tags starts with 3.\*.\*
- For TYPO3 9.0 the tags will start with 4.\*.\*

The features and bugfixes will increment the patch version and the change in minor version
will mean breaking change ([TYPO3 version].[breaking].[features/bugfixes])

Therefore you should use tilde-range constraints for choosing sourcebroker/deployer-extended-typo3

1) Install package with composer:

   **For TYPO3 7.6**
   ::

      composer require sourcebroker/deployer-extended-typo3 ~2.1.0

   **For TYPO3 8.7**
   ::

      composer require sourcebroker/deployer-extended-typo3 ~3.1.0


2) If you are using deployer as composer package then just put following line in your deploy.php:
   ::

      new \SourceBroker\DeployerExtendedTypo3\Loader();

3) If you are using deployer as phar then put following lines in your deploy.php:
   ::

      require __DIR__ . '/vendor/autoload.php';
      new \SourceBroker\DeployerExtendedTypo3\Loader();

4) Remove task "deploy" from your deploy.php. Otherwise you will overwrite deploy task defined in
   deployer/deploy/task/deploy.php


Deployment
----------

Its very advisable that you test deploy on some beta instance first :)
::

   dep deploy beta


The deploy task consist of following subtasks:
::

   task('deploy', [
       // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-lock
       'deploy:check_lock',

       // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-composer-install
       'deploy:check_composer_install',

       // Standard deployer deploy:prepare
       'deploy:prepare',

       // Standard deployer deploy:lock
       'deploy:lock',

       // Standard deployer deploy:release
       'deploy:release',

       // Standard deployer deploy:update_code
       'deploy:update_code',

       // Standard deployer deploy:shared
       'deploy:shared',

       // Standard deployer deploy:writable
       'deploy:writable',

       // Standard deployer deploy:vendors
       'deploy:vendors',

       // Standard deployer deploy:clear_paths
       'deploy:clear_paths',

       // Start buffering http requests. No frontend access possbile from now.
       // Read more on https://github.com/sourcebroker/deployer-extended#buffer-start
       'buffer:start',

       // Truncate caching tables, all cf_* tables
       // Read more on https://github.com/sourcebroker/deployer-extended-database#db-truncate
       'db:truncate',

       // Remove two steps. We rename typo3temp/Cache/
       // Read more on https://github.com/sourcebroker/deployer-extended#file-rm2steps-1
       'file:rm2steps:1',

       // Update database schema for TYPO3. Task from typo3_console extension.
       'typo3cms:database:updateschema',

       // Clear php cli cache.
       // Read more on https://github.com/sourcebroker/deployer-extended#php-clear-cache-cli
       'php:clear_cache_cli',

       // Standard deployers symlink (symlink release/x/ to current/)
       'deploy:symlink',

       // Clear frontend http cache.
       // Read more on https://github.com/sourcebroker/deployer-extended#php-clear-cache-http
       'php:clear_cache_http',

       // Frontend access possbile again from now
       // Read more on https://github.com/sourcebroker/deployer-extended#buffer-stop
       'buffer:stop',

       // Remove two steps. Real remove files and folders.
       // Read more on https://github.com/sourcebroker/deployer-extended#file-rm2steps-2
       'file:rm2steps:2',

       // Standard deployer deploy:unlock
       'deploy:unlock',

       // Standard deployer cleanup.
       'cleanup',

   ])->desc('Deploy your TYPO3 8.7');

The shared dirs for TYPO3 8.7 are:
::

   set('shared_dirs', [
           'fileadmin',
           'uploads',
           'typo3temp/assets/_processed_',
           'typo3temp/assets/images',
           'typo3temp/var/logs',
       ]
   );

The shared files for TYPO3 8.7 are:
::

   set('shared_files', ['.env']);


Synchronizing database
----------------------

Database synchronization is done with `sourcebroker/deployer-extended-database`.
This package requires to store database data in .env files.

You can read more on `sourcebroker/deployer-extended-database` how to store database data
in .env file to be able to synchronize database.

`sourcebroker/deployer-extended-typo3` assume however that you are using .env file not only to
synchronize database but also to give TYPO3 database creditentials. This is usually set in $GLOBALS
in typo3conf/LocalConfiguration.php or in typo3conf/AdditionalConfiguration.php

Here however we do not set database creditentials in typo3conf/LocalConfiguration.php or
in typo3conf/AdditionalConfiguration.php. We do it in .env file and use special way of writing
env vars that are later automaticaly converted to $GLOBALS['TYPO3_CONF_VARS']['DB']*

To use .env fully in your TYPO3 instance install https://github.com/helhum/dotenv-connector
Then you can make a simple .env to $GLOBALS converter:
Put the following in ``typo3conf/AdditionalConfiguration.php``
::

   foreach ($_ENV as $name => $value) {
            if (strpos($name, 'TYPO3__') !== 0) {
                continue;
            }
            $GLOBALS['TYPO3_CONF_VARS'] = ArrayUtility::setValueByPath(
                $GLOBALS['TYPO3_CONF_VARS'],
                str_replace('__', '/', substr($name, 7)),
                $value
            );
        }

This can be a simple start before more complex solutions.

Taking the above facts the .env files should have database data in following format for TYPO3 8.7:
::

   TYPO3__DB__Connections__Default__dbname="{DATABASE_NAME}"
   TYPO3__DB__Connections__Default__host="{DATABASE_HOST}"
   TYPO3__DB__Connections__Default__password="{DATABASE_PASSWORD}"
   TYPO3__DB__Connections__Default__port="{DATABASE_PORT}"
   TYPO3__DB__Connections__Default__user="{DATABASE_USER}"

And following format for TYPO 7.6:
::

   TYPO3__DB__database="{DATABASE_NAME}"
   TYPO3__DB__host="{DATABASE_HOST}"
   TYPO3__DB__password="{DATABASE_PASSWORD}"
   TYPO3__DB__port="{DATABASE_PORT}"
   TYPO3__DB__username="{DATABASE_USER}"

Database configuration:
::

   set('db_default', [
    'truncate_tables' => [
        'cf_.*'
    ],
    'ignore_tables_out' => [
        'cf_.*',
        'cache_.*',
        'be_sessions',
        'sys_history',
        'sys_file_processedfile',
        'sys_log',
        'sys_refindex',
        'tx_devlog',
        'tx_extensionmanager_domain_model_extension',
        'tx_realurl_chashcache',
        'tx_realurl_errorlog',
        'tx_realurl_pathcache',
        'tx_realurl_uniqalias',
        'tx_realurl_urldecodecache',
        'tx_realurl_urlencodecache',
        'tx_powermail_domain_model_mails',
        'tx_powermail_domain_model_answers',
        'tx_solr_.*',
        'tx_crawler_queue',
        'tx_crawler_process',
    ],
    'post_sql_in' => '',
    'post_sql_in_markers' => '
                              UPDATE sys_domain SET hidden = 1;
                              UPDATE sys_domain SET sorting = sorting + 10;
                              UPDATE sys_domain SET sorting=1, hidden = 0 WHERE domainName IN ({{domainsSeparatedByComma}});
                              '
   ]);

   set('db_databases',
       [
           'database_default' => [
               get('db_default'),
               (new \SourceBroker\DeployerExtendedTypo3\Drivers\Typo3EnvDriver)->getDatabaseConfig(
                   [
                       'host' => 'TYPO3__DB__Connections__Default__host',
                       'port' => 'TYPO3__DB__Connections__Default__port',
                       'dbname' => 'TYPO3__DB__Connections__Default__dbname',
                       'user' => 'TYPO3__DB__Connections__Default__user',
                       'password' => 'TYPO3__DB__Connections__Default__password',
                   ]
               ),
           ]
       ]
   );

The command for synchronizing database from live media to local instance is:
::

   dep db:pull live



Synchronizing media
-------------------

Media synchronization is done with `sourcebroker/deployer-extended-media`.
Folders which are synchronized are ``fileadmin`` (except ``_proccessed_``) and ``uploads``.
The config for that is:
::

   set('media',
       [
           'filter' => [
               '+ /fileadmin/',
               '- /fileadmin/_processed_/*',
               '+ /fileadmin/**',
               '+ /uploads/',
               '+ /uploads/**',
               '- *'
           ]
       ]);

The command for synchronizing local media folders with live media folders is:
::

   dep media:pull live


.. _sourcebroker/deployer-extended: https://github.com/sourcebroker/deployer-extended
.. _sourcebroker/deployer-extended-media: https://github.com/sourcebroker/deployer-extended-media
.. _sourcebroker/deployer-extended-database: https://github.com/sourcebroker/deployer-extended-database
.. _sourcebroker/deployer-extended-typo3-tasks: https://github.com/sourcebroker/deployer-extended-typo3-tasks
