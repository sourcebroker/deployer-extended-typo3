<?php

namespace Deployer;

use Deployer\Exception\Exception;
use SourceBroker\DeployerExtendedTypo3\Utility\ConsoleUtility;

task('config:createadmin', function () {
    $username = $_ENV['DEP_CONFIG_CREATEADMIN_USERNAME'] ?? null;
    if (empty($username)) {
        $username = ask('Give new admin username');
    }
    if (empty($username)) {
        throw new Exception('Username has to be defined (env DEP_CONFIG_CREATEADMIN_USERNAME or answer a question).', 1560175164);
    }
    $password = substr(str_shuffle('1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz'), 0, 20);
    output()->writeln('<comment>Generated password: "' . $password . '"</comment>');

    if (empty(get('argument_stage'))) {
        try {
            runLocally('{{local/bin/php}} {{local/bin/typo3cms}} backend:createadmin --username ' . escapeshellarg($username) . ' --password ' . escapeshellarg($password));
            output()->writeln('<info>Admin for instance "' . get('default_stage') . '" has been created.</info>');
        } catch (\Exception $e) {
            output()->writeln('<error>Admin for instance "' . get('default_stage') . '" could not be created.</error>');
        }
    } else {
        try {
            $activeDir = get('deploy_path') . (test('[ -e {{deploy_path}}/release ]') ? '/release' : '/current');
            $verbosity = (new ConsoleUtility())->getVerbosityAsParameter();
            run('cd ' . $activeDir . ' && {{bin/php}} {{bin/deployer}} config:createadmin ' . get('argument_stage') . ' --username ' . escapeshellarg($username) . ' --password ' . escapeshellarg($password) . ' ' . $verbosity);
        } catch (\Exception $e) {
            output()->writeln('<error>Admin for instance "' . get('argument_stage') . '" could not be created.</error>');
            throw $e;
        }
    }
})->desc('Create TYPO3 admin user on remote instance');
