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
  to synchronize database between multiple instances

- `sourcebroker/deployer-extended-media`_  package which provides some php framework independent tasks
  to synchronize media between multiple instances


Installation
------------

1) Install package with composer:
   ::

      composer require sourcebroker/deployer-extended-typo3

   Note! Generally its not advisable to install deployer globally because each of your project can use
   different version of deployer so the best is to have version of deployer dependent on project.

   Its not also advisable to install deployer as direct dependency of your project as it can interfere dependencies
   of your project.

   The best is to install phar binary of deployer using composer - with `deployer/dist`_ package.

   This is why ``deployer-extended-typo3`` depends on `deployer/dist`_. This package will install deployer phar
   and symlink it in ``./vendor/bin/dep``. You should use ``./vendor/bin/dep`` binary to run deployer.

   Its advisable that you put ``alias dep="php ./vendor/bin/dep"`` in your ``~/.profile`` to be able to run deployer
   with regular ``dep`` command. Otherwise you will need to run deployer like this ``vendor/bin/dep deploy live``

2) Put following lines on the beginning of your deploy.php:
   ::

      require_once(__DIR__ . '/vendor/sourcebroker/deployer-loader/autoload.php');
      new \SourceBroker\DeployerExtendedTypo3\Loader();

3) Remove task "deploy" from your deploy.php. Otherwise you will overwrite deploy task defined in
   ``vendor/sourcebroker/deployer-extended-typo3/deployer/default/deploy/task/deploy.php``


Deployment
----------

Its very advisable that you test deploy on some beta instance first :)
::

   dep deploy beta


The deploy task consist of following subtasks:
::

  task('deploy', [

    // Standard deployer task.
    'deploy:info',

    // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-lock
    'deploy:check_lock',

    // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-composer-install
    'deploy:check_composer_install',

    // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-branch-local
    'deploy:check_branch_local',

    // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-branch
    'deploy:check_branch',

    // Standard deployer task.
    'deploy:prepare',

    // Standard deployer task.
    'deploy:lock',

    // Standard deployer task.
    'deploy:release',

    // Standard deployer task.
    'deploy:update_code',

    // Standard deployer task.
    'deploy:shared',

    // Standard deployer task.
    'deploy:writable',

    // Standard deployer task.
    'deploy:vendors',

    // Standard deployer task.
    'deploy:clear_paths',

    // Create database backup, compress and copy to database store.
    // Read more on https://github.com/sourcebroker/deployer-extended-database#db-backup
    'db:backup',

    // Start buffering http requests. No frontend access possible from now.
    // Read more on https://github.com/sourcebroker/deployer-extended#buffer-start
    'buffer:start',

    // Truncate caching tables, all cf_* tables
    // Read more on https://github.com/sourcebroker/deployer-extended-database#db-truncate
    'db:truncate',

    // Update database schema for TYPO3. Task from typo3_console extension.
    'typo3cms:database:updateschema',

    // Standard deployer task.
    'deploy:symlink',

    // Clear php cli cache.
    // Read more on https://github.com/sourcebroker/deployer-extended#cache-clear-php-cli
    'cache:clear_php_cli',

    // Clear frontend http cache.
    // Read more on https://github.com/sourcebroker/deployer-extended#cache-clear-php-http
    'cache:clear_php_http',

    // Frontend access possible again from now
    // Read more on https://github.com/sourcebroker/deployer-extended#buffer-stop
    'buffer:stop',

    // Standard deployer task.
    'deploy:unlock',

    // Standard deployer task.
    'cleanup',

    // Read more on https://github.com/sourcebroker/deployer-extended#deploy-extend-log
    'deploy:extend_log',

    // Standard deployer task.
    'success',

  ])->desc('Deploy your TYPO3');

The shared dirs for TYPO3 10 are:
::

  set('shared_dirs', function () {
      return [
          get('web_path') . 'fileadmin',
          get('web_path') . 'uploads',
          get('web_path') . 'typo3temp/assets/_processed_',
          get('web_path') . 'typo3temp/assets/images',
          !empty(get('web_path')) ? 'var/log' : 'typo3temp/var/log',
          !empty(get('web_path')) ? 'var/transient' : 'typo3temp/var/transient',
      ];
  });

The shared file for TYPO3 10 is:
::

   set('shared_files', ['.env']);

Use this file to store database credentials and use them as env vars for example in typo3onf/AdditionalConfiguration.php
to set up database. This way you can have typo3onf/LocalConfiguration.php in git.

For TYPO3 10 if you use composer installation with public/ folder (default) you need to set in your deploy.php:
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
on staging instances with no risk that they will be accidentally deleted!

::

   dep media:link live --options=target:beta

Changelog
---------

See https://github.com/sourcebroker/deployer-extended-typo3/blob/master/CHANGELOG.rst


.. _sourcebroker/deployer-extended: https://github.com/sourcebroker/deployer-extended
.. _sourcebroker/deployer-extended-media: https://github.com/sourcebroker/deployer-extended-media
.. _sourcebroker/deployer-extended-database: https://github.com/sourcebroker/deployer-extended-database
.. _deployer/dist: https://github.com/deployphp/distribution
