<?php

namespace Deployer;

set('branch_detect_to_deploy', false);

set('allow_anonymous_stats', false);

set('shared_files', [
    '.env'
]);

set('writable_dirs', function () {
    return [
        get('web_path') . 'typo3conf',
        get('web_path') . 'typo3temp',
        get('web_path') . 'uploads',
        get('web_path') . 'fileadmin'
    ];
});

set('default_timeout', 900);

set('clear_paths', [
    '.ddev',
    '.envrc',
    '.git',
    '.githooks',
    '.gitignore',
    '.gitattributes',
    '.env.dist',
    '.php_cs',
    'dynamicReturnTypeMeta.json',
    'composer.json',
    'composer.lock',
    'composer.phar',
    'phpstan.neon'
]);

set('bin/typo3cms', './vendor/bin/typo3cms');
set('local/bin/typo3cms', './vendor/bin/typo3cms');

// Look https://github.com/sourcebroker/deployer-extended-media for docs
set('media_allow_push_live', false);
set('media_allow_copy_live', false);
set('media_allow_link_live', false);
set('media_allow_pull_live', false);
set('media', function () {
    return [
        'filter' => [
            '+ /' . get('web_path') . '',
            '+ /' . get('web_path') . 'fileadmin/',
            '- /' . get('web_path') . 'fileadmin/_processed_/*',
            '+ /' . get('web_path') . 'fileadmin/**',
            '+ /' . get('web_path') . 'uploads/',
            '+ /' . get('web_path') . 'uploads/**',
            '- *'
        ]
    ];
});

// Look https://github.com/sourcebroker/deployer-extended-database for docs
set('db_allow_push_live', false);
set('db_allow_pull_live', false);
set('db_allow_copy_live', false);
set('db_ddev_database_config', [
    'host' => 'db',
    'port' => getenv('DDEV_HOST_DB_PORT'),
    'dbname' => 'db',
    'user' => 'db',
    'password' => 'db',
]);

// Look https://github.com/sourcebroker/deployer-extended-database#db-dumpclean for docs
set('db_dumpclean_keep', [
    '*' => 5,
    'live' => 10,
]);


