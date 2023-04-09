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
    public function getDatabaseConfig(string $databaseConfiguration = 'Default'): array
    {
        exec($this->getPhpExecCommand() . ' ' . $this->getTypo3ExecCommand() . ' configuration:showactive DB --json',
            $output, $resultCode);
        if ($resultCode === 0) {
            return json_decode(implode("\n", $output), true, 512,
                JSON_THROW_ON_ERROR)['Connections'][$databaseConfiguration];
        }
        throw new ConfigurationException('typo3cms configuration:showactive returned error code: "' . $resultCode . '"');
    }

    protected function getPhpExecCommand(): string
    {
        $finder = new PhpExecutableFinder();
        $phpPath = $finder->find();
        if (!$phpPath) {
            throw new RuntimeException('Failed to locate PHP binary to execute ' . $phpPath);
        }
        return $phpPath;
    }

    protected function getTypo3ExecCommand(): string
    {
        if (file_exists('./vendor/bin/typo3cms')) {
            return './vendor/bin/typo3cms';
        }
        if (file_exists('./vendor/bin/typo3')) {
            return './vendor/bin/typo3';
        }
        throw new RuntimeException('Could not find executable of TYPO3 console');
    }
}
