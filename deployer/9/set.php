<?php

namespace Deployer;

set('shared_dirs', [
    get('web_path') . 'fileadmin',
    get('web_path') . 'uploads',
    get('web_path') . 'typo3temp/assets/_processed_',
    get('web_path') . 'typo3temp/assets/images',
    get('web_path') . 'typo3temp/var/log',
]);

// Look https://github.com/sourcebroker/deployer-extended-database for docs
set('db_default', [
    'truncate_tables' => [
        // Do not truncate caching tables "cf_cache_imagesizes" and "cf_cache_pages_tags" as the image settings are not
        // changed frequently and regenerating images is processor core extensive.
        '(?!cf_cache_imagesizes)cf_.*',
        'cache_.*'
    ],
    'ignore_tables_out' => [
        'cf_.*',
        'cache_.*',
        'be_sessions',
        'fe_sessions',
        'sys_file_processedfile',
        'sys_history',
        'sys_log',
        'sys_refindex',
        'tx_devlog',
        'tx_extensionmanager_domain_model_extension',
        'tx_powermail_domain_model_mail',
        'tx_powermail_domain_model_mails',
        'tx_powermail_domain_model_answer',
        'tx_powermail_domain_model_answers',
        'tx_solr_.*',
        'tx_crawler_queue',
        'tx_crawler_process',
    ],
    'post_sql_in' => '',
    'post_sql_in_markers' => ''
]);

// Look on https://github.com/sourcebroker/deployer-extended#buffer-start for docs
set('buffer_config', [
        'index.php' => [
            'entrypoint_filename' => get('web_path') . 'index.php',
        ],
        'typo3/index.php' => [
            'entrypoint_filename' => get('web_path') . 'typo3/index.php',
        ],
        'typo3/install.php' => [
            'entrypoint_filename' => get('web_path') . 'typo3/install.php',
        ]
    ]
);

