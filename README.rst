deployer-extended-typo3
=======================

      .. image:: http://img.shields.io/packagist/v/sourcebroker/deployer-extended-typo3.svg?style=flat
         :target: https://packagist.org/packages/sourcebroker/deployer-extended-typo3

      .. image:: https://img.shields.io/badge/license-MIT-blue.svg?style=flat
         :target: https://packagist.org/packages/sourcebroker/deployer-extended-typo3

.. contents:: :local:

What does it do?
----------------

This package provides deploy task for deploying TYPO3 CMS with deployer (deployer.org).

This "deploy" task depends on:

- `sourcebroker/deployer-extended`_ package which provides some deployer tasks that can be used for any CMS

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

   Note! This command will install also `deployer/dist`_ package which will create ``./vendor/bin/dep`` binary. You should use
   this binary to run deploy. Its advisable that you put ``alias dep="php ./vendor/bin/dep"`` in your ``~/.profile``
   to be able to run deployer with regular "dep" command.

2) If you are using deployer as composer package then just put following line in your deploy.php:
   ::

      new \SourceBroker\DeployerExtendedTypo3\Loader();

3) If you are using deployer as phar then put following lines in your deploy.php:
   ::

      require_once(__DIR__ . '/vendor/sourcebroker/deployer-loader/autoload.php');
      new \SourceBroker\DeployerExtendedTypo3\Loader();

4) Remove task "deploy" from your deploy.php. Otherwise you will overwrite deploy task defined in
   ``deployer/deploy/task/deploy.php``


Deployment
----------

Its very advisable that you test deploy on some beta instance first :)
::

   dep deploy beta


The deploy task consist of following subtasks:
::

  task('deploy', [

    // Standard deployer deploy:info
    'deploy:info',

    // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-lock
    'deploy:check_lock',

    // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-composer-install
    'deploy:check_composer_install',

    // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-branch-local
    'deploy:check_branch_local',

    // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-branch
    'deploy:check_branch',

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

    // Update database schema for TYPO3. Task from typo3_console extension.
    'typo3cms:database:updateschema',

    // Standard deployers symlink (symlink release/x/ to current/)
    'deploy:symlink',

    // Clear php cli cache.
    // Read more on https://github.com/sourcebroker/deployer-extended#cache-clear-php-cli
    'cache:clear_php_cli',

    // Clear frontend http cache.
    // Read more on https://github.com/sourcebroker/deployer-extended#cache-clear-php-http
    'cache:clear_php_http',

    // Frontend access possbile again from now
    // Read more on https://github.com/sourcebroker/deployer-extended#buffer-stop
    'buffer:stop',

    // Standard deployer deploy:unlock
    'deploy:unlock',

    // Standard deployer cleanup.
    'cleanup',

    // Read more on https://github.com/sourcebroker/deployer-extended#deploy-extend-log
    'deploy:extend_log',

    // Standard deployer success.
    'success',

  ])->desc('Deploy your TYPO3');

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

For TYPO3 9 if you use composer installation with public/ folder (default) you need to set in your deploy.php:
::

   set('web_path', 'public/');


Synchronizing database
----------------------

Database synchronization is done with `sourcebroker/deployer-extended-database`_.

The command for synchronizing database from live media to local instance is:
::

   dep db:pull live

You can also synchronise database on remote instances with following command:
::

   dep db:copy live --options=target:beta


Synchronizing media
-------------------

Media synchronization is done with `sourcebroker/deployer-extended-media`_.
Folders which are synchronized are ``fileadmin`` (except ``fileadmin/_proccessed_``) and ``uploads``.

The command for synchronizing local media folders with live media folders is:
::

   dep media:pull live

You can also synchronise remote instances with following command:
::

   dep media:copy live --options=target:beta

If the instances are on the same host you can use symlink for each file
(equivalent of ``cp -rs source destination``). This way you can save space for media
on staging instances with no risk that they will be accidentally deleted.

::

   dep media:link live --options=target:beta

Changelog
---------

See https://github.com/sourcebroker/deployer-extended-typo3/blob/master/CHANGELOG.rst


.. _sourcebroker/deployer-extended: https://github.com/sourcebroker/deployer-extended
.. _sourcebroker/deployer-extended-media: https://github.com/sourcebroker/deployer-extended-media
.. _sourcebroker/deployer-extended-database: https://github.com/sourcebroker/deployer-extended-database
.. _deployer/dist: https://github.com/deployphp/distribution
