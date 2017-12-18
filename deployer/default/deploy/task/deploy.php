<?php

namespace Deployer;

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

])->desc('Deploy your TYPO3');

after('deploy', 'success');
