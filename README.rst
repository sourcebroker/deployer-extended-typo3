deployer-extended-typo3
=======================

      .. image:: http://img.shields.io/packagist/v/sourcebroker/deployer-extended-typo3.svg?style=flat
         :target: https://packagist.org/packages/sourcebroker/deployer-extended-typo3

      .. image:: https://img.shields.io/badge/license-MIT-blue.svg?style=flat
         :target: https://packagist.org/packages/sourcebroker/deployer-extended-typo3

.. contents:: :local:

What does it do?
----------------

This package is customisation for stack of packages ``sourcebroker/deployer-typo3-*``.

1) package `sourcebroker/deployer-typo3-deploy`_ - TYPO3 deploy process at local level
2) package `sourcebroker/deployer-typo3-deploy-ci`_ TYPO3 deploy process at CI level (gitlab for now only)
3) package `sourcebroker/deployer-typo3-database`_ TYPO3 preconfigured synchronisation of databases between multiple instances
4) package `sourcebroker/deployer-typo3-media`_ TYPO3 preconfigured synchronisation of media between multiple instances

You should treat this package more like example and fork it to make your own customisation.

But if you accept flow proposed by this project then of course you can make it a base for your projects.


Installation
------------

1) Install package with composer:
   ::

      composer require sourcebroker/deployer-extended-typo3

2) Put following lines on the beginning of your ``deploy.php``. You can decide what packages/functionality you want to use.
   For example - remove line ``['get' => 'sourcebroker/deployer-typo3-database'],`` and there will be no task for database sync.
   If you choose deploy using CI then use ``sourcebroker/deployer-typo3-deploy-ci`` instead of ``sourcebroker/deployer-typo3-deploy``.
   Each package is completely independent and you can use only those you need.

   ::

        <?php

            require_once('./vendor/autoload.php');

            new \SourceBroker\DeployerLoader\Load([
                ['get' => 'sourcebroker/deployer-typo3-media'],
                ['get' => 'sourcebroker/deployer-typo3-database'],
                ['get' => 'sourcebroker/deployer-typo3-deploy'],
                ['get' => 'sourcebroker/deployer-extended-typo3'],
            ]);

3) If you use `sourcebroker/deployer-typo3-deploy`_ then remove task ``deploy`` from your ``deploy.php``.

4) If you want to use database synchronisation then please read documentation at `sourcebroker/deployer-typo3-database`_

5) If you want to use media synchronisation then please read documentation at `sourcebroker/deployer-typo3-media`_


Example of working configuration
--------------------------------

This is example of working configuration for TYPO3 13. The aim of ``sourcebroker/deployer-extended-typo3`` is to
have very slim ``deploy.php`` file in order to have nice possibility to upgrade to future versions of
``sourcebroker/deployer-extended-typo3``.

This is config for deploy from local cli level.

::

    <?php

    namespace Deployer;

    require_once('./vendor/autoload.php');

    new \SourceBroker\DeployerLoader\Load([
        ['get' => 'sourcebroker/deployer-typo3-media'],
        ['get' => 'sourcebroker/deployer-typo3-database'],
        ['get' => 'sourcebroker/deployer-typo3-deploy'],
        ['get' => 'sourcebroker/deployer-extended-typo3'],
    ]);

    set('repository', 'git@github.com:sourcebrokergit/t3base13.git');

    host('production')
      ->setHostname('vm-dev.example.com')
      ->setRemoteUser('deploy')
      ->set('branch', 'main')
      ->set('public_urls', ['https://t3base13.example.com'])
      ->set('deploy_path', '~/t3base13/production');

    host('staging')
      ->setHostname('vm-dev.example.com')
      ->setRemoteUser('deploy')
      ->set('branch', 'develop')
      ->set('public_urls', ['https://staging-t3base13.example.com'])
      ->set('deploy_path', '~/t3base13/staging');

and here example for deploy from gitlab ci. Deploy is from CI level but database and media synchro still needs ssh access (!)

::

    <?php

    namespace Deployer;

    require_once('./vendor/autoload.php');

    new \SourceBroker\DeployerLoader\Load([
        ['get' => 'sourcebroker/deployer-typo3-media'],
        ['get' => 'sourcebroker/deployer-typo3-database'],
        ['get' => 'sourcebroker/deployer-typo3-deploy-ci'],
        ['get' => 'sourcebroker/deployer-extended-typo3'],
    ]);

    host('production')
      ->setHostname('vm-dev.example.com')
      ->setRemoteUser('deploy')
      ->set('public_urls', ['https://t3base13.example.com'])
      ->set('deploy_path', '~/t3base13/production');

    host('staging')
      ->setHostname('vm-dev.example.com')
      ->setRemoteUser('deploy')
      ->set('public_urls', ['https://staging-t3base13.example.com'])
      ->set('deploy_path', '~/t3base13/staging');


Changelog
---------

See https://github.com/sourcebroker/deployer-extended-typo3/blob/master/CHANGELOG.rst

.. _sourcebroker/deployer-typo3-deploy: https://github.com/sourcebroker/deployer-typo3-deploy
.. _sourcebroker/deployer-typo3-deploy-ci: https://github.com/sourcebroker/deployer-typo3-deploy-ci
.. _sourcebroker/deployer-typo3-database: https://github.com/sourcebroker/deployer-typo3-database
.. _sourcebroker/deployer-typo3-media: https://github.com/sourcebroker/deployer-typo3-media
