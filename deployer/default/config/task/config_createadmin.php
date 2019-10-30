<?php

namespace Deployer;

use Deployer\Exception\Exception;
use SourceBroker\DeployerExtendedDatabase\Utility\ConsoleUtility;

task('config:createadmin', function () {

    $username = getenv('DET_CONFIG_CREATEADMIN_USERNAME');
    if (!$username) {
        $username = ask('Give new admin username');
    }
    if (!$username) {
        throw new Exception('Username has to be defined (env DEP_ADMIN_USERNAME or answer a question).',
            1560175164001);
    }
    $password = substr(
        str_shuffle('1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz'),
        0,
        20);
    output()->writeln('<comment>Generated password: "' . $password . '"</comment>');

    if (get('current_stage') == get('target_stage')) {
        try {
            runLocally('{{local/bin/php}} {{local/bin/typo3cms}} backend:createadmin --username ' . escapeshellarg($username) . ' --password ' . escapeshellarg($password));
            output()->writeln('<info>Admin for instance "' . get('target_stage') . '" has been created.</info>');
        } catch (\Exception $e) {
            output()->writeln('<error>Admin for instance "' . get('target_stage') . '" could not be created.</error>');
        }
    } else {
        try {
            $activeDir = test('[ -e {{deploy_path}}/release ]') ?
                get('deploy_path') . '/release' :
                get('deploy_path') . '/current';
            $verbosity = (new ConsoleUtility())->getVerbosityAsParameter(output());
            run('cd ' . $activeDir . ' && {{bin/php}} {{bin/deployer}} config:createadmin ' . get('target_stage') . ' --username ' . escapeshellarg($username) . ' --password ' . escapeshellarg($password) . ' ' . $verbosity);
            output()->writeln('<info>Admin for instance "' . get('target_stage') . '" has been created.</info>');
        } catch (\Exception $e) {
            output()->writeln('<error>Admin for instance "' . get('target_stage') . '" could not be created.</error>');
            throw $e;
        }
    }
})->desc('Create TYPO3 admin user on target instance');
