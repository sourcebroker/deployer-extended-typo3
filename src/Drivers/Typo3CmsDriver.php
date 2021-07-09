<?php

namespace SourceBroker\DeployerExtendedTypo3\Drivers;

use Deployer\Exception\ConfigurationException;
use RuntimeException;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * Use /vendor/bin/typo3cms configuration:showactive DB to get database data directly from TYPO3.
 */
class Typo3CmsDriver
{
    /**
     * @param string $databaseConfiguration
     * @return array
     */
    public function getDatabaseConfig(string $databaseConfiguration = 'Default'): array
    {
        exec($this->getPhpExecCommand() . ' ./vendor/bin/typo3cms configuration:showactive DB --json', $output, $resultCode);
        if ($resultCode === 0) {
            return json_decode(implode("\n", $output), true)['Connections'][$databaseConfiguration];
        }
        throw new ConfigurationException('typo3cms configuration:showactive returned error code: "' . $resultCode . '"');
    }

    protected function getPhpExecCommand(): string
    {
        $finder = new PhpExecutableFinder();
        $phpPath = $finder->find(true);
        if (!$phpPath) {
            throw new RuntimeException('Failed to locate PHP binary to execute ' . $phpPath);
        }
        return $phpPath;
    }
}