<?php

namespace Deployer;

// Extend "sourcebroker/deployer-typo3-deploy" and "sourcebroker/deployer-typo3-deploy-ci" deploy task

if (!Deployer::get()->tasks->has('deploy-ci')) {

    // sourcebroker/deployer-extended special task. Read more at https://github.com/sourcebroker/deployer-extended#deploy-check-lock
    after('deploy:info', 'deploy:check_lock');

    // sourcebroker/deployer-extended special task. Read more at https://github.com/sourcebroker/deployer-extended#deploy-check-composer-install
    after('deploy:info', 'deploy:check_composer_install');

    // sourcebroker/deployer-extended special task. Read more at https://github.com/sourcebroker/deployer-extended#deploy-check-composer-validate
    after('deploy:info', 'deploy:check_composer_validate');

    // sourcebroker/deployer-extended special task. Read more at https://github.com/sourcebroker/deployer-extended#deploy-check-branch-local
    after('deploy:info', 'deploy:check_branch_local');

    // sourcebroker/deployer-extended special task. Read more at https://github.com/sourcebroker/deployer-extended#deploy-check-branch
    after('deploy:info', 'deploy:check_branch');
}

// sourcebroker/deployer-extended special task. Read more on https://github.com/sourcebroker/deployer-extended#service-php-fpm-reload
after('deploy:symlink', 'service:php_fpm_reload');

// sourcebroker/deployer-extended special task. Read more on https://github.com/sourcebroker/deployer-extended#cache-clear-php-cli
after('deploy:symlink', 'cache:clear_php_cli');

// sourcebroker/deployer-extended special task. Read more on https://github.com/sourcebroker/deployer-extended#cache-clear-php-http
after('deploy:symlink', 'cache:clear_php_http');
