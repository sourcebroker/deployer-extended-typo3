<?php

namespace SourceBroker\DeployerExtendedTypo3\Drivers;

use Dotenv\Dotenv;

/**
 * Class Typo3EnvDriver
 * @package SourceBroker\DeployerExtended\Drivers
 */
class Typo3EnvDriver
{

    /**
     * @param null $params
     * @return array
     * @throws \Exception
     */
    public function getDatabaseConfig($params = null)
    {
        if (file_exists($params['configDir'])) {
            $dotenv = new Dotenv($params['configDir']);
            $dotenv->load();
            //$dotenv->required(['TYPO3__DB__host', 'TYPO3__DB__database', 'TYPO3__DB__username', 'TYPO3__DB__password'])->notEmpty();

            $dbConfig['host'] = getenv('TYPO3__DB__host');
            $dbConfig['port'] = getenv('TYPO3__DB__port') ? getenv('TYPO3__DB__port') : 3306;
            $dbConfig['dbname'] = getenv('TYPO3__DB__database');
            $dbConfig['user'] = getenv('TYPO3__DB__username');
            $dbConfig['password'] = getenv('TYPO3__DB__password');

            return [$params['database_code'] => $dbConfig];

        } else {
            throw new \Exception('Missing file "' . $params['configDir'] . '".env.');
        }


    }

    /**
     * Return the instance name for project
     *
     * @param $params
     * @return string
     * @throws \Exception
     */
    public function getInstanceName($params = null)
    {
        if (file_exists($params['configDir'])) {
            $dotenv = new Dotenv($params['configDir']);
            $dotenv->load();
            $dotenv->required(['INSTANCE'])->notEmpty();
            return getenv('INSTANCE');

        } else {
            throw new \Exception('Missing file "' . $params['configDir'] . '".env.');
        }
    }

    public function detectConfig($projectRootPath)
    {
        if(is_file(rtrim($projectRootPath, '/') . '/.env') || is_file($projectRootPath . '/.env.dist')) {
            return true;
        } else {
            return false;
        }
    }
}