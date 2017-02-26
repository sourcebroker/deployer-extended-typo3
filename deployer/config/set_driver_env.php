<?php

namespace Deployer;

use SourceBroker\DeployerExtendedTypo3\Drivers\Typo3EnvDriver;

add('shared_files', ['{{web_path}}.env']);

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