<?php

namespace Deployer;

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

    // If activated, update language files of all activated extensions. Task from typo3_console.
    'check_language_update',

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

after('deploy:failed', 'deploy:unlock');
