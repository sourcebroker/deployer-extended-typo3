<?php

namespace Deployer;

use SourceBroker\DeployerExtendedTypo3\Drivers\Typo3EnvDriver;

set('bin/typo3cms', './vendor/bin/typo3cms');

// for TYPO3 8.7 we need to have php7
//set('bin/php', function () {
//    return run('which php7.1')->toString();
//});
//set('composer_options', get('composer_options') . ' --ignore-platform-reqs');

// Array keys for better unsetting if someone would like to change only part of configuration
add('buffer_config', [
        'typo3/index.php' => [
            'entrypoint_filename' => 'typo3/index.php',
        ],
        'typo3/cli_dispatch.phpsh' => [
            'entrypoint_filename' => 'typo3/cli_dispatch.phpsh',
        ]
    ]
);

set('shared_dirs', [
        'fileadmin',
        'uploads',
        'typo3temp/assets/_processed_',
        'typo3temp/assets/images',
        'typo3temp/var/logs',
    ]
);

set('file_remove2steps_items', ['typo3temp/Cache']);

set('shared_files', ['.env']);

set('writable_dirs', [
        'typo3conf',
        'typo3temp',
        'uploads',
        'fileadmin'
    ]
);

set('clear_paths', [
    '.git',
    '.gitignore',
    'composer.json',
    'composer.lock',
    'composer.phar',
    '.gitattributes',
    '.env.dist',
    'dynamicReturnTypeMeta.json'
]);

set('db_default', [
    'caching_tables' => [
        'cf_.*'
    ],
    'ignore_tables_out' => [
        'cf_.*',
        'cache_.*',
        'be_sessions',
        'sys_history',
        'sys_file_processedfile',
        'sys_log',
        'sys_refindex',
        'tx_devlog',
        'tx_extensionmanager_domain_model_extension',
        'tx_realurl_chashcache',
        'tx_realurl_errorlog',
        'tx_realurl_pathcache',
        'tx_realurl_uniqalias',
        'tx_realurl_urldecodecache',
        'tx_realurl_urlencodecache',
        'tx_powermail_domain_model_mails',
        'tx_powermail_domain_model_answers',
        'tx_solr_.*',
        'tx_crawler_queue',
        'tx_crawler_process',
    ],
    'ignore_tables_in' => [],
    'post_sql_out' => '',
    'post_sql_in' => ''
]);

set('media',
    [
        'filter' => [
            '+ /fileadmin/',
            '- /fileadmin/_processed_/*',
            '+ /fileadmin/**',
            '+ /uploads/',
            '+ /uploads/**',
            '- *'
        ]
    ]);

set(
    'db_databases',
    [
        (new Typo3EnvDriver)->getDatabaseConfig([
            'configDir' => get('current_dir'),
            'database_code' => 'database_default'
        ]),
        ['database_default' => get('db_default')],
    ]
);

set('instance', (new Typo3EnvDriver)->getInstanceName(['configDir' => get('current_dir')]));

// Its used when you do not put any stage into task parameter.
// Thanks to that you can do: "dep db:export" and not "dep db:export local"
set('default_stage', get('instance'));