<?php

namespace SourceBroker\DeployerExtendedTypo3\Drivers;

use SourceBroker\DeployerInstance\Env;

/**
 * Class Typo3EnvDriver
 */
class Typo3EnvDriver
{
    /**
     * @param null $dbMappingFields
     * @param null $absolutePathWithConfig
     * @return array
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
