<?php

namespace SourceBroker\DeployerExtendedTypo3\Drivers;

use SourceBroker\DeployerInstance\Env;

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
        $env = new Env();
        $env->load($absolutePathWithConfig);
        $dbSettings = [];
        foreach ($dbMappingFields as $key => $dbMappingField) {
            $dbSettings[$key] = $env->get($dbMappingField);
        }
        return $dbSettings;
    }
}
