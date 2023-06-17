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


   Its advisable that you put ``alias dep="vendor/bin/dep"`` in your ``~/.profile`` to be able to run deployer
   with regular ``dep`` command. Otherwise you will need to run deployer like this ``./vendor/bin/dep ...``

2) Put following lines on the beginning of your deploy.php:
   ::

      require_once(__DIR__ . '/vendor/sourcebroker/deployer-loader/autoload.php');
      new \SourceBroker\DeployerExtendedTypo3\Loader();

3) Remove task "deploy" from your deploy.php. Otherwise you will overwrite deploy task defined in
   ``vendor/sourcebroker/deployer-extended-typo3/deployer/default/deploy/task/deploy.php``. Look at
   `Example of working configuration`_ to see how simple can be working ``deploy.php`` file.

4) On each instance create ``.env`` file which should be out of git and have at least ``INSTANCE`` with the same name as
   defined for ``host()`` in ``deploy.php`` file. You can use this file also to store database credentials and all other
   settings that are different per instance. Because ``deployer-extended-typo3`` use ``helhum/dotenv-connector`` the values
   form ``.env`` file will be available in TYPO3 and you can use them for example in ``typo3onf/AdditionalConfiguration.php``
   to set up database connections. This way you can have ``typo3onf/LocalConfiguration.php`` in git. Example for ``.env`` file:

   ::

      TYPO3_CONTEXT='Production//Live'
      INSTANCE='live'

      TYPO3__DB__Connections__Default__dbname='t3base11_live'
      TYPO3__DB__Connections__Default__host='127.0.0.1'
      TYPO3__DB__Connections__Default__password='password'
      TYPO3__DB__Connections__Default__port='3306'
      TYPO3__DB__Connections__Default__user='t3base11_live'


   If you want to update language files on each deploy add task ``typo3cms:language:update`` before ``deploy_symlink``.
   Read https://github.com/sourcebroker/deployer-extended-typo3/discussions/14 to see why updating language labels on
   each deploy is very arguable and generally not advised.
   ::

      before('deploy_symlink', 'typo3cms:language:update');

   If you want that Deployer get database data from TYPO3 directly instead of reading from .env file then set:
   ::

      set('driver_typo3cms', true);

Deployment
----------

Run:
::

   dep deploy [host]


Shared dirs
+++++++++++

For TYPO3 11 the shared dirs are:
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

Shared files
++++++++++++

The shared file for TYPO3 11 is:
::

   set('shared_files', ['.env']);


Composer
++++++++

You can set proper version of composer with ``composer_channel`` (values: 1, 2, stable, prelive, snapshot) or with
``composer_version`` which takes exact tags as arguments (https://github.com/composer/composer/tags). For stability and
security it is advised that you set ``composer_channel`` with value ``1`` or ``2`` so it will be automatically updated
but will not install any new major version in future so your deploy will remain fairly stable. The default value is ``2``.

::

   set('composer_channel', 2);


Synchronizing database
----------------------

Database synchronization is done with `sourcebroker/deployer-extended-database`_.

The command for synchronizing database from live database to local instance is:
::

   dep db:pull live

If you are logged to ssh of beta instance you can also run ``dep media:pull live`` to get database from ``live``
to ``beta``. But you can also synchronise ``live`` to ``beta`` from you local instance with following command:

::

   dep db:copy live --options=target:beta


Synchronizing media
-------------------

Media synchronization is done with `sourcebroker/deployer-extended-media`_.
Folders which are synchronized are ``fileadmin`` (except ``fileadmin/_proccessed_``) and ``uploads``.

The command for synchronizing media from live to local instance:

::

   dep media:pull live

If you are logged to ssh of beta instance you can also run ``dep media:pull live`` to get media from ``live``
to ``beta``. But you can also synchronise ``live`` to ``beta`` from you local instance with following command:

::

   dep media:copy live --options=target:beta

If the instances are on the same host you can use symlink for each file (equivalent of ``cp -rs source destination``).
This way you can save space for media on staging instances with no risk that they will be accidentally deleted!

::

   dep media:link live --options=target:beta


Example of working configuration
--------------------------------

This is example of working configuration for TYPO3 11. The aim of ``sourcebroker/deployer-extended-typo3`` is to
have very slim ``deploy.php`` file in order to have nice possibility to upgrade to future versions of
``sourcebroker/deployer-extended-typo3``.

::

  <?php

  namespace Deployer;

  require_once(__DIR__ . '/vendor/sourcebroker/deployer-loader/autoload.php');
  new \SourceBroker\DeployerExtendedTypo3\Loader();

  set('repository', 'git@github.com:sourcebrokergit/t3base11.git');

  host('live')
      ->setHostname('vm-dev.example.com')
      ->setRemoteUser('deploy')
      ->set('branch', 'master')
      ->set('bin/php', '/home/www/t3base11-public/live/.bin/php');
      ->set('public_urls', ['https://live-t3base11.example.com'])
      ->set('deploy_path', '/home/www/t3base11/live');

  host('beta')
      ->setHostname('vm-dev.example.com')
      ->setRemoteUser('deploy')
      ->set('branch', 'master')
      ->set('bin/php', '/home/www/t3base11-public/beta/.bin/php');
      ->set('public_urls', ['https://beta-t3base11.example.com'])
      ->set('deploy_path', '/home/www/t3base11/beta');

  host('local')
      ->set('deploy_path', getcwd());


Changelog
---------

See https://github.com/sourcebroker/deployer-extended-typo3/blob/master/CHANGELOG.rst


.. _sourcebroker/deployer-extended: https://github.com/sourcebroker/deployer-extended
.. _sourcebroker/deployer-extended-media: https://github.com/sourcebroker/deployer-extended-media
.. _sourcebroker/deployer-extended-database: https://github.com/sourcebroker/deployer-extended-database
.. _sourcebroker/deployer-extended-typo3: https://github.com/sourcebroker/deployer-extended-typo3
.. _deployer/dist: https://github.com/deployphp/distribution
