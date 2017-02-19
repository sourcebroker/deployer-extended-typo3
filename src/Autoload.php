<?php

// run only if called from deployer.phar context
if (function_exists('\Deployer\set')) {

    \SourceBroker\DeployerExtended\Utility\FileUtility::requireFilesFromDirectoryReqursively(__DIR__ . '/../deployer/config/', '/^set_driver/');
    \SourceBroker\DeployerExtended\Utility\FileUtility::requireFilesFromDirectoryReqursively(__DIR__ . '/../deployer/task/');

    if ((new \SourceBroker\DeployerExtendedTypo3\Drivers\Typo3Driver)->detectConfig(\Deployer\get('current_dir'))) {
        require_once(__DIR__ . '/../deployer/config/set_driver_default.php');
    } elseif ((new \SourceBroker\DeployerExtendedTypo3\Drivers\Typo3EnvDriver)->detectConfig(\Deployer\get('current_dir'))) {
        require_once(__DIR__ . '/../deployer/config/set_driver_env.php');
    }

}