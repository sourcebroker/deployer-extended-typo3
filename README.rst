deployer-extended-media
=======================

.. image:: https://scrutinizer-ci.com/g/sourcebroker/deployer-extended-typo3/badges/quality-score.png?b=master
   :target: https://scrutinizer-ci.com/g/sourcebroker/deployer-extended-typo3/?branch=master

.. image:: http://img.shields.io/packagist/v/sourcebroker/deployer-extended-typo3.svg?style=flat
   :target: https://packagist.org/packages/sourcebroker/deployer-extended-typo3

.. image:: https://img.shields.io/badge/license-MIT-blue.svg?style=flat
   :target: https://packagist.org/packages/sourcebroker/deployer-extended-typo3

|

.. contents:: :local:

What does it do?
----------------

This package provides deploy task for deploying TYPO3 CMS with deployer (deployer.org).

Deploy task depends on:

- sourcebroker/deployer-extended package which provides some deployer tasks that can be used for any
  framework or CMS

- sourcebroker/deployer-extended-typo3-tasks which is wrapper for ext:typo3_console and TYPO3 commands

Additionally the package depends on two more packages that are not used on deploy directly abut are useful
to database and media synchronization:

- sourcebroker/deployer-extended-database package which provides some php framework independent tasks
  to synchronize database

- sourcebroker/deployer-extended-media package which provides some php framework independent tasks
  to synchronize media


Installation
------------

Mind that there is no full semantic versioning because major version is for the TYPO3 version.
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

   **For TYPO3 7.6**
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

5) Try to run deploy on some testing instance to see if all works.
