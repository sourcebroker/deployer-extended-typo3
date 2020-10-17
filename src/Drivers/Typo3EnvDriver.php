<?php

namespace SourceBroker\DeployerExtendedTypo3\Drivers;

use Symfony\Component\Dotenv\Dotenv;

/**
 * Class Typo3EnvDriver
 * @package SourceBroker\DeployerExtended\Drivers
 */
class Typo3EnvDriver
{
    /**
     * @param null $dbMappingFields
     * @param null $absolutePathWithConfig
     * @return array
     * @throws \Exception
     * @internal param null $params
     */
    public function getDatabaseConfig($dbMappingFields = null, $absolutePathWithConfig = null): array
    {
        $absolutePathWithConfig = $absolutePathWithConfig ?? getcwd();
        $absolutePathWithConfig = rtrim($absolutePathWithConfig, DIRECTORY_SEPARATOR);
        $dbSettings = [];
        if (file_exists($absolutePathWithConfig . '/.env')) {
            (new Dotenv())->load($absolutePathWithConfig . '/.env');
            foreach ($dbMappingFields as $key => $dbMappingField) {
                $dbSettings[$key] = $this->getenv($dbMappingField);
            }
        } else {
            throw new \Exception('Missing file "' . $absolutePathWithConfig . '"/.env.');
        }
        return $dbSettings;
    }

    private function getenv($env)
    {
        return $_ENV[$env] ?? null;
    }
}
