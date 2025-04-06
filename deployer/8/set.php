<?php

namespace Deployer;

set('shared_dirs', function () {
    return [
        get('web_path') . 'fileadmin',
        get('web_path') . 'uploads',
        get('web_path') . 'typo3temp/assets/_processed_',
        get('web_path') . 'typo3temp/assets/images',
        get('web_path') . 'typo3temp/var/log',
        get('web_path') . 'typo3temp/var/transient',
    ];
});

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
        'fe_session_data',
        'sys_file_processedfile',
        'sys_history',
        'sys_log',
        'sys_refindex',
        'tx_devlog',
        'tx_extensionmanager_domain_model_extension',
        'tx_realurl_.*',
        'tx_powermail_domain_model_mail',
        'tx_powermail_domain_model_mails',
        'tx_powermail_domain_model_answer',
        'tx_powermail_domain_model_answers',
        'tx_solr_.*',
        'tx_crawler_queue',
        'tx_crawler_process',
    ],
    'post_sql_in' => '',
    'post_sql_in_markers' => '
                              UPDATE sys_domain SET hidden = 1;
                              UPDATE sys_domain SET sorting = sorting + 10;
                              UPDATE sys_domain SET sorting=1, hidden = 0 WHERE domainName IN ({{domainsSeparatedByComma}});
                              '
]);

// Look https://github.com/sourcebroker/deployer-extended-database for docs
set('db_databases',
    [
        'database_default' => [
            get('db_default'),
            !empty($_ENV['IS_DDEV_PROJECT']) ? get('db_ddev_database_config') :
                (new \SourceBroker\DeployerExtendedTypo3\Drivers\Typo3EnvDriver)->getDatabaseConfig(
                    [
                        'host' => 'TYPO3__DB__Connections__Default__host',
                        'port' => 'TYPO3__DB__Connections__Default__port',
                        'dbname' => 'TYPO3__DB__Connections__Default__dbname',
                        'user' => 'TYPO3__DB__Connections__Default__user',
                        'password' => 'TYPO3__DB__Connections__Default__password',
                        'ssl_key' => 'TYPO3__DB__Connections__Default__ssl_key',
                        'ssl_cert' => 'TYPO3__DB__Connections__Default__ssl_cert',
                        'ssl_ca' => 'TYPO3__DB__Connections__Default__ssl_ca',
                        'ssl_capath' => 'TYPO3__DB__Connections__Default__ssl_capath',
                        'ssl_cipher' => 'TYPO3__DB__Connections__Default__ssl_cipher',
                        'flags' => 'TYPO3__DB__Connections__Default__driverOptions__flags'
                    ]
                ),
        ]
    ]
);
