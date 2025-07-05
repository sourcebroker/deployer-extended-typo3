# deployer-extended-typo3

[![Packagist Version](http://img.shields.io/packagist/v/sourcebroker/deployer-extended-typo3.svg?style=flat)](https://packagist.org/packages/sourcebroker/deployer-extended-typo3)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg?style=flat)](https://packagist.org/packages/sourcebroker/deployer-extended-typo3)

> [!NOTE]
>
> This package is a working example (successfully used in a mid-sized agency) that shows how you can use different `sourcebroker/deployer-*` packages together.
> If you like the workflow provided here, feel free to use it as-is in your own projects.
>
> However, it's often a better idea to create your own package so it fits your needs perfectly.
> You can combine different `sourcebroker/deployer-*` base packages to build your own high customized, agency-level solution.
>
> An example of such a custom solution, built on top of `sourcebroker/deployer-*` packages, but adding their own improvements and adaptations
> is: [liquidlight/deployer-typo3-ci](https://github.com/liquidlight/deployer-typo3-ci)

## What does it do?

This package combine different `sourcebroker/deployer-*` base packages and adds some very high level customizations, 
you can call it agency-level customizations.

1. [sourcebroker/deployer-typo3-deploy](https://github.com/sourcebroker/deployer-typo3-deploy) - TYPO3 deploy process at local level
2. [sourcebroker/deployer-typo3-deploy-ci](https://github.com/sourcebroker/deployer-typo3-deploy-ci) - TYPO3 deploy process at CI level (gitlab for now only)
3. [sourcebroker/deployer-typo3-database](https://github.com/sourcebroker/deployer-typo3-database) - TYPO3 preconfigured synchronization of databases between multiple instances
4. [sourcebroker/deployer-typo3-media](https://github.com/sourcebroker/deployer-typo3-media) - TYPO3 preconfigured synchronization of media between multiple instances

## Installation

1. Install package with composer:

    ```bash
    composer require sourcebroker/deployer-extended-typo3
    ```

2. Put the following lines at the beginning of your `deploy.php`. You can decide which packages/functionality you want
   to use. For example remove the line `['get' => 'sourcebroker/deployer-typo3-database'],` and there will be no tasks for
   database sync - you can replace it with your own tasks for database update. The same for
   `['get' => 'sourcebroker/deployer-typo3-media']` - maybe you prefer to use https://packagist.org/packages/ichhabrecht/filefill.
   Another example: if you choose to deploy using CI, use `['get' => sourcebroker/deployer-typo3-deploy-ci]` instead of
   `['get' => sourcebroker/deployer-typo3-deploy]`. Each package is completely independent, use only those you need.

    ```php
    <?php

    require_once('./vendor/autoload.php');

    new \SourceBroker\DeployerLoader\Load([
        ['get' => 'sourcebroker/deployer-typo3-media'],
        ['get' => 'sourcebroker/deployer-typo3-database'],
        ['get' => 'sourcebroker/deployer-typo3-deploy'],
        ['get' => 'sourcebroker/deployer-extended-typo3'],
    ]);
    ```

4. If you want to use database synchronization, please read the documentation
   at [sourcebroker/deployer-typo3-database](https://github.com/sourcebroker/deployer-typo3-database).

5. If you want to use media synchronization, please read the documentation
   at [sourcebroker/deployer-typo3-media](https://github.com/sourcebroker/deployer-typo3-media).


## Example of working configuration

### CLI deploy (local)

This is an example of working configuration for TYPO3 13. The aim of `sourcebroker/deployer-extended-typo3` is to have a
very slim `deploy.php` file for easy upgrades to future versions.

```php
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
```

### GitLab CI deploy

> [!NOTE]
> Deploy is from CI level, but database and media sync in below example still require SSH access from your local computer!

```php
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
```

## Changelog

See [CHANGELOG.rst](https://github.com/sourcebroker/deployer-extended-typo3/blob/master/CHANGELOG.rst)
