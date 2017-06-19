<?php

namespace SourceBroker\DeployerExtendedTypo3\Drivers;

use Dotenv\Dotenv;
use SourceBroker\DeployerExtended\Utility\FileUtility;

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
    public function getDatabaseConfig($dbMappingFields = null, $absolutePathWithConfig = null)
    {
        $absolutePathWithConfig = null === $absolutePathWithConfig ? getcwd() : $absolutePathWithConfig;
        $absolutePathWithConfig = rtrim($absolutePathWithConfig, DIRECTORY_SEPARATOR);
        $dbSettings = [];
        if (file_exists($absolutePathWithConfig . '/.env')) {
            (new Dotenv($absolutePathWithConfig))->load();
            foreach ($dbMappingFields as $key => $dbMappingField) {
                $dbSettings[$key] = getenv($dbMappingField);
            }
        } else {
            throw new \Exception('Missing file "' . $absolutePathWithConfig . '"/.env.');
        }
        return $dbSettings;
    }

    /**
     * @param $params
     */
//    public function createEnvFileIfDoesNotExist($params)
//    {
//        if (!file_exists(FileUtility::normalizeFolder($params['configDir']) . '/.env')) {
//
//            if (getenv('TYPO3_CONTEXT') == false
//                || getenv('INSTANCE') == false
//                || getenv('DATABASE_HOST') == false
//                || getenv('DATABASE_PASSWORD') == false
//                || getenv('DATABASE_USER') == false
//                || getenv('DATABASE_PORT') == false
//            ) {
//                throw new ConfigurationException('Create .env file yourself from .env.dist or it can be done automaticly for you. Read the docs.
//                Just pass TYPO3_CONTEXT_ENV, INSTANCE, DATABASE_HOST, DATABASE_PASSWORD, DATABASE_USER, DATABASE_PORT, TYPO3__GFX__im_path, TYPO3__GFX__im_path_lzw, TYPO3__GFX__colorspace as env.');
//            }
//
//            if (getenv('DATABASE_NAME') === false) {
//                exec('cd ' . $params['configDir'] . ' && basename `git rev-parse --show-toplevel`', $output);
//                if (isset($output[0]) && strlen($output[0]) == 0) {
//                    throw new ConfigurationException('Can not get git repo name from "basename `git rev-parse --show-toplevel`" command. Its needed to create database.');
//                }
//                $databaseBaseName = 'typo3_' . $output[0];
//            } else {
//                $databaseBaseName = getenv('DATABASE_NAME');
//            }
//            $host = getenv('DATABASE_HOST');
//            $username = getenv('DATABASE_USER');
//            $password = getenv('DATABASE_PASSWORD');
//            $port = getenv('DATABASE_PORT');
//            $databaseName = '';
//            try {
//                $mysqli = new \mysqli($host, $username, $password);
//                $databaseCreated = false;
//                $i = 0;
//                while ($databaseCreated == false && $i < 3) {
//                    $databaseName = $databaseBaseName . ($i ? '_' . $i : '');
//                    $mysqli->query("CREATE DATABASE IF NOT EXISTS " . $databaseName);
//                    $result = $mysqli->query("SELECT COUNT(DISTINCT `table_name`) AS table_counter FROM `information_schema`.`columns` WHERE `table_schema` = '" . $databaseName . "'");
//                    if (intval($result->fetch_assoc()['table_counter']) === 0) {
//                        $databaseCreated = true;
//                    } else {
//                        $i++;
//                    }
//                }
//            } catch (\mysqli_sql_exception $e) {
//                throw $e;
//            }
//
//            $envDist = file_get_contents(FileUtility::normalizeFolder($params['configDir']) . '/.env.dist');
//
//            $replace = [
//                'TYPO3_CONTEXT' => getenv('TYPO3_CONTEXT'),
//                'INSTANCE' => getenv('INSTANCE'),
//                'DATABASE_NAME' => $databaseName,
//                'DATABASE_HOST' => $host,
//                'DATABASE_PASSWORD' => $password,
//                'DATABASE_PORT' => $port,
//                'DATABASE_USER' => $username,
//                'IMAGE_MAGICK_PATH' => getenv('IMAGE_MAGICK_PATH'),
//                'IMAGE_MAGICK_COLORSPACE' => getenv('IMAGE_MAGICK_COLORSPACE')
//            ];
//
//            $envDistMarkersReplaced = preg_replace_callback(
//                "/\\{([A-Za-z0-9_:]+)\\}/",
//                function ($match) use ($replace) {
//                    return $replace[$match[1]];
//                },
//                $envDist
//            );
//
//            file_put_contents(FileUtility::normalizeFolder($params['configDir']) . '/.env', $envDistMarkersReplaced);
//        }
//    }
}