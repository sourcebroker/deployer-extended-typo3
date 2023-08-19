<?php

namespace SourceBroker\DeployerExtendedTypo3\Drivers;

use Deployer\Exception\ConfigurationException;
use SourceBroker\DeployerInstance\Configuration;

/**
 * Use /vendor/bin/typo3cms configuration:showactive DB to get database data directly from TYPO3.
 */
class Typo3CmsDriver
{
    public function getDatabaseConfig(string $databaseConfiguration = 'Default'): array
    {
        $localHost = Configuration::getLocalHost();
        $command =  $localHost->get('local/bin/php') .
            ' ' . $localHost->get('local/bin/typo3cms')
            . ' configuration:showactive DB --json';

        exec($command, $output, $resultCode);

        if ($resultCode > 0) {
            throw new ConfigurationException('Command: "' . $command . '" returned error code: "' . $resultCode . '"');
        }

        $config = json_decode(implode("\n", $output), true, 512,
            JSON_THROW_ON_ERROR)['Connections'][$databaseConfiguration];
        $config['flags'] = $config['driverOptions']['flags'] ?? 0;

        return $config;
    }
}
