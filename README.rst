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

- `sourcebroker/deployer-bulk-tasks`_ which is wrapper for typo3_console and TYPO3 native cli commands

Additionally this package depends on two more packages that are not used directly for deploy but are useful
for database and media synchronization:

- `sourcebroker/deployer-extended-database`_ package which provides some php framework independent tasks
  to synchronize database

- `sourcebroker/deployer-extended-media`_  package which provides some php framework independent tasks
  to synchronize media


Installation
------------

1) Install package with composer:
   ::

      composer require sourcebroker/deployer-extended-typo3

   Note! This command will install also deployer/dist package which will create ./vendor/bin/dep binary. You should use
   this binary to run deploy. Its advisable that you put `alias dep="php ./vendor/bin/dep"` in your ~/.profile
   to be able to run deployer with regular "dep" command.

2) If you are using deployer as composer package then just put following line in your deploy.php:
   ::

      new \SourceBroker\DeployerExtendedTypo3\Loader();

3) If you are using deployer as phar then put following lines in your deploy.php:
   ::

      require_once(__DIR__ . '/vendor/sourcebroker/deployer-loader/autoload.php');
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

       // Create database backup, compress and copy to database store.
       // Read more on https://github.com/sourcebroker/deployer-extended-database#db-backup
       'db:backup',

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

   ])->desc('Deploy your TYPO3 9');

The shared dirs for TYPO3 9 are:
::

   set('shared_dirs', [
           'fileadmin',
           'uploads',
           'typo3temp/assets/_processed_',
           'typo3temp/assets/images',
           'typo3temp/var/logs',
       ]
   );

The shared files for TYPO3 9 are:
::

   set('shared_files', ['.env']);


Synchronizing database
----------------------

Database synchronization is done with `sourcebroker/deployer-extended-database`.

Read https://github.com/helhum/dotenv-connector to know how to reuse database data stored in .env file later in TYPO3.
This way you are able to store database credentials in one place.


Database configuration:
::

   set('db_default', [
       'truncate_tables' => [
           // Do not truncate caching tables "cf_cache_imagesizes" and "cf_cache_pages_tags" as the image settings are not
           // changed frequently and regenerating images is processor core extensive.
           '(?!cf_cache_imagesizes)cf_.*',
           'cache_.*'
       ],
       // Do not get those tables when synchronising database between instances as they can be very huge and usually are not needed.
       'ignore_tables_out' => [
           'cf_.*',
           'cache_.*',
           'be_sessions',
           'fe_sessions',
           'fe_session_data',
           'sys_file_processedfile',
           'sys_history',
           'sys_log',
           'sys_refindex',
           'tx_devlog',
           'tx_extensionmanager_domain_model_extension',
           'tx_realurl_.*',
           'tx_powermail_domain_model_mail*',
           'tx_powermail_domain_model_answer*',
           'tx_solr_.*',
           'tx_crawler_queue',
           'tx_crawler_process',
       ],
       'post_sql_in' => '',
        // SQL done after importing database from target instance. This one will activate sys_domains records for current instance.
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


Changelog
---------

See https://github.com/sourcebroker/deployer-extended-typo3/blob/master/CHANGELOG.rst


.. _sourcebroker/deployer-extended: https://github.com/sourcebroker/deployer-extended
.. _sourcebroker/deployer-extended-media: https://github.com/sourcebroker/deployer-extended-media
.. _sourcebroker/deployer-extended-database: https://github.com/sourcebroker/deployer-extended-database
.. _sourcebroker/deployer-bulk-tasks: https://github.com/sourcebroker/deployer-bulk-tasks
