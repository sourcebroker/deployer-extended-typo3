<?php

namespace Deployer;

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
    'caching_tables' => [
        'cf_.*'
    ],
    'ignore_tables_out' => [
        'cf_.*',
        'cache_.*',
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
    'post_sql_in' => 'UPDATE sys_domain SET hidden = 1;
                          UPDATE sys_domain SET sorting = sorting + 10;
                          UPDATE sys_domain SET sorting=1, hidden = 0 WHERE tx_local_context="local";
        '
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