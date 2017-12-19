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

// Look on https://github.com/sourcebroker/deployer-extended#buffer-start for docs
set('buffer_config', [
        'index.php' => [
            'entrypoint_filename' => 'index.php',
        ],
        'typo3/index.php' => [
            'entrypoint_filename' => 'typo3/index.php',
        ],
        'typo3/cli_dispatch.phpsh' => [
            'entrypoint_filename' => 'typo3/cli_dispatch.phpsh',
        ],
        'typo3/deprecated.php' => [
            'entrypoint_filename' => 'typo3/deprecated.php',
        ],
        'typo3/install/index.php' => [
            'entrypoint_filename' => 'typo3/install/index.php',
        ],
    ]
);

// Look https://github.com/sourcebroker/deployer-extended-database for docs
set('db_databases',
    [
        'database_default' => [
            get('db_default'),
            (new \SourceBroker\DeployerExtendedTypo3\Drivers\Typo3EnvDriver)->getDatabaseConfig(
                [
                    'host' => 'TYPO3__DB__host',
                    'port' => 'TYPO3__DB__port',
                    'dbname' => 'TYPO3__DB__database',
                    'user' => 'TYPO3__DB__username',
                    'password' => 'TYPO3__DB__password',
                ]
            ),
        ]
    ]
);