<?php

namespace SourceBroker\DeployerExtendedTypo3\Drivers;


/**
 * Class Typo3Driver
 * @package SourceBroker\DeployerExtended\Drivers
 */
class Typo3Driver
{
    /**
     * @param null $params
     * @return array
     * @throws \Exception
     */
    public function getDatabaseConfig($params = null)
    {
        $filename = $params['file'];
        if (file_exists($filename)) {
            require $filename;
            if (isset($GLOBALS['TYPO3_CONF_VARS']) && isset($GLOBALS['TYPO3_CONF_VARS']['DB'])) {
                $config = $GLOBALS['TYPO3_CONF_VARS']['DB'];
                if (isset($config['host'])) {
                    $typo_db_host_parts = explode(':', $config['host']);
                    $dbConfig['host'] = count($typo_db_host_parts) > 1 ? $typo_db_host_parts[0] : $config['host'];
                    $dbConfig['port'] = count($typo_db_host_parts) > 1 ? $typo_db_host_parts[1] : 3306;
                }

                if (isset($config['database'])) {
                    $dbConfig['dbname'] = $config['database'];
                }

                if (isset($config['username'])) {
                    $dbConfig['user'] = $config['username'];
                }

                if (isset($config['password'])) {
                    $dbConfig['password'] = $config['password'];
                }
            } elseif (isset($typo_db_username) && isset($typo_db_password) && isset($typo_db_host) && isset($typo_db)) {

                $typo_db_host_parts = explode(':', $typo_db_host);
                $dbConfig['host'] = count($typo_db_host_parts) > 1 ? $typo_db_host_parts[0] : $typo_db_host;
                $dbConfig['port'] = count($typo_db_host_parts) > 1 ? $typo_db_host_parts[1] : 3306;
                $dbConfig['dbname'] = $typo_db;
                $dbConfig['user'] = $typo_db_username;
                $dbConfig['password'] = $typo_db_password;
            }

        } else {
            throw new \Exception('Missing file "' . $filename . '" when trying to get TYPO3 configuration file.');
        }
        return [$params['database_code'] => $dbConfig];
    }

    /**
     * Return the instance name for project
     *
     * @param null $params
     * @return string
     * @throws \Exception
     */
    public function getInstanceName($params = null)
    {
        $filename = $params['file'];
        if (file_exists($filename)) {
            /** @noinspection PhpIncludeInspection */
            require $filename;

            $contextStringParts = explode('/', getenv('TYPO3_CONTEXT'));
            $typo3ContextInstance = (isset($contextStringParts[2])) ? $contextStringParts[2] : '';
            if (isset($typo3ContextInstance) && $typo3ContextInstance) {
                $instanceName = strtolower($typo3ContextInstance);
            } else {
                throw new \Exception("\nTYPO3_CONTEXT environment variable is not set. \nIf this is your local instance then please put following line: \nputenv('TYPO3_CONTEXT=Development//Local');  \nin configuration file: typo3conf/AdditionalConfiguration_custom.php. \n\n");
            }
            return $instanceName;
        } else {
            throw new \Exception('Missing file "' . $filename . '" when trying to get TYPO3 configuration file.');
        }
    }

    public function detectConfig($projectRootPath)
    {
        if (is_file(rtrim($projectRootPath, '/') . '/typo3conf/AdditionalConfiguration_custom.php')) {
            return true;
        } else {
            return false;
        }
    }
}