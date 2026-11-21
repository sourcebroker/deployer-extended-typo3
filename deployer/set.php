<?php

namespace Deployer;

// We use custom bin/composer from https://github.com/sourcebroker/deployer-extended?tab=readme-ov-file#bin-composer
// And because of that, can set the composer version.
set('composer_channel', '2');

// Add custom clear paths compared to base from sourcebroker/deployer-typo3-deploy*
add('clear_paths', [
    '.env.dist',
    'assets',
    'README.md',
]);

// Deployer standard is 300. This can be too little for db:media tasks.
set('default_timeout', 900);

// Deployer standard is 10 releases.
set('keep_releases', 5);

// For 100% hosters we used so far, the ssh username is the same as httpd user.
set('writable_mode', 'skip');

// Files uploaded by "file:upload_build" (sourcebroker/deployer-extended) get default permissions of the server
// (its umask) instead of permissions from the build, but never write permission for others.
set('file_upload_build_rsync_options', ['--no-perms', '--chmod=ugo=rwX,o-w']);

// Force the branch to deploy to be explicitly set by `->set("branch", "main");` or by adding cli param `--branch=`
// If branch is not set the task "deploy:check_branch_local" will stop deploy.
set('branch', function () {
    return null;
});

// Do not allow dangerous media sync to top instances.
// Look https://github.com/sourcebroker/deployer-extended-media for docs
set('media_allow_push_live', false);
set('media_allow_copy_live', false);
set('media_allow_link_live', false);
set('media_allow_pull_live', false);

// Do not allow dangerous database sync to top instances.
// Look https://github.com/sourcebroker/deployer-extended-database for docs
set('db_allow_push_live', false);
set('db_allow_pull_live', false);
set('db_allow_copy_live', false);

// Set custom database backup rotations
set('db_dumpclean_keep', ['*' => 5, 'live' => 10]);

// Extend ignore_tables_out defined in sourcebroker/deployer-typo3-database. How to extend it in project deploy.php:
// https://github.com/sourcebroker/deployer-extended-typo3#database-additional-ignored-tables-and-per-instance-sql
set('db_databases_overwrite_global', [
    'database_default' => [
        [
            'ignore_tables_out' => [
                'sys_history',
                'sys_log',
                'tx_powermail_domain_model_mail',
                'tx_powermail_domain_model_answer',
            ],
        ],
    ],
]);
