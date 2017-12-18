<?php

namespace Deployer;

set('shared_dirs', [
        'fileadmin',
        'uploads',
        'typo3temp/assets/_processed_',
        'typo3temp/assets/images',
        'typo3temp/var/logs',
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
        'typo3/install.php' => [
            'entrypoint_filename' => 'typo3/install.php',
        ]
    ]
);

