<?php

namespace SourceBroker\DeployerExtendedTypo3;

class Loader
{
    public function __construct()
    {
        require_once 'recipe/common.php';

        new \SourceBroker\DeployerExtendedDatabase\Loader();
        new \SourceBroker\DeployerExtendedMedia\Loader();
        new \SourceBroker\DeployerExtendedTypo3Tasks\Loader();
        new \SourceBroker\DeployerExtended\Loader();


        \SourceBroker\DeployerExtended\Utility\FileUtility::requireFilesFromDirectoryReqursively(
            dirname((new \ReflectionClass('\SourceBroker\DeployerExtendedTypo3\Loader'))->getFileName()) . '/../deployer/'
        );
    }
}