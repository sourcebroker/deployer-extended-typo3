<?php


if (defined('DEPLOYER') && PHP_SAPI === 'cli') {

    require_once 'recipe/common.php';

    if (is_dir(getcwd()) && file_exists(getcwd() . '/deploy.php')) {
        \Deployer\set('current_dir', getcwd());
    } else {
        throw new \RuntimeException('Can not set "current_dir" var.');
    }

    \SourceBroker\DeployerExtended\Utility\FileUtility::requireFilesFromDirectoryReqursively(__DIR__ . '/../deployer/');
}