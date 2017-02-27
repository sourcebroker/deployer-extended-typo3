<?php

namespace Deployer;

set('bin/typo3cms', './vendor/bin/typo3cms');

set('shared_dirs', [
        'fileadmin',
        'uploads',
        'typo3temp/_processed_',
        'typo3temp/pics',
        'typo3temp/logs'
    ]
);

set('writable_dirs', [
        'typo3conf',
        'typo3temp',
        'uploads',
        'fileadmin'
    ]
);

set('writable_use_sudo', false);

set('clear_paths', [
    '.git',
    '.gitignore',
    'composer.json',
    'composer.lock',
    'composer.phar',
    '.gitattributes',
    '.env.dist'
]);

set('clear_use_sudo', false);

set('db_default', [
    '`' => [
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