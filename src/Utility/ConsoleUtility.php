<?php

namespace SourceBroker\DeployerExtendedTypo3\Utility;

use function Deployer\input;
use function Deployer\output;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConsoleUtility
 *
 * @package SourceBroker\DeployerExtendedTypo3\Utility
 */
class ConsoleUtility
{
    /**
     * Returns OutputInterface verbosity as parameter that can be used in cli command
     *
     * @return string
     */
    public function getVerbosityAsParameter()
    {
        switch (output()->getVerbosity()) {
            case OutputInterface::VERBOSITY_DEBUG:
                $verbosity = ' -vvv';
                break;
            case OutputInterface::VERBOSITY_VERY_VERBOSE:
                $verbosity = ' -vv';
                break;
            case OutputInterface::VERBOSITY_VERBOSE:
                $verbosity = ' -v';
                break;
            case OutputInterface::VERBOSITY_QUIET:
                $verbosity = ' -q';
                break;
            case OutputInterface::VERBOSITY_NORMAL:
            default:
                $verbosity = '';
        }
        return $verbosity;
    }
}
