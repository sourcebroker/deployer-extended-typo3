<?php

namespace SourceBroker\DeployerExtendedTypo3\Drivers;

use Deployer\Exception\ConfigurationException;
use Dotenv\Dotenv;
use SourceBroker\DeployerExtended\Utility\FileUtility;

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
        $this->createEnvFileIfDoesNotExist($params);

        if (file_exists($params['configDir'])) {
            $dotenv = new Dotenv($params['configDir']);
            $dotenv->load();

            switch ($this->getTypo3MajorVersion($params)) {
                case 6:
                case 7:
                    $dbConfig['host'] = getenv('TYPO3__DB__host');
                    $dbConfig['port'] = getenv('TYPO3__DB__port') ? getenv('TYPO3__DB__port') : 3306;
                    $dbConfig['dbname'] = getenv('TYPO3__DB__database');
                    $dbConfig['user'] = getenv('TYPO3__DB__username');
                    $dbConfig['password'] = getenv('TYPO3__DB__password');
                    break;

                case 8:
                    $dbConfig['host'] = getenv('TYPO3__DB__Connections__Default__host');
                    $dbConfig['port'] = getenv('TYPO3__DB__Connections__Default__port') ? getenv('TYPO3__DB__Connections__Default__port') : 3306;
                    $dbConfig['dbname'] = getenv('TYPO3__DB__Connections__Default__dbname');
                    $dbConfig['user'] = getenv('TYPO3__DB__Connections__Default__user');
                    $dbConfig['password'] = getenv('TYPO3__DB__Connections__Default__password');
                    break;

                default:
                    throw new \Exception('Not supported TYPO3 version: ' . $this->getTypo3MajorVersion($params));

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
        $this->createEnvFileIfDoesNotExist($params);

        if (file_exists(FileUtility::normalizeFolder($params['configDir']) . '/.env')) {
            $dotenv = new Dotenv($params['configDir']);
            $dotenv->load();
            $dotenv->required(['INSTANCE'])->notEmpty();
            return getenv('INSTANCE');

        } else {
            throw new \Exception('Missing file "' . $params['configDir'] . '".env.');
        }
    }


    /**
     * @param $params
     */
    public function createEnvFileIfDoesNotExist($params)
    {
        if (!file_exists(FileUtility::normalizeFolder($params['configDir']) . '/.env')) {

            if (getenv('TYPO3_CONTEXT') == false
                || getenv('INSTANCE') == false
                || getenv('DATABASE_HOST') == false
                || getenv('DATABASE_PASSWORD') == false
                || getenv('DATABASE_USER') == false
                || getenv('DATABASE_PORT') == false
            ) {
                throw new ConfigurationException('Create .env file yourself from .env.dist or it can be done automaticly for you. Read the docs. 
                Just pass TYPO3_CONTEXT_ENV, INSTANCE, DATABASE_HOST, DATABASE_PASSWORD, DATABASE_USER, DATABASE_PORT, TYPO3__GFX__im_path, TYPO3__GFX__im_path_lzw, TYPO3__GFX__colorspace as env.');
            }

            if (getenv('DATABASE_NAME') === false) {
                exec('cd ' . $params['configDir'] . ' && basename `git rev-parse --show-toplevel`', $output);
                if (isset($output[0]) && strlen($output[0]) == 0) {
                    throw new ConfigurationException('Can not get git repo name from "basename `git rev-parse --show-toplevel`" command. Its needed to create database.');
                }
                $databaseBaseName = 'typo3_' . $output[0];
            } else {
                $databaseBaseName = getenv('DATABASE_NAME');
            }
            $host = getenv('DATABASE_HOST');
            $username = getenv('DATABASE_USER');
            $password = getenv('DATABASE_PASSWORD');
            $port = getenv('DATABASE_PORT');
            $databaseName = '';
            try {
                $mysqli = new \mysqli($host, $username, $password);
                $databaseCreated = false;
                $i = 0;
                while ($databaseCreated == false && $i < 3) {
                    $databaseName = $databaseBaseName . ($i ? '_' . $i : '');
                    $mysqli->query("CREATE DATABASE IF NOT EXISTS " . $databaseName);
                    $result = $mysqli->query("SELECT COUNT(DISTINCT `table_name`) AS table_counter FROM `information_schema`.`columns` WHERE `table_schema` = '" . $databaseName . "'");
                    if (intval($result->fetch_assoc()['table_counter']) === 0) {
                        $databaseCreated = true;
                    } else {
                        $i++;
                    }
                }
            } catch (\mysqli_sql_exception $e) {
                throw $e;
            }

            $envDist = file_get_contents(FileUtility::normalizeFolder($params['configDir']) . '/.env.dist');

            $replace = [
                'TYPO3_CONTEXT' => getenv('TYPO3_CONTEXT'),
                'INSTANCE' => getenv('INSTANCE'),
                'DATABASE_NAME' => $databaseName,
                'DATABASE_HOST' => $host,
                'DATABASE_PASSWORD' => $password,
                'DATABASE_PORT' => $port,
                'DATABASE_USER' => $username,
                'IMAGE_MAGICK_PATH' => getenv('IMAGE_MAGICK_PATH'),
                'IMAGE_MAGICK_COLORSPACE' => getenv('IMAGE_MAGICK_COLORSPACE')
            ];

            $envDistMarkersReplaced = preg_replace_callback(
                "/\\{([A-Za-z0-9_:]+)\\}/",
                function ($match) use ($replace) {
                    return $replace[$match[1]];
                },
                $envDist
            );

            file_put_contents(FileUtility::normalizeFolder($params['configDir']) . '/.env', $envDistMarkersReplaced);
        }
    }

    /**
     * @param $params
     * @return int|null
     * @throws \Exception
     */
    public function getTypo3MajorVersion($params)
    {
        $typo3MajorVersion = null;
        if (file_exists(FileUtility::normalizeFolder($params['configDir']) . '/typo3/backend.php')) {
            $typo3MajorVersion = 6;
        }
        if (file_exists(FileUtility::normalizeFolder($params['configDir']) . '/typo3/init.php')) {
            $typo3MajorVersion = 7;
        }
        if (!file_exists(FileUtility::normalizeFolder($params['configDir']) . '/typo3/init.php')) {
            $typo3MajorVersion = 8;
        }
        if (null == $typo3MajorVersion) {
            throw new \Exception('Cannot figure out the TYPO3 major version.');
        }
        return $typo3MajorVersion;
    }

    public function load()
    {
        \SourceBroker\DeployerExtended\Utility\FileUtility::requireFilesFromDirectoryReqursively(__DIR__ . '/../deployer/');
    }
}