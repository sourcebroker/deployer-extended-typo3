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

            // TYPO3 8.0
            if (false !== getenv('TYPO3__DB__Connections__Default__dbname')) {
                $dbConfig['host'] = getenv('TYPO3__DB__Connections__Default__host');
                $dbConfig['port'] = getenv('TYPO3__DB__Connections__Default__port') ? getenv('TYPO3__DB__Connections__Default__port') : 3306;
                $dbConfig['dbname'] = getenv('TYPO3__DB__Connections__Default__dbname');
                $dbConfig['user'] = getenv('TYPO3__DB__Connections__Default__user');
                $dbConfig['password'] = getenv('TYPO3__DB__Connections__Default__password');
            } else {
                // TYPO3 7.6 and TYPO3 6.2
                $dbConfig['host'] = getenv('TYPO3__DB__host');
                $dbConfig['port'] = getenv('TYPO3__DB__port') ? getenv('TYPO3__DB__port') : 3306;
                $dbConfig['dbname'] = getenv('TYPO3__DB__database');
                $dbConfig['user'] = getenv('TYPO3__DB__username');
                $dbConfig['password'] = getenv('TYPO3__DB__password');
            }

            $dbConfig['post_sql_in_with_markers'] = '
                              UPDATE sys_domain SET hidden = 1;
                              UPDATE sys_domain SET sorting = sorting + 10;
                              UPDATE sys_domain SET sorting=1, hidden = 0 WHERE domainName IN ({{domainsSeparatedByComma}});
                              ';

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
        if (is_file(rtrim($projectRootPath, '/') . '/.env') || is_file($projectRootPath . '/.env.dist')) {
            return true;
        } else {
            return false;
        }
    }

}