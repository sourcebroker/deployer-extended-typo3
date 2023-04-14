<?php

namespace SourceBroker\DeployerExtendedTypo3;

use SourceBroker\DeployerLoader\Load;
use Deployer\Exception\GracefulShutdownException;

class Loader
{
    public function __construct()
    {
        require_once 'recipe/common.php';
        $typo3MajorVersion = $this->getTypo3MajorVersion($this->projectRootAbsolutePath());
        new Load([
                ['path' => 'vendor/sourcebroker/deployer-instance/deployer'],
                ['path' => 'vendor/sourcebroker/deployer-extended/deployer'],
                ['path' => 'vendor/sourcebroker/deployer-extended-database/deployer'],
                ['path' => 'vendor/sourcebroker/deployer-extended-media/deployer'],
                ['path' => 'vendor/sourcebroker/deployer-extended-typo3/deployer/default'],
                [
                    'path' => 'vendor/sourcebroker/deployer-extended-typo3/deployer/' .
                        ($typo3MajorVersion >= 10 ? '10-12' : $typo3MajorVersion)
                ],
            ]
        );
    }

    /**
     * @param $rootDir
     * @return int|null
     * @throws GracefulShutdownException
     */
    public function getTypo3MajorVersion($rootDir): ?int
    {
        $typo3MajorVersion = null;
        $rootDir = rtrim($rootDir, '/');

        $changelogFilesRoot = glob($rootDir . '/typo3/sysext/core/Documentation/Changelog-*.rst');
        $changelogFilesRootSubDirs = glob($rootDir . '/*/typo3/sysext/core/Documentation/Changelog-*.rst');
        $changelogFilesVendor = glob($rootDir . '/vendor/typo3/cms-core/Documentation/Changelog-*.rst');
        $changelogFiles = array_merge(
            is_array($changelogFilesRoot) ? $changelogFilesRoot : [],
            is_array($changelogFilesRootSubDirs) ? $changelogFilesRootSubDirs : [],
            is_array($changelogFilesVendor) ? $changelogFilesVendor : []
        );

        $changelogFilesIntegers = array_map(static function ($changelogFile) {
            preg_match('/Changelog-(\\d+)\\.rst/', $changelogFile, $matches);

            return $matches[1] ?? 0;
        }, $changelogFiles);

        if (!empty($changelogFilesIntegers)) {
            asort($changelogFilesIntegers, SORT_NUMERIC);
            $typo3MajorVersion = array_pop($changelogFilesIntegers);
        }

        if ($typo3MajorVersion === null && file_exists($rootDir . '/typo3/backend.php')) {
            $typo3MajorVersion = 6;
        }

        if (null === $typo3MajorVersion) {
            throw new GracefulShutdownException('Cannot figure out the TYPO3 major version.');
        }

        return $typo3MajorVersion;
    }

    public function projectRootAbsolutePath(): string
    {
        $dir = __DIR__;
        while ((!is_file($dir . '/composer.json') && !is_file($dir . '/root_dir') && !is_file($dir . '/deploy.php')) || basename($dir) === 'deployer-extended-typo3') {
            if ($dir === \dirname($dir)) {
                break;
            }
            $dir = \dirname($dir);
        }

        return $dir;
    }
}
