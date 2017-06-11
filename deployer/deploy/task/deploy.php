<?php

namespace Deployer;

task('deploy', [
    // Check for existance of file deploy.lock in root of current instance.
    // If the file deploy.lock is there then deployment is stopped.
    // Read more on https://github.com/sourcebroker/deployer-extended
    'deploy:check_lock',
    // Check if there is composer.lock file on current instance and if its there then make
    // dry run for "composer install". If "composer install" returns information that some
    // packages needs to be updated or installed then it means that probably developer pulled
    // composer.lock changes from repo but forget to make "composer install". In that case deployment
    // is stopped to allow developer to update packages, make some test and make deployment then.
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
    'buffer:start',
    // Truncate caching tables
    'db:truncate',
    // Remove two steps. First just rename files and folders to be removed.
    'file:rm2steps:1',
    // Update database schema for TYPO3.
    'typo3cms:database:updateschema',
    // Clear php file info cache.
    'cache:clearstatcache',
    // Symlink release/x/ to current/
    'deploy:symlink',
    // Remove two steps. First just rename files and folders to be removed.
    'file:rm2steps:1',
    // Clear cli php caches
    'php:clear_cache_cli',
    // Clear frontend http cache
    'php:clear_cache_http',
    // Frontend access possbile again from now
    'buffer:stop',
    // Remove two steps. Remove files and folders.
    'file:rm2steps:2',
    // Standard deployer deploy:unlock
    'deploy:unlock',
    // Standard deployer cleanup.
    'cleanup',
])->desc('Deploy your TYPO3 8.7');

after('deploy', 'success');
