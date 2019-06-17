<?php

namespace Deployer;

use Deployer\Exception\Exception;
use SourceBroker\DeployerExtendedDatabase\Utility\ConsoleUtility;

function config_createAdmin_generatePassword($chars)
{
    $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($data), 0, $chars);
}

task('config:createadmin', function () {
    $targetName = input()->getArgument('stage') ?? 'local';

    $username = input()->getOption('username');
    if (!$username) {
        $username = getenv('DEP_ADMIN_USERNAME');
    }
    if (!$username) {
        throw new Exception('Username has to be defined (env DEP_ADMIN_USERNAME or --username argument).', 1560175164001);
    }

    $password = input()->getOption('password');
    if (!$password) {
        $password = config_createAdmin_generatePassword(16);
        output()->writeln('<comment>Generated password: "' . $password . '"</comment>');
    }

    try {
        $currentPath = get('deploy_path');
        if (get('vhost_nocurrent', false) == false) {
            $currentPath .= '/' . (test('[ -L {{deploy_path}}/release ]') ? 'release' : 'current');
        }

        run('cd '. $currentPath .' && ./vendor/bin/typo3cms backend:createadmin --username ' . $username . ' --password=' . $password);
        output()->writeln('<info>Admin for instance "' . $targetName . '" has been created.</info>');
    } catch (\Exception $e) {
        output()->writeln('<error>Admin for instance "' . $targetName . '" could not be created.</error>');
        if ($targetName != 'local') {
            throw $e;
        }
    }

    if ($targetName == 'local' || empty($targetName)) {
        $environments = Deployer::get()->environments;
        $verbosity = (new ConsoleUtility())->getVerbosityAsParameter(output());

        foreach ($environments as $envName => $environment) {
            if ($envName == 'local') {
                continue;
            }

            try {
                runLocally('./vendor/bin/dep config:createadmin ' . $envName . ' --username ' . $username . ' --password=' . $password . ' ' . $verbosity);
                output()->writeln('<info>Admin for instance "' . $envName . '" has been created.</info>');
            } catch (\Exception $e) {
                output()->writeln('<error>Admin for instance "' . $envName . '" could not be created.</error>');
            }
        }
    }
});
