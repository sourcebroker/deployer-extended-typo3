<?php

namespace SourceBroker\DeployerExtendedTypo3;

use SourceBroker\DeployerLoader\Load;

class Loader
{
    public function __construct()
    {
        /** @noinspection PhpIncludeInspection */
        require_once 'recipe/common.php';
        new Load([
                ['path' => 'vendor/sourcebroker/deployer-instance/deployer'],
                ['path' => 'vendor/sourcebroker/deployer-extended/deployer'],
                ['path' => 'vendor/sourcebroker/deployer-extended-database/deployer'],
                ['path' => 'vendor/sourcebroker/deployer-extended-media/deployer'],
                ['path' => 'vendor/sourcebroker/deployer-extended-typo3/deployer/default'],
                [
                    'path' => 'vendor/sourcebroker/deployer-extended-typo3/deployer/' .
                        $this->getTypo3MajorVersion($this->projectRootAbsolutePath())
                ]
            ]
        );
    }

    /**
     * @param $rootDir
     * @return int|null
     * @throws \Exception
     * @internal param $params
     */
    public function getTypo3MajorVersion($rootDir)
    {
        $typo3MajorVersion = null;
        $rootDir = rtrim($rootDir, '/');
        if(!file_exists($rootDir . '/typo3')) {
            $rootDir = $rootDir . '/public';
        }
        if (file_exists($rootDir . '/typo3/backend.php')) {
            $typo3MajorVersion = 6;
        } elseif (file_exists($rootDir . '/typo3/sysext/core/Documentation/Changelog-10.rst')) {
            $typo3MajorVersion = 10;
        } elseif (file_exists($rootDir . '/typo3/sysext/core/Documentation/Changelog-9.rst')) {
            $typo3MajorVersion = 9;
        } elseif (file_exists($rootDir . '/typo3/sysext/core/Documentation/Changelog-8.rst')) {
            $typo3MajorVersion = 8;
        } elseif (file_exists($rootDir . '/typo3/sysext/core/Documentation/Changelog-7.rst')) {
            $typo3MajorVersion = 7;
        }
        if (null === $typo3MajorVersion) {
            throw new \Exception('Cannot figure out the TYPO3 major version.');
        }
        return $typo3MajorVersion;
    }

    /**
     * Return absolute path to project root so we can add it to relative pathes.
     *
     * @return bool|string
     */
    protected function projectRootAbsolutePath()
    {
        return realpath(__DIR__ . '/../../../..');
    }
}
