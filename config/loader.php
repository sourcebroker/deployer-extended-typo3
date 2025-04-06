<?php

return [
    [
        // Load all from "deployer" folder of "sourcebroker/deployer-extended" package
        'package' => 'sourcebroker/deployer-extended',
    ],
    [
        // Add custom "bin/composer" where composer version can be chosen and updated. https://github.com/sourcebroker/deployer-extended?tab=readme-ov-file#bincomposer
        'path' => 'vendor/sourcebroker/deployer-extended/includes/settings/bin_composer.php',
    ],
    [
        // Add custom "bin/php" where php version is detected based on composer.json. https://github.com/sourcebroker/deployer-extended?tab=readme-ov-file#binphp
        'path' => 'vendor/sourcebroker/deployer-extended/includes/settings/bin_php.php',
    ],
    [
        // Add custom task "releases" which solved performance problems of original deployer "release" task. https://github.com/sourcebroker/deployer-extended?tab=readme-ov-file#releases
        'path' => 'vendor/sourcebroker/deployer-extended/includes/tasks/releases.php',
    ],
    [
        // Load all from "deployer" folder of "sourcebroker/deployer-extended-typo3" package
        'package' => 'sourcebroker/deployer-extended-typo3',
    ],
];
