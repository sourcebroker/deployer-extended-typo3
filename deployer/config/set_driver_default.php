<?php

namespace Deployer;

use SourceBroker\DeployerExtendedTypo3\Drivers\Typo3Driver;

add('shared_files', ['{{web_path}}typo3conf/AdditionalConfiguration_custom.php']);

set(
    'db_databases',
    [
        (new Typo3Driver)->getDatabaseConfig([
            'file' => get('current_dir') . '/typo3conf/AdditionalConfiguration_custom.php',
            'database_code' => 'database_default'
        ]),
        ['database_default' => get('db_default')],
    ]
);

set(
    'instance',
    (new Typo3Driver)->getInstanceName(['file' => get('current_dir') . '/typo3conf/AdditionalConfiguration_custom.php'])
);