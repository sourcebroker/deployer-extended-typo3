<?php

namespace Deployer;

set('shared_dirs', [
    get('web_path') . 'fileadmin',
    get('web_path') . 'uploads',
    get('web_path') . 'typo3temp/assets/_processed_',
    get('web_path') . 'typo3temp/assets/images',
    get('web_path') . 'typo3temp/var/log',
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
        ],
        'typo3/cli_dispatch.phpsh' => [
            'entrypoint_filename' => get('web_path') . 'typo3/cli_dispatch.phpsh',
        ]
    ]
);
